<?php

namespace App\Http\Controllers;

use App\Exports\ApupptAbsensiExport;
use App\Models\ApupptAbsensi;
use App\Models\ApupptJadwalAbsensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ApupptAbsensiAdminController extends Controller
{
    public function indexAdmin($idJadwal, Request $request)
    {
        $jadwal = ApupptJadwalAbsensi::findOrFail($idJadwal);

        $rolesPT = ['Trainer (RFB)', 'Trainer (SGB)', 'Trainer (KPF)', 'Trainer (BPF)', 'Trainer (EWF)'];

        $query = ApupptAbsensi::with('user')->where('jadwal_id', $idJadwal);

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

        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('waktu_absen', 'asc');
                break;
            case 'name_asc':
                $query->join('users', 'apuppt_absensi_logs.user_id', '=', 'users.id')->orderBy('users.name', 'asc');
                break;
            case 'name_desc':
                $query->join('users', 'apuppt_absensi_logs.user_id', '=', 'users.id')->orderBy('users.name', 'desc');
                break;
            case 'company_asc':
                $query->join('users', 'apuppt_absensi_logs.user_id', '=', 'users.id')->orderBy('users.role', 'asc');
                break;
            case 'company_desc':
                $query->join('users', 'apuppt_absensi_logs.user_id', '=', 'users.id')->orderBy('users.role', 'desc');
                break;
            case 'cabang_asc':
                $query->join('users', 'apuppt_absensi_logs.user_id', '=', 'users.id')->orderBy('users.cabang', 'asc');
                break;
            case 'cabang_desc':
                $query->join('users', 'apuppt_absensi_logs.user_id', '=', 'users.id')->orderBy('users.cabang', 'desc');
                break;
            default:
                $query->orderBy('waktu_absen', 'desc');
                break;
        }

        $perPage = $request->get('per_page', 20);
        $absensiList = $query->paginate($perPage);

        $aggregates = ApupptAbsensi::where('jadwal_id', $idJadwal)->selectRaw('COUNT(*) as total')->first();

        return view('apuppt.AbsensiAdmin.index', compact('jadwal', 'rolesPT', 'absensiList', 'aggregates'));
    }

    public function delete($idJadwal, $idAbsensi)
    {
        $absensi = ApupptAbsensi::findOrFail($idAbsensi);
        $absensi->delete();

        return redirect()->back()->with('Alert', ($absensi->user->nama ?? $absensi->user->name) . ' berhasil dihapus!');
    }

    public function downloadExcel($idJadwal)
    {
        $absensiList = ApupptAbsensi::with('user')->where('jadwal_id', $idJadwal)->get();
        $jadwal = ApupptJadwalAbsensi::findOrFail($idJadwal);

        $judul = Str::slug($jadwal->title, '_');
        $tanggal = Carbon::now()->format('Ymd_His');
        $fileName = "apuppt_absensi_{$judul}_{$tanggal}.xlsx";

        return Excel::download(new ApupptAbsensiExport($absensiList), $fileName);
    }

    public function downloadPdf($idJadwal)
    {
        $jadwal = ApupptJadwalAbsensi::findOrFail($idJadwal);
        $absensiList = ApupptAbsensi::with('user')->where('jadwal_id', $idJadwal)->get();

        $judul = Str::slug($jadwal->title, '_');
        $tanggal = Carbon::now()->format('Ymd_His');
        $fileName = "apuppt_absensi_{$judul}_{$tanggal}.pdf";

        $pdf = Pdf::loadView('apuppt.AbsensiAdmin.PdfExport', compact('absensiList', 'jadwal'));

        return $pdf->download($fileName);
    }

    public function downloadExcelPerCabang($idJadwal, Request $request)
    {
        $jadwal = ApupptJadwalAbsensi::findOrFail($idJadwal);

        $query = ApupptAbsensi::with('user')->where('jadwal_id', $idJadwal);

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

        $absensiList = $query->get();

        $judul = Str::slug($jadwal->title, '_');
        $cabang = $request->cabang ? Str::slug($request->cabang, '_') : 'semua';
        $tanggal = Carbon::now()->format('Ymd_His');
        $fileName = "apuppt_absensi_{$judul}_{$cabang}_{$tanggal}.xlsx";

        return Excel::download(new ApupptAbsensiExport($absensiList), $fileName);
    }

    public function downloadPdfPerCabang($idJadwal, Request $request)
    {
        $jadwal = ApupptJadwalAbsensi::findOrFail($idJadwal);

        $query = ApupptAbsensi::with('user')->where('jadwal_id', $idJadwal);

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

        $absensiList = $query->get();

        $judul = Str::slug($jadwal->title, '_');
        $cabang = $request->cabang ? Str::slug($request->cabang, '_') : 'semua';
        $tanggal = Carbon::now()->format('Ymd_His');
        $fileName = "apuppt_absensi_{$judul}_{$cabang}_{$tanggal}.pdf";

        $pdf = Pdf::loadView('apuppt.AbsensiAdmin.PdfExport', compact('absensiList', 'jadwal'));

        return $pdf->download($fileName);
    }
}