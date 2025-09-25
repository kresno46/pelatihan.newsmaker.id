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
            ->with(['user:id,name,email,role,cabang,jabatan'])   // <-- pastikan 'role' dibawa agar accessor bisa jalan
            ->leftJoin('users', 'post_test_results.user_id', '=', 'users.id')
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('users.email', 'like', "%{$q}%");
                });
            })
            ->when($roleFilter, function ($qr) use ($roleFilter) {
                $qr->where('users.role', $roleFilter);
            })
            ->when($sort === 'highest', fn($qr) => $qr->orderByDesc('post_test_results.score'))
            ->when($sort === 'lowest',  fn($qr) => $qr->orderBy('post_test_results.score'))
            ->when($sort === 'oldest',  fn($qr) => $qr->orderBy('post_test_results.created_at'))
            ->when($sort === 'latest',  fn($qr) => $qr->orderByDesc('post_test_results.created_at'))
            ->when($sort === 'lulus_first', fn($qr) => $qr->orderByRaw('CASE WHEN post_test_results.score >= 60 THEN 1 ELSE 0 END DESC'))
            ->when($sort === 'tidak_lulus_first', fn($qr) => $qr->orderByRaw('CASE WHEN post_test_results.score >= 60 THEN 1 ELSE 0 END ASC'))
            ->when($sort === 'cabang_asc', fn($qr) => $qr->orderBy('users.role', 'asc')->orderBy('users.cabang', 'asc'))
            ->when($sort === 'cabang_desc', fn($qr) => $qr->orderBy('users.role', 'desc')->orderBy('users.cabang', 'desc'))
            ->when(preg_match('/^cabang_asc_(.+)$/', $sort, $matches), function ($qr) use ($matches) {
                $branch = $matches[1];
                $qr->where('users.cabang', $branch)->orderBy('users.cabang', 'asc');
            })
            ->when(preg_match('/^cabang_desc_(.+)$/', $sort, $matches), function ($qr) use ($matches) {
                $branch = $matches[1];
                $qr->where('users.cabang', $branch)->orderBy('users.cabang', 'desc');
            })
            ->select('post_test_results.*')
            ->paginate($perPage)
            ->withQueryString();

        // Agregat (ikuti filter company bila ada)
        $aggregates = $session->results()
            ->leftJoin('users', 'post_test_results.user_id', '=', 'users.id')
            ->when($roleFilter, fn($qr) => $qr->where('users.role', $roleFilter))
            ->selectRaw('COUNT(*) AS total, AVG(post_test_results.score) AS avg_score, MAX(post_test_results.score) AS max_score, MIN(post_test_results.score) AS min_score')
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

        // Get distinct branches for the selected company (role)
        $branches = [];
        if ($roleFilter) {
            $branches = \App\Models\User::where('role', $roleFilter)
                ->whereNotNull('cabang')
                ->where('cabang', '!=', '')
                ->distinct()
                ->pluck('cabang')
                ->sort()
                ->toArray();
        }

        // Get all branches grouped by company
        $allBranches = [];
        foreach ($roleToCompany as $role => $company) {
            $branchList = \App\Models\User::where('role', $role)
                ->whereNotNull('cabang')
                ->where('cabang', '!=', '')
                ->distinct()
                ->pluck('cabang')
                ->sort()
                ->toArray();
            if (!empty($branchList)) {
                $allBranches[$company] = $branchList;
            }
        }

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
            'branches'    => $branches,    // cabang untuk sort dinamis
            'allBranches' => $allBranches, // semua cabang per perusahaan
        ]);
    }

    public function deleteResult(PostTestSession $session, PostTestResult $result)
    {
        // Ensure the result belongs to the session
        if ($result->session_id !== $session->id) {
            abort(404);
        }

        // Only allow deleting if score < 60
        if ($result->score >= 60) {
            return back()->with('error', 'Tidak dapat menghapus hasil yang lulus.');
        }

        $result->delete();

        return back()->with('success', 'Hasil post test berhasil dihapus.');
    }

    public function deleteAllFailed(PostTestSession $session)
    {
        $deletedCount = $session->results()->where('score', '<', 60)->delete();

        return back()->with('success', "Berhasil menghapus {$deletedCount} hasil post test yang tidak lulus.");
    }

    public function reportExport(Request $request, PostTestSession $session)
    {
        // (Kalau kamu sudah migrasi ke XLSX pakai Laravel Excel, ganti implementasi ini)
        $filename = 'posttest-report-' . $session->slug . '-' . now()->format('Ymd_His') . '.csv';

        $q         = trim($request->input('q', ''));
        $sort      = $request->input('sort', 'latest');       // latest|oldest|highest|lowest
        $company   = trim((string) $request->input('company', '')); // filter berdasarkan NAMA PERUSAHAAN

        // Konversi filter perusahaan → kode role (karena query ke kolom users.role)
        $roleFilter = array_search($company, [
            'Trainer (RFB)' => 'PT Rifan Financindo Berjangka',
            'Trainer (SGB)' => 'PT Solid Gold Berjangka',
            'Trainer (KPF)' => 'PT Kontak Perkasa Futures',
            'Trainer (BPF)' => 'PT Best Profit Futures',
            'Trainer (EWF)' => 'PT Equity World Futures',
        ], true) ?: null;

        $rows = $session->results()
            ->with(['user:id,name,email,role,cabang,jabatan'])
            ->leftJoin('users', 'post_test_results.user_id', '=', 'users.id')
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('users.email', 'like', "%{$q}%");
                });
            })
            ->when($roleFilter, function ($qr) use ($roleFilter) {
                $qr->where('users.role', $roleFilter);
            })
            ->when($sort === 'highest', fn($qr) => $qr->orderByDesc('post_test_results.score'))
            ->when($sort === 'lowest',  fn($qr) => $qr->orderBy('post_test_results.score'))
            ->when($sort === 'oldest',  fn($qr) => $qr->orderBy('post_test_results.created_at'))
            ->when($sort === 'latest',  fn($qr) => $qr->orderByDesc('post_test_results.created_at'))
            ->when($sort === 'lulus_first', fn($qr) => $qr->orderByRaw('CASE WHEN post_test_results.score >= 60 THEN 1 ELSE 0 END DESC'))
            ->when($sort === 'tidak_lulus_first', fn($qr) => $qr->orderByRaw('CASE WHEN post_test_results.score >= 60 THEN 1 ELSE 0 END ASC'))
            ->when($sort === 'cabang_asc', fn($qr) => $qr->orderBy('users.role', 'asc')->orderBy('users.cabang', 'asc'))
            ->when($sort === 'cabang_desc', fn($qr) => $qr->orderBy('users.role', 'desc')->orderBy('users.cabang', 'desc'))
            ->when(preg_match('/^cabang_asc_(.+)$/', $sort, $matches), function ($qr) use ($matches) {
                $branch = $matches[1];
                $qr->where('users.cabang', $branch)->orderBy('users.cabang', 'asc');
            })
            ->when(preg_match('/^cabang_desc_(.+)$/', $sort, $matches), function ($qr) use ($matches) {
                $branch = $matches[1];
                $qr->where('users.cabang', $branch)->orderBy('users.cabang', 'desc');
            })
            ->select('post_test_results.*')
            ->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No', 'Nama', 'Perusahaan', 'Cabang', 'Jabatan', 'Skor', 'Status', 'Tanggal'], ';');
            foreach ($rows as $i => $r) {
                fputcsv($out, [
                    $i + 1,
                    optional($r->user)->name,
                    optional($r->user)->nama_perusahaan,
                    optional($r->user)->cabang,
                    optional($r->user)->jabatan,
                    $r->score,
                    $r->score >= 60 ? 'Lulus' : 'Tidak Lulus',
                    optional($r->created_at)->format('Y-m-d H:i'),
                ], ';');
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
