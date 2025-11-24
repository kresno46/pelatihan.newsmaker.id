<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\CertificateAward;
use App\Models\Ebook;
use App\Models\FolderEbook;
use App\Models\JadwalAbsensi;
use App\Models\PostTestResult;
use App\Models\PostTestSession;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Cek kelengkapan profil
        $requiredFields = [
            'name',
            'email',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'alamat',
            'no_tlp',
        ];

        $isIncomplete = false;
        if ($user->role !== 'Admin') {
            foreach ($requiredFields as $field) {
                if (empty($user->$field)) {
                    $isIncomplete = true;
                    break;
                }
            }
        }

        // ========================
        // Statistik dasar
        // ========================
        $jumlahEbook = Ebook::count();
        $jumlahSession = PostTestSession::count();
        $jumlahPelatihan = FolderEbook::count();
        $jumlahJadwalAbsensi = JadwalAbsensi::count();
        $jumlahUser = User::where('role', 'Trainer (Eksternal)')->count();
        $jumlahAdmin = User::where('role', 'Admin')->count();

        // Statistik berdasarkan user login
        $riwayatUserLogin = PostTestResult::where('user_id', $user->id)->count();
        $jumlahSertifikatSelesai = CertificateAward::where('user_id', $user->id)->count();
        $jumlahAbsensiTerisi = Absensi::where('user_id', $user->id)->count();

        // ========================
        // Data untuk grafik
        // ========================
        // Grafik absensi per jadwal
        $absensiPerJadwal = Absensi::selectRaw('jadwal_absensis.tanggal, COUNT(absensis.id) as jumlah')
            ->join('jadwal_absensis', 'absensis.jadwal_id', '=', 'jadwal_absensis.id')
            ->groupBy('jadwal_absensis.tanggal')
            ->orderBy('jadwal_absensis.tanggal', 'asc')
            ->limit(5)
            ->get();

        $absensiLabels = $absensiPerJadwal->pluck('tanggal');
        $absensiData = $absensiPerJadwal->pluck('jumlah');

        // Grafik post test per tanggal
        $postTestPerTanggal = PostTestResult::selectRaw('DATE(created_at) as tanggal, COUNT(id) as jumlah')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->limit(5)
            ->get();

        $postTestLabels = $postTestPerTanggal->pluck('tanggal');
        $postTestData = $postTestPerTanggal->pluck('jumlah');

        // ========================
        // Data sertifikat terbaru
        // ========================
        $latestCertificates = CertificateAward::with(['user', 'folder'])
            ->orderBy('awarded_at', 'desc')
            ->limit(15)
            ->get();

        // ========================
        // Kirim data ke view
        // ========================
        return view('dashboard', compact(
            'isIncomplete',
            'jumlahEbook',
            'jumlahSession',
            'riwayatUserLogin',
            'jumlahUser',
            'jumlahAdmin',
            'jumlahSertifikatSelesai',
            'jumlahPelatihan',
            'jumlahAbsensiTerisi',
            'jumlahJadwalAbsensi',
            'absensiLabels',
            'absensiData',
            'postTestLabels',
            'postTestData',
            'latestCertificates'
        ));
    }
}
