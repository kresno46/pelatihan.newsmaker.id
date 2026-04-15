<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\ApupptCertificateAward;
use App\Models\ApupptJadwalAbsensi;
use App\Models\ApupptPostTestSession;
use App\Models\CertificateAward;
use App\Models\Ebook;
use App\Models\FolderEbook;
use App\Models\JadwalAbsensi;
use App\Models\LoginActivity;
use App\Models\PostTestResult;
use App\Models\PostTestSession;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'Admin';
        $isApupptAdmin = $user->role === 'Admin APUPPT';

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
        if (! $isAdmin) {
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
        $jumlahAdmin = User::whereIn('role', ['Admin', 'Admin APUPPT'])->count();

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

        $adminHealthStats = [
            'suspended' => 0,
            'force_reset' => 0,
            'unverified' => 0,
            'active_7d' => 0,
        ];
        $loginTrendLabels = [];
        $loginTrendData = [];
        $deviceLabels = [];
        $deviceData = [];
        $topBrowsers = collect();
        $topPlatforms = collect();

        if ($isAdmin) {
            $adminHealthStats = [
                'suspended' => User::whereNotNull('suspended_at')->count(),
                'force_reset' => User::where('force_password_reset', true)->count(),
                'unverified' => User::whereNull('email_verified_at')->count(),
                'active_7d' => LoginActivity::where('created_at', '>=', now()->subDays(7))->distinct('user_id')->count('user_id'),
            ];

            $dateMap = collect(range(0, 6))->mapWithKeys(function ($i) {
                $date = now()->subDays(6 - $i)->toDateString();

                return [$date => 0];
            });

            $loginTrendRaw = LoginActivity::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->pluck('total', 'tanggal');

            $mergedTrend = $dateMap->merge($loginTrendRaw);
            $loginTrendLabels = $mergedTrend->keys()->map(fn ($date) => Carbon::parse($date)->format('d M'))->values()->all();
            $loginTrendData = $mergedTrend->values()->map(fn ($v) => (int) $v)->all();

            $deviceDist = LoginActivity::selectRaw('device_type, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(30)->startOfDay())
                ->groupBy('device_type')
                ->orderByDesc('total')
                ->get();

            $deviceLabels = $deviceDist->pluck('device_type')->map(fn ($x) => $x ?: 'Unknown')->values()->all();
            $deviceData = $deviceDist->pluck('total')->map(fn ($x) => (int) $x)->values()->all();

            $topBrowsers = LoginActivity::selectRaw('browser, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(30)->startOfDay())
                ->groupBy('browser')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            $topPlatforms = LoginActivity::selectRaw('platform, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(30)->startOfDay())
                ->groupBy('platform')
                ->orderByDesc('total')
                ->limit(5)
                ->get();
        }

        // ========================
        // Quick actions & tools
        // ========================
        $quickActions = [
            [
                'title' => 'Absensi',
                'desc' => 'Isi absensi sesi terbaru.',
                'icon' => 'fa-solid fa-pen-to-square',
                'color' => 'emerald',
                'route' => 'AbsensiUser.index',
            ],
            [
                'title' => 'Post Test',
                'desc' => 'Kerjakan post test yang tersedia.',
                'icon' => 'fa-solid fa-clipboard-question',
                'color' => 'blue',
                'route' => 'post-test.index',
            ],
            [
                'title' => 'Ebook',
                'desc' => 'Baca materi pelatihan.',
                'icon' => 'fa-solid fa-book',
                'color' => 'amber',
                'route' => 'edukasi.ebook',
            ],
            [
                'title' => 'Outlook',
                'desc' => 'Lihat insight terbaru.',
                'icon' => 'fa-solid fa-newspaper',
                'color' => 'purple',
                'route' => 'edukasi.outlook',
            ],
            [
                'title' => 'Sertifikat',
                'desc' => 'Unduh sertifikat yang tersedia.',
                'icon' => 'fa-solid fa-certificate',
                'color' => 'orange',
                'route' => 'sertifikat.index',
            ],
        ];

        if ($isAdmin) {
            $quickActions = [
                [
                    'title' => 'Absensi',
                    'desc' => 'Kelola jadwal absensi.',
                    'icon' => 'fa-solid fa-face-smile',
                    'color' => 'emerald',
                    'route' => 'absensi.index',
                ],
                [
                    'title' => 'Post Test',
                    'desc' => 'Kelola sesi post test.',
                    'icon' => 'fa-solid fa-clipboard-question',
                    'color' => 'blue',
                    'route' => 'posttest.index',
                ],
                [
                    'title' => 'Sertifikat',
                    'desc' => 'Laporan sertifikat terbaru.',
                    'icon' => 'fa-solid fa-certificate',
                    'color' => 'orange',
                    'route' => 'LaporanSertifikat.index',
                ],
                [
                    'title' => 'User',
                    'desc' => 'Kelola user pelatihan.',
                    'icon' => 'fa-solid fa-user',
                    'color' => 'indigo',
                    'route' => 'trainer.index',
                ],
                [
                    'title' => 'Admin',
                    'desc' => 'Kelola akun admin.',
                    'icon' => 'fa-solid fa-user-shield',
                    'color' => 'slate',
                    'route' => 'admin.index',
                ],
            ];
        }

        if ($isApupptAdmin) {
            $quickActions = [
                [
                    'title' => 'APUPPT Ebook',
                    'desc' => 'Kelola ebook APUPPT.',
                    'icon' => 'fa-solid fa-book',
                    'color' => 'amber',
                    'route' => 'apuppt.ebookfolder.index',
                ],
                [
                    'title' => 'APUPPT Post Test',
                    'desc' => 'Kelola sesi post test APUPPT.',
                    'icon' => 'fa-solid fa-clipboard-question',
                    'color' => 'blue',
                    'route' => 'apuppt.posttest.index',
                ],
                [
                    'title' => 'APUPPT Absensi',
                    'desc' => 'Kelola absensi APUPPT.',
                    'icon' => 'fa-solid fa-face-smile',
                    'color' => 'emerald',
                    'route' => 'apuppt.absensi.index',
                ],
                [
                    'title' => 'APUPPT Sertifikat',
                    'desc' => 'Laporan sertifikat APUPPT.',
                    'icon' => 'fa-solid fa-certificate',
                    'color' => 'orange',
                    'route' => 'apuppt.sertifikat.index',
                ],
            ];
        }

        $tools = [
            [
                'title' => 'AiSG',
                'desc' => 'Akses tool AiSG.',
                'icon' => 'fa-solid fa-window-maximize',
                'color' => 'blue',
                'href' => route('webview.show', ['tool' => 'aisg']),
            ],
            [
                'title' => 'NMAi 23',
                'desc' => 'Akses tool NMAi 23.',
                'icon' => 'fa-solid fa-window-maximize',
                'color' => 'indigo',
                'href' => route('webview.show', ['tool' => 'nmai23']),
            ],
            [
                'title' => 'BIAS23',
                'desc' => 'Akses tool BIAS23.',
                'icon' => 'fa-solid fa-window-maximize',
                'color' => 'amber',
                'href' => route('webview.show', ['tool' => 'bias23']),
            ],
            [
                'title' => 'Risk Guard',
                'desc' => 'Akses tool Risk Guard.',
                'icon' => 'fa-solid fa-window-maximize',
                'color' => 'emerald',
                'href' => route('webview.show', ['tool' => 'risk-guard']),
            ],
        ];

        // ========================
        // Summary cards
        // ========================
        $summaryCards = [
            [
                'title' => 'Total Ebook',
                'value' => $jumlahEbook,
                'suffix' => ' Ebook',
                'icon' => 'fa-solid fa-book',
                'color' => 'blue',
                'route' => 'edukasi.ebook',
            ],
            [
                'title' => 'Sesi Post Test',
                'value' => $jumlahSession,
                'suffix' => ' Sesi',
                'icon' => 'fa-solid fa-clipboard-question',
                'color' => 'purple',
                'route' => 'post-test.index',
            ],
            [
                'title' => 'Absensi Terisi',
                'value' => $jumlahAbsensiTerisi,
                'suffix' => ' Absensi',
                'icon' => 'fa-solid fa-pen-to-square',
                'color' => 'emerald',
                'route' => 'AbsensiUser.index',
            ],
            [
                'title' => 'Sertifikat Selesai',
                'value' => $jumlahSertifikatSelesai,
                'suffix' => ' Sertifikat',
                'icon' => 'fa-solid fa-certificate',
                'color' => 'orange',
                'route' => 'sertifikat.index',
            ],
        ];

        if ($isAdmin) {
            $summaryCards = array_merge($summaryCards, [
                [
                    'title' => 'Jumlah User',
                    'value' => $jumlahUser,
                    'suffix' => ' User',
                    'icon' => 'fa-solid fa-users',
                    'color' => 'indigo',
                    'route' => 'trainer.index',
                ],
                [
                    'title' => 'Jumlah Admin',
                    'value' => $jumlahAdmin,
                    'suffix' => ' Admin',
                    'icon' => 'fa-solid fa-user-shield',
                    'color' => 'slate',
                    'route' => 'admin.index',
                ],
                [
                    'title' => 'Jadwal Absensi',
                    'value' => $jumlahJadwalAbsensi,
                    'suffix' => ' Jadwal',
                    'icon' => 'fa-solid fa-list-check',
                    'color' => 'teal',
                    'route' => 'absensi.index',
                ],
            ]);
        }

        if ($isApupptAdmin) {
            $summaryCards = [
                [
                    'title' => 'Sesi APUPPT',
                    'value' => ApupptPostTestSession::where('tipe', 'APUPPT')->count(),
                    'suffix' => ' Sesi',
                    'icon' => 'fa-solid fa-clipboard-question',
                    'color' => 'blue',
                    'route' => 'apuppt.posttest.index',
                ],
                [
                    'title' => 'Jadwal APUPPT',
                    'value' => ApupptJadwalAbsensi::count(),
                    'suffix' => ' Jadwal',
                    'icon' => 'fa-solid fa-list-check',
                    'color' => 'teal',
                    'route' => 'apuppt.absensi.index',
                ],
                [
                    'title' => 'Sertifikat APUPPT',
                    'value' => ApupptCertificateAward::count(),
                    'suffix' => ' Sertifikat',
                    'icon' => 'fa-solid fa-certificate',
                    'color' => 'orange',
                    'route' => 'apuppt.sertifikat.index',
                ],
            ];
        }

        // ========================
        // Kirim data ke view
        // ========================
        return view('dashboard', compact(
            'isAdmin',
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
            'latestCertificates',
            'adminHealthStats',
            'loginTrendLabels',
            'loginTrendData',
            'deviceLabels',
            'deviceData',
            'topBrowsers',
            'topPlatforms',
            'quickActions',
            'tools',
            'summaryCards'
        ));
    }
}
