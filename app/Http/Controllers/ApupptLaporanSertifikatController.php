<?php

namespace App\Http\Controllers;

use App\Exports\ApupptSertifikatExport;
use App\Exports\ApupptSertifikatPerCabangExport;
use App\Models\ApupptCertificateAward;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ApupptLaporanSertifikatController extends Controller
{
    public function index(Request $request)
    {
        $query = ApupptCertificateAward::with(['user', 'postTestResult.session']);
        $showNameOnly = false;

        if ($request->filled('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('company')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->company);
            });
        }

        if ($request->filled('cabang')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('cabang', $request->cabang);
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('postTestResult.session', function ($q) use ($request) {
                if (in_array($request->kategori, ['PATD', 'PATL', 'APUPPT'])) {
                    $q->where('tipe', $request->kategori);
                }
            });
        }

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('awarded_at', 'asc');
                break;
            case 'highest':
                $query->orderBy('average_score', 'desc');
                break;
            case 'lowest':
                $query->orderBy('average_score', 'asc');
                break;
            case 'name_asc':
                $query->join('users', 'apuppt_certificate_awards.user_id', '=', 'users.id')->orderBy('users.name', 'asc');
                break;
            case 'name_desc':
                $query->join('users', 'apuppt_certificate_awards.user_id', '=', 'users.id')->orderBy('users.name', 'desc');
                break;
            case 'company_asc':
                $query->join('users', 'apuppt_certificate_awards.user_id', '=', 'users.id')->orderBy('users.role', 'asc');
                break;
            case 'company_desc':
                $query->join('users', 'apuppt_certificate_awards.user_id', '=', 'users.id')->orderBy('users.role', 'desc');
                break;
            case 'cabang_asc':
                $query->join('users', 'apuppt_certificate_awards.user_id', '=', 'users.id')->orderBy('users.cabang', 'asc');
                break;
            case 'cabang_desc':
                $query->join('users', 'apuppt_certificate_awards.user_id', '=', 'users.id')->orderBy('users.cabang', 'desc');
                break;
            case 'awarded_asc':
                $query->orderBy('awarded_at', 'asc');
                break;
            case 'awarded_desc':
                $query->orderBy('awarded_at', 'desc');
                break;
            default:
                $query->orderBy('awarded_at', 'desc');
                break;
        }

        $perPage = $request->get('per_page', 20);
        $sertifikats = $query->paginate($perPage);

        $aggregates = ApupptCertificateAward::selectRaw('COUNT(*) as total, AVG(average_score) as avg_score, MAX(average_score) as max_score, MIN(average_score) as min_score')->first();

        return view('apuppt.LaporanSertifikat.index', compact('sertifikats', 'aggregates', 'showNameOnly'));
    }

    public function destroy($id)
    {
        $sertifikat = ApupptCertificateAward::findOrFail($id);
        $sertifikat->delete();

        return redirect()->back()->with('Alert', 'Sertifikat berhasil dihapus.');
    }

    public function export(Request $request)
    {
        $query = ApupptCertificateAward::with(['user', 'postTestResult.session']);

        if ($request->filled('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            });
        }

        if ($request->filled('company')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->company);
            });
        }

        if ($request->filled('cabang')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('cabang', $request->cabang);
            });
        }

        $filename = 'apuppt_laporan_sertifikat_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ApupptSertifikatExport($query), $filename);
    }

    public function exportPerCabang(Request $request)
    {
        $cabang = $request->get('cabang');
        if (! $cabang) {
            return redirect()->back()->with('error', 'Cabang harus dipilih untuk export per cabang.');
        }

        $query = ApupptCertificateAward::with(['user', 'postTestResult.session'])
            ->whereHas('user', function ($q) use ($cabang) {
                $q->where('cabang', $cabang);
            });

        $filename = 'apuppt_laporan_sertifikat_' . str_replace(' ', '_', $cabang) . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new ApupptSertifikatPerCabangExport($query, $cabang), $filename);
    }
}
