<?php

namespace App\Http\Controllers;

use App\Models\ApupptPostTestResult;
use App\Models\ApupptPostTestSession;
use Illuminate\Http\Request;

class ApupptQuizController extends Controller
{
    public function index()
    {
        $sessions = ApupptPostTestSession::withCount('questions')->latest()->paginate(10);

        return view('apuppt.quiz.index', compact('sessions'));
    }

    public function create()
    {
        return view('apuppt.quiz.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:1440',
            'status' => 'required|in:1,0',
            'tipe' => 'required|in:PATD,PATL,APUPPT',
        ]);

        $session = ApupptPostTestSession::create($data);

        return redirect()
            ->route('apuppt.posttest.edit', $session)
            ->with('success', 'Sesi berhasil dibuat. Silakan tambahkan soal.');
    }

    public function edit(ApupptPostTestSession $session)
    {
        $session->load(['questions' => fn ($q) => $q->orderBy('created_at')]);

        return view('apuppt.quiz.edit', compact('session'));
    }

    public function update(Request $request, ApupptPostTestSession $session)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required|integer|min:1|max:1440',
            'status' => 'required|in:1,0',
            'tipe' => 'required|in:PATD,PATL,APUPPT',
        ]);

        $session->update($data);

        return redirect()->route('apuppt.posttest.edit', $session)->with('success', 'Sesi berhasil diperbarui.');
    }

    public function destroy(ApupptPostTestSession $session)
    {
        $session->delete();

        return redirect()->route('apuppt.posttest.index')->with('success', 'Sesi berhasil dihapus.');
    }

    public function report(Request $request, ApupptPostTestSession $session)
    {
        $roleToCompany = [
            'Trainer (RFB)' => 'PT Rifan Financindo Berjangka',
            'Trainer (SGB)' => 'PT Solid Gold Berjangka',
            'Trainer (KPF)' => 'PT Kontak Perkasa Futures',
            'Trainer (BPF)' => 'PT Best Profit Futures',
            'Trainer (EWF)' => 'PT Equity World Futures',
        ];

        $q = trim($request->input('q', ''));
        $sort = $request->input('sort', 'latest');
        $perPage = (int) $request->input('per_page', 20) ?: 20;
        $company = trim((string) $request->input('company', ''));
        $cabang = trim((string) $request->input('cabang', ''));
        $cabang = str_replace(["\u{2013}", "\u{2014}", "\u{2212}"], '-', $cabang);

        $roleFilter = array_search($company, $roleToCompany, true) ?: null;

        $results = $session->results()
            ->with(['user:id,name,email,role,cabang,jabatan'])
            ->leftJoin('users', 'apuppt_post_test_results.user_id', '=', 'users.id')
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('users.email', 'like', "%{$q}%");
                });
            })
            ->when($roleFilter, fn ($qr) => $qr->where('users.role', $roleFilter))
            ->when($cabang !== '', fn ($qr) => $qr->where('users.cabang', $cabang))
            ->when($sort === 'highest', fn ($qr) => $qr->orderByDesc('apuppt_post_test_results.score'))
            ->when($sort === 'lowest', fn ($qr) => $qr->orderBy('apuppt_post_test_results.score'))
            ->when($sort === 'oldest', fn ($qr) => $qr->orderBy('apuppt_post_test_results.created_at'))
            ->when($sort === 'latest', fn ($qr) => $qr->orderByDesc('apuppt_post_test_results.created_at'))
            ->when($sort === 'lulus_first', fn ($qr) => $qr->orderByRaw('CASE WHEN apuppt_post_test_results.score >= 60 THEN 1 ELSE 0 END DESC'))
            ->when($sort === 'tidak_lulus_first', fn ($qr) => $qr->orderByRaw('CASE WHEN apuppt_post_test_results.score >= 60 THEN 1 ELSE 0 END ASC'))
            ->when($sort === 'cabang_asc', fn ($qr) => $qr->orderBy('users.cabang', 'asc'))
            ->when($sort === 'cabang_desc', fn ($qr) => $qr->orderBy('users.cabang', 'desc'))
            ->select('apuppt_post_test_results.*')
            ->paginate($perPage)
            ->withQueryString();

        $aggregates = $session->results()
            ->leftJoin('users', 'apuppt_post_test_results.user_id', '=', 'users.id')
            ->when($roleFilter, fn ($qr) => $qr->where('users.role', $roleFilter))
            ->when($cabang !== '', fn ($qr) => $qr->where('users.cabang', $cabang))
            ->selectRaw('COUNT(*) AS total, AVG(apuppt_post_test_results.score) AS avg_score, MAX(apuppt_post_test_results.score) AS max_score, MIN(apuppt_post_test_results.score) AS min_score')
            ->first();

        $rawRoleCounts = $session->results()
            ->leftJoin('users', 'users.id', '=', 'apuppt_post_test_results.user_id')
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('users.email', 'like', "%{$q}%");
                });
            })
            ->selectRaw('COALESCE(users.role, "TanpaRole") AS role_key, COUNT(*) AS total')
            ->groupBy('role_key')
            ->pluck('total', 'role_key');

        $companies = array_values($roleToCompany);
        $byCompany = collect($roleToCompany)->mapWithKeys(function ($companyName, $roleKey) use ($rawRoleCounts) {
            return [$companyName => (int) ($rawRoleCounts[$roleKey] ?? 0)];
        });
        $noRoleCount = (int) ($rawRoleCounts['TanpaRole'] ?? 0);

        $branches = $session->results()
            ->leftJoin('users', 'apuppt_post_test_results.user_id', '=', 'users.id')
            ->when($roleFilter, fn ($qr) => $qr->where('users.role', $roleFilter))
            ->when($cabang !== '', fn ($qr) => $qr->where('users.cabang', $cabang))
            ->whereNotNull('users.cabang')
            ->distinct()
            ->pluck('users.cabang')
            ->sort()
            ->values();

        return view('apuppt.quiz.report', [
            'session' => $session,
            'results' => $results,
            'aggregates' => $aggregates,
            'filters' => [
                'q' => $q,
                'sort' => $sort,
                'per_page' => $perPage,
                'company' => $company,
                'cabang' => $cabang,
            ],
            'companies' => $companies,
            'byCompany' => $byCompany,
            'noRoleCount' => $noRoleCount,
            'branches' => $branches,
        ]);
    }

    public function deleteResult(ApupptPostTestSession $session, ApupptPostTestResult $result)
    {
        if ($result->session_id !== $session->id) {
            abort(404);
        }

        if ($result->score >= 60) {
            return back()->with('error', 'Tidak dapat menghapus hasil yang lulus.');
        }

        $result->delete();

        return back()->with('success', 'Hasil post test berhasil dihapus.');
    }

    public function deleteAllFailed(ApupptPostTestSession $session)
    {
        $deletedCount = $session->results()->where('score', '<', 60)->delete();

        return back()->with('success', "Berhasil menghapus {$deletedCount} hasil post test yang tidak lulus.");
    }

    public function reportExport(Request $request, ApupptPostTestSession $session)
    {
        $filename = 'apuppt-posttest-report-' . $session->slug . '-' . now()->format('Ymd_His') . '.csv';

        $q = trim($request->input('q', ''));
        $sort = $request->input('sort', 'latest');
        $company = trim((string) $request->input('company', ''));
        $cabang = trim((string) $request->input('cabang', ''));
        $cabang = str_replace(["\u{2013}", "\u{2014}", "\u{2212}"], '-', $cabang);

        $roleFilter = array_search($company, [
            'Trainer (RFB)' => 'PT Rifan Financindo Berjangka',
            'Trainer (SGB)' => 'PT Solid Gold Berjangka',
            'Trainer (KPF)' => 'PT Kontak Perkasa Futures',
            'Trainer (BPF)' => 'PT Best Profit Futures',
            'Trainer (EWF)' => 'PT Equity World Futures',
        ], true) ?: null;

        $rows = $session->results()
            ->with(['user:id,name,email,role,cabang,jabatan'])
            ->leftJoin('users', 'apuppt_post_test_results.user_id', '=', 'users.id')
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('users.name', 'like', "%{$q}%")
                        ->orWhere('users.email', 'like', "%{$q}%");
                });
            })
            ->when($roleFilter, fn ($qr) => $qr->where('users.role', $roleFilter))
            ->when($cabang !== '', fn ($qr) => $qr->where('users.cabang', $cabang))
            ->when($sort === 'highest', fn ($qr) => $qr->orderByDesc('apuppt_post_test_results.score'))
            ->when($sort === 'lowest', fn ($qr) => $qr->orderBy('apuppt_post_test_results.score'))
            ->when($sort === 'oldest', fn ($qr) => $qr->orderBy('apuppt_post_test_results.created_at'))
            ->when($sort === 'latest', fn ($qr) => $qr->orderByDesc('apuppt_post_test_results.created_at'))
            ->when($sort === 'lulus_first', fn ($qr) => $qr->orderByRaw('CASE WHEN apuppt_post_test_results.score >= 60 THEN 1 ELSE 0 END DESC'))
            ->when($sort === 'tidak_lulus_first', fn ($qr) => $qr->orderByRaw('CASE WHEN apuppt_post_test_results.score >= 60 THEN 1 ELSE 0 END ASC'))
            ->when($sort === 'cabang_asc', fn ($qr) => $qr->orderBy('users.cabang', 'asc'))
            ->when($sort === 'cabang_desc', fn ($qr) => $qr->orderBy('users.cabang', 'desc'))
            ->select('apuppt_post_test_results.*')
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
