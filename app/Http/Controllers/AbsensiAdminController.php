<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\AbsensiExport;
use App\Models\Absensi;
use App\Models\JadwalAbsensi;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AbsensiAdminController extends Controller
{
    // Tampilkan kategori role PT
    public function indexAdmin($idJadwal, Request $request)
    {
        $jadwal = JadwalAbsensi::findOrFail($idJadwal);

        $rolesPT = [
            'Trainer (RFB)',
            'Trainer (SGB)',
            'Trainer (KPF)',
            'Trainer (BPF)',
            'Trainer (EWF)',
        ];

        $query = Absensi::with('user')->where('jadwal_id', $idJadwal);

        // Filter berdasarkan pencarian nama
        if ($request->filled('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            });
        }

        // Filter berdasarkan perusahaan
        if ($request->filled('company')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->company);
            });
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('waktu_absen', 'asc');
                break;
            case 'name_asc':
                $query->join('users', 'absensis.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc');
                break;
            case 'name_desc':
                $query->join('users', 'absensis.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc');
                break;
            case 'company_asc':
                $query->join('users', 'absensis.user_id', '=', 'users.id')
                    ->orderBy('users.role', 'asc');
                break;
            case 'company_desc':
                $query->join('users', 'absensis.user_id', '=', 'users.id')
                    ->orderBy('users.role', 'desc');
                break;
            default:
                $query->orderBy('waktu_absen', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 20);
        $absensiList = $query->paginate($perPage);

        // Aggregates untuk ringkasan
        $aggregates = Absensi::where('jadwal_id', $idJadwal)->selectRaw('
            COUNT(*) as total
        ')->first();

        return view('AbsensiAdmin.index', compact('jadwal', 'rolesPT', 'absensiList', 'aggregates'));
    }

    public function delete($idJadwal, $idAbsensi)
    {
        $jadwalAbsensi = JadwalAbsensi::findOrFail($idJadwal);

        $absensi = Absensi::findOrFail($idAbsensi);

        $absensi->delete(); // Jangan lupa ini agar datanya benar-benar dihapus

        return redirect()->back()->with('Alert', $absensi->user->nama . ' berhasil dihapus!');
    }

    public function downloadExcel($idJadwal)
    {
        $absensiList = Absensi::with('user')->where('jadwal_id', $idJadwal)->get();
        $jadwal = JadwalAbsensi::findOrFail($idJadwal);

        $judul = Str::slug($jadwal->title, '_'); // Ubah jadi format file-friendly
        $tanggal = Carbon::now()->format('Ymd_His');

        $fileName = "absensi_{$judul}_{$tanggal}.xlsx";

        return Excel::download(new AbsensiExport($absensiList), $fileName);
    }

    public function downloadPdf($idJadwal)
    {
        $jadwal = JadwalAbsensi::findOrFail($idJadwal);
        $absensiList = Absensi::with('user')->where('jadwal_id', $idJadwal)->get();

        $judul = Str::slug($jadwal->title, '_');
        $tanggal = Carbon::now()->format('Ymd_His');

        $fileName = "absensi_{$judul}_{$tanggal}.pdf";

        $pdf = Pdf::loadView('AbsensiAdmin.PdfExport', compact('absensiList', 'jadwal'));

        return $pdf->download($fileName);
    }
}
