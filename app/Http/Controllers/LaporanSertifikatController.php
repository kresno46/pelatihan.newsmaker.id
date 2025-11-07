<?php

namespace App\Http\Controllers;

use App\Exports\SertifikatExport;
use App\Exports\SertifikatPerCabangExport;
use App\Models\CertificateAward;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanSertifikatController extends Controller
{
    /**
     * Menampilkan daftar sertifikat yang telah diunduh.
     */
    public function index(Request $request)
    {
        $query = CertificateAward::with(['user', 'folder']);

        // Filter berdasarkan pencarian nama
        if ($request->filled('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%');
            });
        }

        // Filter berdasarkan perusahaan
        if ($request->filled('company')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->company);
            });
        }

        // Filter berdasarkan cabang
        if ($request->filled('cabang')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('cabang', $request->cabang);
            });
        }

        // Sorting
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
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc');
                break;
            case 'name_desc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc');
                break;
            case 'company_asc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.role', 'asc');
                break;
            case 'company_desc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.role', 'desc');
                break;
            case 'cabang_asc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.cabang', 'asc');
                break;
            case 'cabang_desc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.cabang', 'desc');
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

        // Pagination
        $perPage = $request->get('per_page', 20);
        $sertifikats = $query->paginate($perPage);

        // Aggregates untuk ringkasan
        $aggregates = CertificateAward::selectRaw('
            COUNT(*) as total,
            AVG(average_score) as avg_score,
            MAX(average_score) as max_score,
            MIN(average_score) as min_score
        ')->first();

        return view('LaporanSertifikat.index', compact('sertifikats', 'aggregates'));
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
        $filePath = storage_path('app/public/sertifikat/'.$sertifikat->certificate_uuid.'.pdf');

        // Hapus file jika ada
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Hapus data dari database
        $sertifikat->delete();

        return redirect()->back()->with('Alert', 'Sertifikat berhasil dihapus.');
    }

    /**
     * Export sertifikat ke Excel
     */
    public function export(Request $request)
    {
        $query = CertificateAward::with(['user', 'folder']);

        // Filter berdasarkan pencarian nama
        if ($request->filled('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%');
            });
        }

        // Filter berdasarkan perusahaan
        if ($request->filled('company')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('role', $request->company);
            });
        }

        // Filter berdasarkan cabang
        if ($request->filled('cabang')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('cabang', $request->cabang);
            });
        }

        // Sorting
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
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc');
                break;
            case 'name_desc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc');
                break;
            case 'company_asc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.role', 'asc');
                break;
            case 'company_desc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.role', 'desc');
                break;
            case 'cabang_asc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.cabang', 'asc');
                break;
            case 'cabang_desc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.cabang', 'desc');
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

        $filename = 'laporan_sertifikat_'.now()->format('Y-m-d_H-i-s').'.xlsx';

        return Excel::download(new SertifikatExport($query), $filename);
    }

    /**
     * Export sertifikat per cabang ke Excel
     */
    public function exportPerCabang(Request $request)
    {
        $cabang = $request->get('cabang');
        if (! $cabang) {
            return redirect()->back()->with('error', 'Cabang harus dipilih untuk export per cabang.');
        }

        $query = CertificateAward::with(['user', 'folder'])
            ->whereHas('user', function ($q) use ($cabang) {
                $q->where('cabang', $cabang);
            });

        // Filter berdasarkan pencarian nama
        if ($request->filled('q')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%');
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
                $query->orderBy('awarded_at', 'asc');
                break;
            case 'highest':
                $query->orderBy('average_score', 'desc');
                break;
            case 'lowest':
                $query->orderBy('average_score', 'asc');
                break;
            case 'name_asc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'asc');
                break;
            case 'name_desc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.name', 'desc');
                break;
            case 'company_asc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.role', 'asc');
                break;
            case 'company_desc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.role', 'desc');
                break;
            case 'cabang_asc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.cabang', 'asc');
                break;
            case 'cabang_desc':
                $query->join('users', 'certificate_awards.user_id', '=', 'users.id')
                    ->orderBy('users.cabang', 'desc');
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

        $filename = 'laporan_sertifikat_'.str_replace(' ', '_', $cabang).'_'.now()->format('Y-m-d_H-i-s').'.xlsx';

        return Excel::download(new SertifikatPerCabangExport($query, $cabang), $filename);
    }
}
