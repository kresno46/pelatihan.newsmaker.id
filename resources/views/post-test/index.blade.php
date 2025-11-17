@extends('layouts.app')

@section('namePage', 'Post Test')

@section('content')
    <div class="mx-auto">
        <!-- Header Section -->
        <header
            class="w-full bg-white dark:bg-gray-800 shadow-xl rounded-lg p-4 sm:p-6 mb-8 border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Profile') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Lengkapi dan perbarui data diri Anda agar informasi selalu akurat.') }}
                    </p>
                </div>

                @if (session('status'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg">
                        {{ session('success') }}
                    </div>
                @elseif (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </header>

        {{-- Filter Button --}}
        @php
            $activeTipe = request('tipe', 'PATD'); // Default ke PATD
        @endphp

        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl border border-gray-500 dark:border-gray-700 overflow-hidden mb-6">
            <div class="flex">
                {{-- <a href="{{ route('post-test.index', ['tipe' => 'PATD']) }}"
                    class="flex-1 text-sm py-2 px-6 text-center font-semibold transition-all duration-300 {{ $activeTipe === 'PATD' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    PATD
                </a> --}}
                <a href="{{ route('post-test.index', ['tipe' => 'PATL']) }}"
                    class="flex-1 text-sm py-2 px-6 text-center font-semibold transition-all duration-300 {{ $activeTipe === 'PATL' ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    PATL
                </a>
            </div>
        </div>

        {{-- Content --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl border border-gray-300 dark:border-gray-400 overflow-hidden hidden lg:block">
            <div>
                @if ($tests->where('status', true)->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-4 border-b">#</th>
                                    <th scope="col" class="px-6 py-4 border-b">Judul</th>
                                    <th scope="col" class="px-6 py-4 border-b">Durasi</th>
                                    <th scope="col" class="px-6 py-4 border-b">Progress</th>
                                    <th scope="col" class="px-6 py-4 border-b">Status</th>
                                    <th scope="col" class="px-6 py-4 border-b text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tests->where('status', true) as $index => $posttest)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $posttest->title }}</td>
                                        <td class="px-6 py-4">{{ $posttest->duration }} menit</td>
                                        <td class="px-6 py-4">
                                            @if ($posttest->progres === 'Belum Dikerjakan')
                                                <span
                                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                                    <i class="fa-solid fa-clock"></i> Belum Dikerjakan
                                                </span>
                                            @elseif ($posttest->progres === 'Nilai di Bawah 60')
                                                <span
                                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400">
                                                    <i class="fa-solid fa-times-circle"></i> Selesai
                                                    ({{ $posttest->score }})
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                    <i class="fa-solid fa-check-circle"></i> Selesai
                                                    ({{ $posttest->score }})
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                                <i class="fa-solid fa-circle text-green-500"></i> Aktif
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($posttest->progres === 'Belum Dikerjakan')
                                                <a href="{{ route('post-test.show', $posttest->slug) }}"
                                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition-all shadow-lg hover:shadow-xl">
                                                    <i class="fa-solid fa-play"></i> Mulai
                                                </a>
                                            @else
                                                <a href="{{ route('post-test.result', $posttest->result_id) }}"
                                                    class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg transition-all shadow-lg hover:shadow-xl">
                                                    <i class="fa-solid fa-eye"></i> Lihat Hasil
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div
                            class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-clipboard-question text-gray-400 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak ada post test aktif</h3>
                        <p class="text-gray-600 dark:text-gray-400">Belum ada post test yang tersedia untuk saat ini.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden">
            @if ($tests->where('status', true)->isNotEmpty())
                @foreach ($tests->where('status', true) as $index => $posttest)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl p-6 mb-4 border border-gray-200 dark:border-gray-700 shadow-lg space-y-2">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-gray-900 dark:text-white text-lg">{{ $posttest->title }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $posttest->duration }} menit</p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-1">
                            <span
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400">
                                <i class="fa-solid fa-circle text-green-500"></i> Aktif
                            </span>
                            @if ($posttest->progres === 'Belum Dikerjakan')
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                    <i class="fa-solid fa-clock"></i> Belum Dikerjakan
                                </span>
                            @elseif ($posttest->progres === 'Nilai di Bawah 60')
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                    <i class="fa-solid fa-times-circle"></i> Selesai ({{ $posttest->score }})
                                </span>
                            @else
                                <span
                                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                    <i class="fa-solid fa-check-circle"></i> Selesai ({{ $posttest->score }})
                                </span>
                            @endif
                        </div>

                        <div class="flex justify-end">
                            @if ($posttest->progres === 'Belum Dikerjakan')
                                <a href="{{ route('post-test.show', $posttest->slug) }}"
                                    class="w-full inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition-all shadow-lg hover:shadow-xl">
                                    <i class="fa-solid fa-play"></i> Mulai
                                </a>
                            @else
                                <a href="{{ route('post-test.result', $posttest->result_id) }}"
                                    class="w-full inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white text-sm px-4 py-2 rounded-lg transition-all shadow-lg hover:shadow-xl">
                                    <i class="fa-solid fa-eye"></i> Lihat Hasil
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-12">
                    <div
                        class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-clipboard-question text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tidak ada post test aktif</h3>
                    <p class="text-gray-600 dark:text-gray-400">Belum ada post test yang tersedia untuk saat ini.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
