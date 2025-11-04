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
            'tipe'      => 'required|in:PATD,PATL',
        ]);

        $session = PostTestSession::create($data); // slug dibuat otomatis di model

        return redirect()
            ->route('posttest.edit', $session->slug)
            ->with('success', 'Sesi berhasil dibuat. Silakan tambahkan soal.');
    }

    public function edit(PostTestSession $session)
    {
        $session->load(['questions' => fn($q) => $q->orderBy('created_at')]);
        return view('quiz.edit', compact('session'));
    }

    /**
     * ✅ FIX: update sekarang benar-benar pakai model binding $session (slug)
     */
    public function update(Request $request, PostTestSession $session)
    {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:1440',
            'status'   => 'required|in:1,0',
            'tipe'     => 'required|in:PATD,PATL',
        ]);

        $session->update($data);

        return redirect()
            ->route('posttest.edit', $session->slug)
            ->with('success', 'Sesi berhasil diperbarui.');
    }

    public function destroy(PostTestSession $session)
    {
        $session->delete();
        return redirect()->route('posttest.index')->with('success', 'Sesi berhasil dihapus.');
    }

    public function report(Request $request, PostTestSession $session)
    {
        $roleToCompany = [
            'Trainer (RFB)' => 'PT Rifan Financindo Berjangka',
            'Trainer (SGB)' => 'PT Solid Gold Berjangka',
            'Trainer (KPF)' => 'PT Kontak Perkasa Futures',
            'Trainer (BPF)' => 'PT Best Profit Futures',
            'Trainer (EWF)' => 'PT Equity World Futures',
        ];

        $q        = trim($request->input('q', ''));
        $sort     = $request->input('sort', 'latest');
        $perPage  = (int) $request->input('per_page', 12);
        $company  = trim($request->input('company', ''));
        $branch   = trim($request->input('branch', ''));

        $roleFilter = array_search($company, $roleToCompany, true) ?: null;

        $query = $session->results()
            ->with(['user:id,name,email,role,cabang,jabatan'])
            ->leftJoin('users', 'post_test_results.user_id', '=', 'users.id')
            ->when($q !== '', fn($qr) => $qr->where(function ($w) use ($q) {
                $w->where('users.name', 'like', "%{$q}%")
                    ->orWhere('users.email', 'like', "%{$q}%");
            }))
            ->when($roleFilter, fn($qr) => $qr->where('users.role', $roleFilter))
            ->when($branch !== '', fn($qr) => $qr->where('users.cabang', $branch))
            ->select('post_test_results.*');

        match ($sort) {
            'oldest' => $query->orderBy('post_test_results.created_at', 'asc'),
            'highest' => $query->orderBy('post_test_results.score', 'desc'),
            'lowest' => $query->orderBy('post_test_results.score', 'asc'),
            default => $query->orderBy('post_test_results.created_at', 'desc'),
        };

        $results = $query->paginate($perPage)->withQueryString();

        $aggregates = $session->results()
            ->leftJoin('users', 'post_test_results.user_id', '=', 'users.id')
            ->when($roleFilter, fn($qr) => $qr->where('users.role', $roleFilter))
            ->when($branch !== '', fn($qr) => $qr->where('users.cabang', $branch))
            ->selectRaw('COUNT(*) AS total, 
                         ROUND(AVG(post_test_results.score),2) AS avg_score, 
                         MAX(post_test_results.score) AS max_score, 
                         MIN(post_test_results.score) AS min_score')
            ->first();

        $kantorCabang = [
            'RFB' => ['Palembang', 'Balikpapan', 'Solo', 'Jakarta DBS Tower', 'Jakarta AXA Tower', 'Jakarta AXA 1', 'Jakarta AXA 2', 'Jakarta AXA 3', 'Medan', 'Semarang', 'Surabaya Pakuwon', 'Surabaya Ciputra', 'Pekanbaru', 'Bandung', 'Yogyakarta'],
            'SGB' => ['Jakarta', 'Semarang', 'Makassar'],
            'KPF' => ['Jakarta', 'Yogyakarta', 'Bali', 'Makassar', 'Bandung', 'Semarang'],
            'EWF' => ['SCC Jakarta', 'Cyber 2 Jakarta', 'Surabaya Trilium', 'Manado', 'Semarang', 'Surabaya Praxis', 'Cirebon'],
            'BPF' => ['Equity Tower Jakarta', 'Jambi', 'Jakarta - Pacific Place Mall', 'Pontianak', 'Malang', 'Surabaya', 'Medan', 'Bandung', 'Pekanbaru', 'Banjarmasin', 'Bandar Lampung', 'Semarang'],
        ];

        $branches = collect();
        if ($roleFilter) {
            preg_match('/\((.*?)\)/', $roleFilter, $matches);
            $roleKey = $matches[1] ?? null;
            if ($roleKey && isset($kantorCabang[$roleKey])) {
                $branches = collect($kantorCabang[$roleKey]);
            }
        }

        $rawRoleCounts = $session->results()
            ->leftJoin('users', 'users.id', '=', 'post_test_results.user_id')
            ->when($q !== '', fn($qr) => $qr->where(function ($w) use ($q) {
                $w->where('users.name', 'like', "%{$q}%")
                    ->orWhere('users.email', 'like', "%{$q}%");
            }))
            ->selectRaw('COALESCE(users.role, "TanpaRole") AS role_key, COUNT(*) AS total')
            ->groupBy('role_key')
            ->pluck('total', 'role_key');

        $companies = array_values($roleToCompany);
        $byCompany = collect($roleToCompany)->mapWithKeys(
            fn($companyName, $roleKey) => [$companyName => (int) ($rawRoleCounts[$roleKey] ?? 0)]
        );
        $noRoleCount = (int) ($rawRoleCounts['TanpaRole'] ?? 0);

        return view('quiz.report', [
            'session'     => $session,
            'results'     => $results,
            'aggregates'  => $aggregates,
            'filters'     => [
                'q'        => $q,
                'sort'     => $sort,
                'per_page' => $perPage,
                'company'  => $company,
                'branch'   => $branch,
            ],
            'companies'   => $companies,
            'branches'    => $branches,
            'byCompany'   => $byCompany,
            'noRoleCount' => $noRoleCount,
        ]);
    }

    public function deleteResult(PostTestSession $session, PostTestResult $result)
    {
        if ($result->session_id !== $session->id) abort(404);
        if ($result->score >= 60)
            return back()->with('error', 'Tidak dapat menghapus hasil yang lulus.');

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
        $q = trim($request->input('q', ''));
        $sort = $request->input('sort', 'latest');
        $company = trim($request->input('company', ''));
        $branch = trim($request->input('branch', ''));

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
            ->when($q !== '', fn($qr) => $qr->where(function ($w) use ($q) {
                $w->where('users.name', 'like', "%{$q}%")
                    ->orWhere('users.email', 'like', "%{$q}%");
            }))
            ->when($roleFilter, fn($qr) => $qr->where('users.role', $roleFilter))
            ->when($branch !== '', fn($qr) => $qr->where('users.cabang', $branch))
            ->when($sort === 'latest', fn($qr) => $qr->orderByDesc('post_test_results.created_at'))
            ->select('post_test_results.*')
            ->get();

        $filename = 'posttest-report-' . $session->slug;
        if ($branch !== '') {
            $filename .= '-' . str_replace([' ', '/', '\\', ':'], '_', $branch);
        }
        $filename .= '-' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['No', 'Nama', 'Perusahaan', 'Cabang', 'Jabatan', 'Skor', 'Status', 'Tanggal']);
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
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
