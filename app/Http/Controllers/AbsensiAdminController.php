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

        $selectedRole = $request->query('role');
        $search = $request->query('search');
        $sortBy = $request->query('sort_by', 'name');  // Default to 'name' sorting

        $absensiList = Absensi::with('user')
            ->where('jadwal_id', $idJadwal)
            ->when($selectedRole, function ($query) use ($selectedRole) {
                return $query->whereHas('user', function ($query) use ($selectedRole) {
                    $query->where('role', $selectedRole);
                });
            })
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            })
            ->orderBy(User::select('name')->whereColumn('users.id', 'absensis.user_id'), 'asc')  // Correct the sorting
            ->get();

        return view('AbsensiAdmin.index', compact('jadwal', 'rolesPT', 'selectedRole', 'absensiList', 'search', 'sortBy'));
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
        $absensiList = Absensi::where('jadwal_id', $idJadwal)->get();
        $jadwal = JadwalAbsensi::findOrFail($idJadwal);

        $judul = Str::slug($jadwal->title, '_'); // Ubah jadi format file-friendly
        $tanggal = Carbon::now()->format('Ymd_His');

        $fileName = "absensi_{$judul}_{$tanggal}.xlsx";

        return Excel::download(new AbsensiExport($absensiList), $fileName);
    }

    public function downloadPdf($idJadwal)
    {
        $jadwal = JadwalAbsensi::findOrFail($idJadwal);
        $absensiList = Absensi::where('jadwal_id', $idJadwal)->get();

        $judul = Str::slug($jadwal->title, '_');
        $tanggal = Carbon::now()->format('Ymd_His');

        $fileName = "absensi_{$judul}_{$tanggal}.pdf";

        $pdf = Pdf::loadView('AbsensiAdmin.PdfExport', compact('absensiList', 'jadwal'));

        return $pdf->download($fileName);
    }
}
