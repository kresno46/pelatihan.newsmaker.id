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
                [
                    'title' => 'Jumlah Pelatihan Tersedia',
                    'value' => $jumlahPelatihan,
                    'suffix' => ' Pelatihan',
                    'icon' => 'fa-solid fa-certificate',
                    'bg_class' => 'bg-emerald-500',
                    'text_class' => 'text-white',
                    'border_class' => 'border-emerald-500',
                    'route' => 'post-test.index',
                ],
                [
                    'title' => 'Jumlah Daftar Absensi',
                    'value' => $jumlahJadwalAbsensi,
                    'suffix' => ' Jadwal',
                    'icon' => 'fa-solid fa-list-check',
                    'bg_class' => 'bg-pink-500',
                    'text_class' => 'text-white',
                    'border_class' => 'border-pink-500',
                    'route' => 'AbsensiUser.index',
                ],
                [
                    'title' => 'Mengisi Absensi',
                    'value' => $jumlahAbsensiTerisi,
                    'suffix' => ' Terisi',
                    'icon' => 'fa-solid fa-pen-to-square',
                    'bg_class' => 'bg-violet-500',
                    'text_class' => 'text-white',
                    'border_class' => 'border-violet-500',
                    'route' => 'AbsensiUser.index',
                ],
                [
                    'title' => 'Jumlah Post Test',
                    'value' => $jumlahSession,
                    'suffix' => ' Post Test',
                    'icon' => 'fa-solid fa-question',
                    'bg_class' => 'bg-red-500',
                    'text_class' => 'text-white',
                    'border_class' => 'border-red-500',
                    'route' => 'post-test.index',
                ],
                [
                    'title' => 'Kuis Dikerjakan',
                    'value' => $riwayatUserLogin,
                    'suffix' => ' Kuis',
                    'icon' => 'fa-solid fa-check',
                    'bg_class' => 'bg-green-500',
                    'text_class' => 'text-white',
                    'border_class' => 'border-green-500',
                    'route' => 'riwayat.index',
                ],
                [
                    'title' => 'Sertifikat di download',
                    'value' => $jumlahSertifikatSelesai,
                    'suffix' => ' Sertifikat',
                    'icon' => 'fa-solid fa-certificate',
                    'bg_class' => 'bg-orange-500',
                    'text_class' => 'text-white',
                    'border_class' => 'border-orange-500',
                    'route' => 'sertifikat.index',
                ],
            ];
            if (Auth::check() && Auth::user()->role === 'Admin') {
                $cards[] = [
                    'title' => 'Jumlah User',
                    'value' => $jumlahUser,
                    'suffix' => ' User',
                    'icon' => 'fa-solid fa-users',
                    'bg_class' => 'bg-purple-500',
                    'text_class' => 'text-white',
                    'border_class' => 'border-purple-500',
                    'route' => 'trainer.index',
                ];
                $cards[] = [
                    'title' => 'Jumlah Admin',
                    'value' => $jumlahAdmin,
                    'suffix' => ' Admin',
                    'icon' => 'fa-solid fa-user-shield',
                    'bg_class' => 'bg-indigo-500',
                    'text_class' => 'text-white',
                    'border_class' => 'border-indigo-500',
                    'route' => 'admin.index',
                ];
            }
        @endphp

        @foreach ($cards as $card)
            <div
                class="bg-white dark:bg-gray-800 border-l-4 {{ $card['border_class'] }} rounded-lg shadow transform transition duration-300 hover:scale-105 hover:shadow-xl cursor-pointer">
                <a href="{{ route($card['route']) }}">
                    <div class="flex items-center p-5">
                        <div class="{{ $card['text_class'] }} px-6 py-5 {{ $card['bg_class'] }} rounded-full">
                            <i class="{{ $card['icon'] }} text-2xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $card['title'] }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                <span class="counter" data-target="{{ $card['value'] }}">0</span>
                                {{ $card['suffix'] }}
                            </p>
                            {{-- <span class="text-sm text-blue-500 hover:text-blue-700 mt-2 inline-block">Klik untuk lihat</span> --}}
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    @if (Auth::check() && Auth::user()->role === 'Admin')
        <!-- Graphs Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <!-- Absensi Graph -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Grafik Absensi</h3>
                    <a href="{{ route('absensi.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Lihat
                        Lainnya</a>
                </div>
                <div class="relative h-64">
                    <canvas id="absensiChart"></canvas>
                </div>
            </div>

            <!-- Post Test Graph -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Grafik Post Test</h3>
                    <a href="{{ route('posttest.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">Lihat
                        Lainnya</a>
                </div>
                <div class="relative h-64">
                    <canvas id="postTestChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Certificate Awards Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mt-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800 dark:text-white">Laporan Sertifikat</h3>
                <a href="{{ route('LaporanSertifikat.index') }}"
                    class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">Lihat
                    Lainnya →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[700px] text-sm text-center table-auto">
                    <thead class="bg-gray-600 text-white dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 rounded-l-lg">#</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Batch</th>
                            <th class="px-4 py-3">Nilai</th>
                            <th class="px-4 py-3 rounded-r-lg">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($certificateAwards as $index => $certificate)
                            <tr
                                class="{{ $loop->odd ? 'bg-white dark:bg-gray-800' : 'bg-gray-100 dark:bg-gray-900' }} border-b border-gray-300 dark:border-gray-700">
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-semibold">{{ $index + 1 }}
                                </td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                    {{ $certificate->user->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                    {{ $certificate->batch_number ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                    {{ $certificate->average_score }}/100</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                    {{ $certificate->awarded_at ? \Carbon\Carbon::parse($certificate->awarded_at)->translatedFormat('d F Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada pengguna yang mengunduh sertifikat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Counter animation
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const increment = Math.ceil(target / 50);

                    if (count < target) {
                        counter.innerText = count + increment;
                        setTimeout(updateCount, 30);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
            });

            @if (Auth::check() && Auth::user()->role === 'Admin')
                // ===== Fungsi Bikin Grafik Chart.js =====
                function createChart(canvasId, chartType, labels, data, label, colors) {
                    const ctx = document.getElementById(canvasId);
                    if (!ctx) return;

                    new Chart(ctx, {
                        type: chartType,
                        data: {
                            labels: labels,
                            datasets: [{
                                label: label,
                                data: data,
                                backgroundColor: colors.background,
                                borderColor: colors.border,
                                borderWidth: 2,
                                fill: chartType === 'line',
                                tension: 0.3,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: {
                                        color: '#374151' // abu gelap
                                    }
                                },
                                tooltip: {
                                    mode: 'index',
                                    intersect: false,
                                },
                            },
                        }
                    });
                }

                // ===== Data dari Controller =====
                const absensiData = @json($absensiData);
                const postTestData = @json($postTestData);

                // Jika data kosong, tampilkan minimal label agar chart tidak error
                const absensiLabels = Object.keys(absensiData).length ? Object.keys(absensiData) : [
                    'Tidak ada data'
                ];
                const absensiValues = Object.values(absensiData).length ? Object.values(absensiData) : [0];

                const postTestLabels = Object.keys(postTestData).length ? Object.keys(postTestData) : [
                    'Tidak ada data'
                ];
                const postTestValues = Object.values(postTestData).length ? Object.values(postTestData) : [0];

                // ===== Buat Chart =====
                createChart('absensiChart', 'line', absensiLabels, absensiValues, 'Absensi', {
                    background: 'rgba(16, 185, 129, 0.3)', // hijau toska transparan
                    border: 'rgb(16, 185, 129)',
                });

                createChart('postTestChart', 'bar', postTestLabels, postTestValues, 'Post Test', {
                    background: 'rgba(239, 68, 68, 0.3)', // merah muda transparan
                    border: 'rgb(239, 68, 68)',
                });
            @endif
        });
    </script>
@endsection
