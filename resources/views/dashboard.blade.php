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
                    'color' => 'emerald',
                    'route' => 'post-test.index',
                ],
                [
                    'title' => 'Jumlah e-Book',
                    'value' => $jumlahEbook,
                    'suffix' => ' eBook',
                    'icon' => 'fa-solid fa-book',
                    'color' => 'blue',
                    'route' => 'folder.index',
                ],
                [
                    'title' => 'Jumlah Daftar Absensi',
                    'value' => $jumlahJadwalAbsensi,
                    'suffix' => ' Jadwal',
                    'icon' => 'fa-solid fa-list-check',
                    'color' => 'pink',
                    'route' => 'AbsensiUser.index',
                ],
                [
                    'title' => 'Mengisi Absensi',
                    'value' => $jumlahAbsensiTerisi,
                    'suffix' => ' Terisi',
                    'icon' => 'fa-solid fa-pen-to-square',
                    'color' => 'violet',
                    'route' => 'AbsensiUser.index',
                ],
                [
                    'title' => 'Jumlah Post Test',
                    'value' => $jumlahSession,
                    'suffix' => ' Post Test',
                    'icon' => 'fa-solid fa-question',
                    'color' => 'red',
                    'route' => 'post-test.index',
                ],
                [
                    'title' => 'Kuis Dikerjakan',
                    'value' => $riwayatUserLogin,
                    'suffix' => ' Kuis',
                    'icon' => 'fa-solid fa-check',
                    'color' => 'green',
                    'route' => 'riwayat.index',
                ],
                [
                    'title' => 'Sertifikat di download',
                    'value' => $jumlahSertifikatSelesai,
                    'suffix' => ' Sertifikat',
                    'icon' => 'fa-solid fa-certificate',
                    'color' => 'orange',
                    'route' => 'sertifikat.index',
                ],
            ];
            if (Auth::check() && Auth::user()->role === 'Admin') {
                $cards[] = [
                    'title' => 'Jumlah User',
                    'value' => $jumlahUser,
                    'suffix' => ' User',
                    'icon' => 'fa-solid fa-users',
                    'color' => 'purple',
                    'route' => 'trainer.index',
                ];
                $cards[] = [
                    'title' => 'Jumlah Admin',
                    'value' => $jumlahAdmin,
                    'suffix' => ' Admin',
                    'icon' => 'fa-solid fa-user-shield',
                    'color' => 'indigo',
                    'route' => 'admin.index',
                ];
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
        });
    </script>
@endsection
