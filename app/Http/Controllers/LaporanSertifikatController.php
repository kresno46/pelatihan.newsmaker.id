<?php

namespace App\Http\Controllers;

use App\Models\CertificateAward;
use Illuminate\Http\Request;

class LaporanSertifikatController extends Controller
{
    /**
     * Menampilkan daftar sertifikat yang telah diunduh.
     */
    public function index(Request $request)
    {
        $search = $request->input('search'); // Ambil nilai pencarian dari input form

        // Ambil sertifikat dengan pagination dan pencarian
        $sertifikats = CertificateAward::with(['user'])
            ->orderByDesc('awarded_at') // Urutkan berdasarkan tanggal awarded_at
            ->when($search, function ($query, $search) {
                // Jika ada query pencarian, cari berdasarkan nama user atau kriteria lainnya
                return $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10); // Menampilkan 10 data per halaman

        return view('LaporanSertifikat.index', compact('sertifikats', 'search'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function show(string $id)
    {
        abort(404);
    }

    public function edit(string $id)
    {
        abort(404);
    }

    public function update(Request $request, string $id)
    {
        abort(404);
    }

    public function destroy($id)
    {
        $sertifikat = CertificateAward::findOrFail($id);

        // Path ke file sertifikat (pastikan sesuai struktur path yang digunakan di sistem Anda)
        $filePath = storage_path('app/public/sertifikat/' . $sertifikat->certificate_uuid . '.pdf');

        // Hapus file jika ada
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus data dari database
        $sertifikat->delete();

        return redirect()->back()->with('Alert', 'Sertifikat berhasil dihapus.');
    }
}
