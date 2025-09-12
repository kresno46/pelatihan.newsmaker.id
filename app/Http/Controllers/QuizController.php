<?php

namespace App\Http\Controllers;

use App\Models\PostTestResult;
use App\Models\PostTestSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index()
    {
        $sessions = PostTestSession::withCount('questions')->latest()->paginate(10);
        return view('quiz.index', compact('sessions'));
    }

    public function create()
    {
        return view('quiz.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'     => 'required|string|max:255',
            'duration'  => 'required|integer|min:1|max:1440',
            'status'    => 'required|in:1,0',
            'tipe'      => 'required|in:PATD, PATL',
        ]);

        $session = PostTestSession::create($data); // slug dibuat otomatis di model

        return redirect()
            ->route('posttest.edit', $session) // route model binding pakai slug
            ->with('success', 'Sesi berhasil dibuat. Silakan tambahkan soal.');
    }

    public function edit(PostTestSession $session)
    {
        $session->load(['questions' => fn($q) => $q->orderBy('created_at')]);
        return view('quiz.edit', compact('session'));
    }

    public function update(Request $request, PostTestSession $session)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:1440',
            'status'    => 'required|in:1,0',
            'tipe'     => 'required|in:PATD,PATL',
        ]);

        $session->update($data);

        return redirect()
            ->route('posttest.edit', $session)
            ->with('success', 'Sesi berhasil diperbarui.');
    }

    public function destroy(PostTestSession $session)
    {
        $session->delete();
        return redirect()->route('posttest.index')->with('success', 'Sesi berhasil dihapus.');
    }

    public function report(Request $request, PostTestSession $session)
    {
        // Map role → nama perusahaan (harus sinkron dengan accessor di User)
        $roleToCompany = [
            'Trainer (RFB)' => 'PT Rifan Financindo Berjangka',
            'Trainer (SGB)' => 'PT Solid Gold Berjangka',
            'Trainer (KPF)' => 'PT Kontak Perkasa Futures',
            'Trainer (BPF)' => 'PT Best Profit Futures',
            'Trainer (EWF)' => 'PT Equity World Futures',
        ];

        $q         = trim($request->input('q', ''));
        $sort      = $request->input('sort', 'latest');       // latest|oldest|highest|lowest
        $perPage   = (int) $request->input('per_page', 12) ?: 12;
        $company   = trim((string) $request->input('company', '')); // filter berdasarkan NAMA PERUSAHAAN

        // Konversi filter perusahaan → kode role (karena query ke kolom users.role)
        $roleFilter = array_search($company, $roleToCompany, true) ?: null;

        // Query hasil
        $results = $session->results()
            ->with(['user:id,name,email,role'])   // <-- pastikan 'role' dibawa agar accessor bisa jalan
            ->when($q !== '', function ($qr) use ($q) {
                $qr->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($roleFilter, function ($qr) use ($roleFilter) {
                $qr->whereHas('user', fn($u) => $u->where('role', $roleFilter));
            })
            ->when($sort === 'highest', fn($qr) => $qr->orderByDesc('score'))
            ->when($sort === 'lowest',  fn($qr) => $qr->orderBy('score'))
            ->when($sort === 'oldest',  fn($qr) => $qr->orderBy('created_at'))
            ->when($sort === 'latest',  fn($qr) => $qr->orderByDesc('created_at'))
            ->paginate($perPage)
            ->withQueryString();

        // Agregat (ikuti filter company bila ada)
        $aggregates = $session->results()
            ->when($roleFilter, fn($qr) => $qr->whereHas('user', fn($u) => $u->where('role', $roleFilter)))
            ->selectRaw('COUNT(*) AS total, AVG(score) AS avg_score, MAX(score) AS max_score, MIN(score) AS min_score')
            ->first();

        // Rekap per perusahaan (group by users.role, lalu map ke nama perusahaan)
        $rawRoleCounts = $session->results()
            ->leftJoin('users', 'users.id', '=', 'post_test_results.user_id')
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('users.email', 'like', "%{$q}%");
                });
            })
            ->selectRaw('COALESCE(users.role, "TanpaRole") AS role_key, COUNT(*) AS total')
            ->groupBy('role_key')
            ->pluck('total', 'role_key');

        // Normalisasi ke nama perusahaan
        $companies = array_values($roleToCompany); // opsi dropdown
        $byCompany = collect($roleToCompany)->mapWithKeys(function ($companyName, $roleKey) use ($rawRoleCounts) {
            return [$companyName => (int) ($rawRoleCounts[$roleKey] ?? 0)];
        });
        $noRoleCount = (int) ($rawRoleCounts['TanpaRole'] ?? 0);

        return view('quiz.report', [
            'session'     => $session,
            'results'     => $results,
            'aggregates'  => $aggregates,
            'filters'     => [
                'q'        => $q,
                'sort'     => $sort,
                'per_page' => $perPage,
                'company'  => $company, // kirim nama perusahaan yg sedang difilter
            ],
            'companies'   => $companies,   // opsi dropdown perusahaan
            'byCompany'   => $byCompany,   // rekap per perusahaan (nama → total)
            'noRoleCount' => $noRoleCount, // jumlah tanpa role
        ]);
    }

    public function reportExport(Request $request, PostTestSession $session)
    {
        // (Kalau kamu sudah migrasi ke XLSX pakai Laravel Excel, ganti implementasi ini)
        $filename = 'posttest-report-' . $session->slug . '-' . now()->format('Ymd_His') . '.csv';

        $q         = trim($request->input('q', ''));
        $roleParam = trim((string) $request->input('role_pt', ''));
        $rolesPT   = ['Trainer (RFB)', 'Trainer (SGB)', 'Trainer (KPF)', 'Trainer (BPF)', 'Trainer (EWF)'];
        $roleFilter = in_array($roleParam, $rolesPT, true) ? $roleParam : null;

        $rows = $session->results()
            ->with(['user:id,name,email,role_pt'])
            ->when($q !== '', function ($qr) use ($q) {
                $qr->whereHas('user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($roleFilter, function ($qr) use ($roleFilter) {
                $qr->whereHas('user', fn($u) => $u->where('role_pt', $roleFilter));
            })
            ->orderByDesc('created_at')
            ->get(['id', 'user_id', 'score', 'created_at']);

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No', 'Nama', 'Email', 'RolePT', 'Skor', 'Dikirim pada']);
            foreach ($rows as $i => $r) {
                fputcsv($out, [
                    $i + 1,
                    optional($r->user)->name,
                    optional($r->user)->email,
                    optional($r->user)->role_pt,
                    $r->score,
                    optional($r->created_at)->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
