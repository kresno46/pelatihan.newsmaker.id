<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\CertificateAward;
use App\Models\Ebook;
use App\Models\FolderEbook;
use App\Models\JadwalAbsensi;
use App\Models\PostTestSession;
use App\Models\PostTestResult;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $requiredFields = [
            'name',
            'email',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            // 'warga_negara',
            'alamat',
            'no_tlp',
            // 'pekerjaan',
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

        // Statistik
        $jumlahEbook                = Ebook::count();
        $jumlahSession              = PostTestSession::count();
        $riwayatUserLogin           = PostTestResult::where('user_id', auth()->id())->count();
        $jumlahUser                 = User::where('role', 'Trainer (Eksternal)')->count();
        $jumlahAdmin                = User::where('role', 'Admin')->count();
        $jumlahSertifikatSelesai    = CertificateAward::where('user_id', auth()->id())->count();
        $jumlahPelatihan            = FolderEbook::count();
        $jumlahAbsensiTerisi        = Absensi::where('user_id', auth()->id())->count();
        $jumlahJadwalAbsensi        = JadwalAbsensi::count();

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
            'jumlahJadwalAbsensi'
        ));
    }
}
