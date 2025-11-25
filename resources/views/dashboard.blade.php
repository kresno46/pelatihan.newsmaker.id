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
                    'color' => 'violet',
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

        @foreach ($cards as $card)
            <div
                class="bg-white dark:bg-gray-800 border-l-4 border-{{ $card['color'] }}-500 rounded-lg shadow transform transition duration-300 hover:scale-105 hover:shadow-xl cursor-pointer">
                <a href="{{ route($card['route']) }}">
                    <div class="flex items-center p-5">
                        <div
                            class="text-{{ $card['color'] }}-500 px-6 py-5 bg-{{ $card['color'] }}-100 dark:bg-{{ $card['color'] }}-900 rounded-full">
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
        <!-- Grafik Section -->
        {{-- <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Grafik Absensi Per Jadwal -->
            <div
                class="bg-white dark:bg-gray-800 border rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Absensi Per Tanggal</h3>
                    <a href="{{ route('AbsensiUser.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors duration-200">
                        Lihat Lainnya...
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="h-64">
                    <canvas id="absensiChart"></canvas>
                </div>
            </div>

            <!-- Grafik Post Test Per Tanggal -->
            <div
                class="bg-white dark:bg-gray-800 border rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow duration-300">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Post Test Per Tanggal</h3>
                    <a href="{{ route('post-test.index') }}"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors duration-200">
                        Lihat Lainnya...
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
                <div class="h-64">
                    <canvas id="postTestChart"></canvas>
                </div>
            </div>
        </div> --}}

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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $perusahaan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                            {{ $certificate->user->cabang ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                                {{ $certificate->average_score }}/100
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
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

    <script>
        // Counter animation
        document.addEventListener("DOMContentLoaded", () => {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const increment = Math.ceil(target / 50); // speed

                    if (count < target) {
                        counter.innerText = count + increment;
                        setTimeout(updateCount, 30);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
            });

            // Pastikan Chart.js tersedia sebelum membuat chart
            if (typeof Chart !== 'undefined') {
                // Konfigurasi umum untuk chart
                Chart.defaults.font.family = "'Inter', sans-serif";
                Chart.defaults.font.size = 12;
                Chart.defaults.color = '#6b7280';

                // Grafik Absensi Per Tanggal (Bar Chart)
                const absensiCtx = document.getElementById('absensiChart');
                if (absensiCtx) {
                    new Chart(absensiCtx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: @json($absensiLabels),
                            datasets: [{
                                label: 'Absensi',
                                data: @json($absensiData),
                                backgroundColor: 'rgba(39, 80, 245, 0.1)',
                                borderColor: 'rgba(39, 80, 245, 1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: 'rgba(39, 80, 245, 1)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    cornerRadius: 6,
                                    displayColors: false
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#9ca3af',
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(156, 163, 175, 0.1)',
                                        drawBorder: false
                                    },
                                    ticks: {
                                        color: '#9ca3af',
                                        font: {
                                            size: 11
                                        },
                                        stepSize: 1
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    hoverBorderWidth: 3
                                }
                            }
                        }
                    });
                }

                // Grafik Post Test Per Tanggal (Line Chart)
                const postTestCtx = document.getElementById('postTestChart');
                if (postTestCtx) {
                    new Chart(postTestCtx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: @json($postTestLabels),
                            datasets: [{
                                label: 'Post Test',
                                data: @json($postTestData),
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                borderColor: 'rgba(239, 68, 68, 1)',
                                borderWidth: 2,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleColor: '#fff',
                                    bodyColor: '#fff',
                                    cornerRadius: 6,
                                    displayColors: false
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        color: '#9ca3af',
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(156, 163, 175, 0.1)',
                                        drawBorder: false
                                    },
                                    ticks: {
                                        color: '#9ca3af',
                                        font: {
                                            size: 11
                                        },
                                        stepSize: 1
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    hoverBorderWidth: 3
                                }
                            }
                        }
                    });
                }
            } else {
                console.error('Chart.js tidak tersedia');
            }
        });
    </script>
@endsection
