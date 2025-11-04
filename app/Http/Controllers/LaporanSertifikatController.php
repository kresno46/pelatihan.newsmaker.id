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
        $q = $request->input('q');
        $company = $request->input('company');
        $branch = $request->input('branch');
        $sort = $request->input('sort', 'latest');
        $perPage = (int) $request->input('per_page', 15);

        // Ambil sertifikat dengan pagination dan filter
        $sertifikats = CertificateAward::with(['user'])
            ->when($q, function ($query, $q) {
                return $query->whereHas('user', function ($qUser) use ($q) {
                    $qUser->where('name', 'like', '%' . $q . '%')
                        ->orWhere('email', 'like', '%' . $q . '%');
                });
            })
            ->when($company, function ($query, $company) {
                return $query->whereHas('user', function ($qUser) use ($company) {
                    $qUser->where('nama_perusahaan', $company);
                });
            })
            ->when($branch, function ($query, $branch) {
                return $query->whereHas('user', function ($qUser) use ($branch) {
                    $qUser->where('cabang', $branch);
                });
            })
            ->when($sort === 'latest', function ($query) {
                return $query->orderByDesc('awarded_at');
            })
            ->when($sort === 'oldest', function ($query) {
                return $query->orderBy('awarded_at');
            })
            ->when($sort === 'name_asc', function ($query) {
                return $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.name')
                    ->select('certificate_awards.*');
            })
            ->when($sort === 'name_desc', function ($query) {
                return $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderByDesc('users.name')
                    ->select('certificate_awards.*');
            })
            ->paginate($perPage);

        return view('LaporanSertifikat.index', compact('sertifikats'));
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
