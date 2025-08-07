<?php

namespace App\Http\Controllers;

use App\Models\CertificateAward;
use Illuminate\Http\Request;

class LaporanSertifikatController extends Controller
{
    /**
     * Menampilkan daftar sertifikat yang telah diunduh.
     */
    public function index()
    {
        $sertifikats = CertificateAward::with(['user', 'folder'])
            ->orderByDesc('awarded_at')
            ->get();

        return view('LaporanSertifikat.index', compact('sertifikats'));
    }

    // Method lainnya masih kosong (bisa dihapus jika tidak dipakai)

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
