@extends('layouts.app')

@section('namePage', 'Dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-2">Dashboard</h1>
    <p class="mb-6">Selamat datang kembali, {{ Auth::user()->name }}!</p>

    @if ($isIncomplete)
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 rounded mb-6 flex items-center animate-pulse"
            role="alert">
            <div class="p-3 bg-yellow-400">
                <strong class="font-bold">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </strong>
            </div>
            <div class="ps-3">
                <span class="block sm:inline">
                    Lengkapi data diri Anda untuk pengalaman yang lebih baik.
                    <a href="{{ route('profile.edit') }}" class="underline text-yellow-700 ml-2 hover:text-yellow-900">
                        Klik di sini
                    </a>
                </span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Example Card Template -->
        @php
            $cards = [
                // [
                //     'title' => 'Jumlah Pelatihan Tersedia',
                //     'value' => $jumlahPelatihan,
                //     'suffix' => ' Pelatihan',
                //     'icon' => 'fa-solid fa-certificate',
                //     'color' => 'emerald',
                //     'route' => 'post-test.index',
                // ],
                // [
                //     'title' => 'Jumlah Daftar Absensi',
                //     'value' => $jumlahJadwalAbsensi,
                //     'suffix' => ' Jadwal',
                //     'icon' => 'fa-solid fa-list-check',
                //     'color' => 'pink',
                //     'route' => 'AbsensiUser.index',
                // ],
                [
                    'title' => 'Absensi',
                    'value' => $jumlahAbsensiTerisi,
                    'suffix' => ' Terisi',
                    'icon' => 'fa-solid fa-pen-to-square',
                    'color' => 'emerald',
                    'route' => 'AbsensiUser.index',
                ],
                [
                    'title' => 'Post Test',
                    'value' => $jumlahSession,
                    'suffix' => ' Post Test',
                    'icon' => 'fa-solid fa-question',
                    'color' => 'red',
                    'route' => 'post-test.index',
                ],
                // [
                //     'title' => 'Kuis Dikerjakan',
                //     'value' => $riwayatUserLogin,
                //     'suffix' => ' Kuis',
                //     'icon' => 'fa-solid fa-check',
                //     'color' => 'green',
                //     'route' => 'riwayat.index',
                // ],
                [
                    'title' => 'Download Sertifikat',
                    'value' => $jumlahSertifikatSelesai,
                    'suffix' => ' Sertifikat',
                    'icon' => 'fa-solid fa-certificate',
                    'color' => 'orange',
                    'route' => 'sertifikat.index',
                ],
            ];
            if (Auth::check() && Auth::user()->role === 'Admin') {
                // $cards[] = [
                //     'title' => 'Jumlah User',
                //     'value' => $jumlahUser,
                //     'suffix' => ' User',
                //     'icon' => 'fa-solid fa-users',
                //     'color' => 'purple',
                //     'route' => 'trainer.index',
                // ];
                // $cards[] = [
                //     'title' => 'Jumlah Admin',
                //     'value' => $jumlahAdmin,
                //     'suffix' => ' Admin',
                //     'icon' => 'fa-solid fa-user-shield',
                //     'color' => 'indigo',
                //     'route' => 'admin.index',
                // ];
            }
        @endphp

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Beranda</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-300">
                Halo, {{ Auth::user()->name }}. Pilih menu di bawah untuk mulai.
            </p>
        </div>

        @if ($isIncomplete)
            <div class="mb-6 rounded-xl border border-yellow-300 bg-yellow-50 p-4 text-yellow-800 dark:border-yellow-700/50 dark:bg-yellow-900/20 dark:text-yellow-200"
                role="alert">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-3">
                        <div
                            class="mt-0.5 flex h-10 w-10 items-center justify-center rounded-lg bg-yellow-200 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <div class="font-semibold">Lengkapi data diri dulu ya</div>
                            <div class="text-sm opacity-90">Supaya absensi, post test, dan sertifikat berjalan lancar.</div>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-600 px-4 py-2 text-sm font-semibold text-white hover:bg-yellow-700">
                        <i class="fa-solid fa-user-pen"></i>
                        <span>Lengkapi Profil</span>
                    </a>
                </div>
            </div>
        @endif

        <div class="mb-8 rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Mulai dari sini</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Pilih salah satu tombol, nanti kamu diarahkan.</p>
                </div>
            </div>

            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($quickActions as $action)
                    <a href="{{ route($action['route']) }}"
                        class="group flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:bg-gray-50 hover:shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-900/30">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-600 dark:bg-{{ $action['color'] }}-900/30 dark:text-{{ $action['color'] }}-300">
                            <i class="{{ $action['icon'] }} text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="font-semibold text-gray-900 dark:text-white">{{ $action['title'] }}</div>
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $action['desc'] }}</div>
                        </div>
                        <div class="ml-auto text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-200">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Tools</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Akses cepat ke tools yang kamu butuhkan.</p>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-3 sm:grid-cols-2">
            @foreach ($tools as $tool)
                <a href="{{ $tool['href'] }}"
                    class="group flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-4 transition hover:bg-gray-50 hover:shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-900/30">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-{{ $tool['color'] }}-100 text-{{ $tool['color'] }}-700 dark:bg-{{ $tool['color'] }}-900/30 dark:text-{{ $tool['color'] }}-200">
                        <i class="{{ $tool['icon'] }} text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-900 dark:text-white">{{ $tool['title'] }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $tool['desc'] }}</div>
                    </div>
                    <div class="ml-auto text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-200">
                        <i class="fa-solid fa-chevron-right"></i>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan</h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Informasi singkat untuk kamu.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            @foreach ($summaryCards as $card)
                <a href="{{ route($card['route']) }}"
                    class="group rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:shadow-md dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-600 dark:bg-{{ $card['color'] }}-900/30 dark:text-{{ $card['color'] }}-300">
                            <i class="{{ $card['icon'] }} text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-gray-600 dark:text-gray-300">{{ $card['title'] }}</div>
                            <div class="mt-1 flex items-baseline gap-2">
                                <div class="text-3xl font-semibold text-gray-900 dark:text-white">
                                    {{ number_format((int) $card['value']) }}
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $card['suffix'] }}</div>
                            </div>
                        </div>
                        <div class="ml-auto text-gray-300 group-hover:text-gray-500 dark:group-hover:text-gray-200">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        @if ($isAdmin)
            <!-- Sertifikat Terbaru Section -->
            <div class="mt-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                                <i class="fas fa-certificate text-blue-500 mr-2"></i>
                                Sertifikat Terbaru
                            </h3>
                            <a href="{{ route('LaporanSertifikat.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 rounded-lg transition-colors duration-200">
                                Lihat Semua
                                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    @if ($latestCertificates->isEmpty())
                        <div class="text-center py-12 text-gray-600 dark:text-gray-300">
                            <i class="fas fa-certificate text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p>Belum ada sertifikat yang diterbitkan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900/40">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Peserta
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Perusahaan
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Cabang
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Nilai
                                        </th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Tanggal Sertifikat
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($latestCertificates as $certificate)
                                        @php
                                            switch ($certificate->user->role ?? '') {
                                                case 'Trainer (SGB)':
                                                    $perusahaan = 'PT Solid Gold Berjangka';
                                                    break;
                                                case 'Trainer (RFB)':
                                                    $perusahaan = 'PT Rifan Financindo Berjangka';
                                                    break;
                                                case 'Trainer (EWF)':
                                                    $perusahaan = 'PT Equity World Futures';
                                                    break;
                                                case 'Trainer (BPF)':
                                                    $perusahaan = 'PT Best Profit Futures';
                                                    break;
                                                case 'Trainer (KPF)':
                                                    $perusahaan = 'PT Kontak Perkasa Futures';
                                                    break;
                                                default:
                                                    $perusahaan = '-';
                                                    break;
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center mr-3">
                                                        <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                                                    </div>
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $certificate->user->name ?? '-' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $perusahaan }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ $certificate->user->cabang ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                    {{ $certificate->average_score }}/100
                                                </span>
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                                {{ optional($certificate->awarded_at)->format('d F Y - H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endsection
