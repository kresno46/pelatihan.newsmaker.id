@extends('layouts.app')

@section('namePage', 'Riwayat Post Test')

@section('content')
    <div class="mx-auto">
        <!-- Header Section -->
        <header
            class="w-full bg-white dark:bg-gray-800 shadow-2xl rounded-lg p-4 sm:p-6 mb-4 sm:mb-8 border border-gray-100 dark:border-gray-700">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Riwayat Post Test') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Pantau perkembangan belajar Anda') }}
                    </p>
                </div>
            </div>
        </header>

        @if ($results->isEmpty())
            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                <div class="max-w-md mx-auto">
                    <div>
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fa-solid fa-book-open text-4xl text-blue-500 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Belum Ada Riwayat</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Anda belum menyelesaikan post test apapun. Mulai belajar dan ikuti post test untuk melihat
                            riwayat di sini.
                        </p>
                    </div>

                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-500 to-purple-500 text-white px-6 py-3 rounded-xl font-medium hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <i class="fa-solid fa-play"></i>
                        Mulai Belajar
                    </a>
                </div>
            </div>
        @else
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 mb-4 sm:mb-8">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 sm:p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center">
                        <div
                            class="w-16 h-16 bg-blue-100 dark:bg-blue-900/20 rounded-xl flex items-center justify-center mr-6">
                            <i class="fa-solid fa-book text-blue-600 dark:text-blue-400 text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-base text-gray-600 dark:text-gray-400 font-medium">Total Test</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">{{ $results->count() }}
                                Post Test
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 sm:p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center">
                        <div
                            class="w-16 h-16 bg-green-100 dark:bg-green-900/20 rounded-xl flex items-center justify-center mr-6">
                            <i class="fa-solid fa-trophy text-green-600 dark:text-green-400 text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-base text-gray-600 dark:text-gray-400 font-medium">Rata-rata Skor</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                                {{ round($results->avg('score')) }}/100</p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 sm:p-8 border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center">
                        <div
                            class="w-16 h-16 bg-purple-100 dark:bg-purple-900/20 rounded-xl flex items-center justify-center mr-6">
                            <i class="fa-solid fa-calendar text-purple-600 dark:text-purple-400 text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-base text-gray-600 dark:text-gray-400 font-medium">Test Terakhir</p>
                            <p class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $results->first()->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th
                                    class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    <i class="fa-solid fa-graduation-cap mr-2"></i>Sesi Test
                                </th>
                                <th
                                    class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    <i class="fa-solid fa-clock mr-2"></i>Durasi
                                </th>
                                <th
                                    class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    <i class="fa-solid fa-chart-line mr-2"></i>Skor
                                </th>
                                <th
                                    class="px-8 py-4 text-left text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    <i class="fa-solid fa-calendar mr-2"></i>Tanggal
                                </th>
                                <th
                                    class="px-8 py-4 text-center text-sm font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($results as $result)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-colors duration-200">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center">
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl flex items-center justify-center mr-4">
                                                <i
                                                    class="fa-solid fa-file-alt text-blue-600 dark:text-blue-400 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $result->session->title ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-sm text-gray-700 dark:text-gray-300">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            <i class="fa-solid fa-clock mr-2"></i>
                                            {{ $result->session->duration ?? '-' }} menit
                                        </span>
                                    </td>
                                    <td class="px-8 py-6">
                                        @php
                                            $score = $result->score;
                                            $scoreClass =
                                                $score >= 80
                                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400'
                                                    : ($score >= 60
                                                        ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400'
                                                        : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400');
                                            $scoreIcon =
                                                $score >= 80
                                                    ? 'fa-trophy'
                                                    : ($score >= 60
                                                        ? 'fa-star'
                                                        : 'fa-exclamation-triangle');
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $scoreClass }}">
                                            <i class="fa-solid {{ $scoreIcon }} mr-2"></i>
                                            {{ $score }}/100
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-sm text-gray-700 dark:text-gray-300">
                                        <div class="flex flex-col">
                                            <span class="font-semibold">{{ $result->created_at->format('d F Y') }}</span>
                                            <span
                                                class="text-sm text-gray-500 dark:text-gray-400">{{ $result->created_at->format('H:i') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <a href="{{ route('riwayat.show', $result->id) }}"
                                            class="w-full inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors duration-200 border border-blue-200 dark:border-blue-800">
                                            <i class="fa-solid fa-eye"></i>
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden space-y-4 p-6">
                    <div>
                        <h3 class="text-xl">Daftar Riwayat</h3>
                    </div>
                    @foreach ($results as $result)
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center flex-1">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20 rounded-xl flex items-center justify-center mr-4">
                                        <i class="fa-solid fa-file-alt text-blue-600 dark:text-blue-400 text-lg"></i>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                            {{ $result->session->title ?? '-' }}
                                        </h4>
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <i class="fa-solid fa-calendar"></i>
                                            <span>{{ $result->created_at->format('d M Y') }}</span>
                                            <span class="text-gray-400">•</span>
                                            <span>{{ $result->created_at->format('H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                    <div class="flex items-center gap-2">
                                        <i class="fa-solid fa-clock text-gray-500 dark:text-gray-400"></i>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Durasi</p>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                {{ $result->session->duration ?? '-' }} menit
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                    @php
                                        $score = $result->score;
                                        $scoreClass =
                                            $score >= 80
                                                ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400'
                                                : ($score >= 60
                                                    ? 'text-yellow-800 dark:text-yellow-400'
                                                    : 'text-red-800 dark:text-red-400');
                                        $scoreIcon =
                                            $score >= 80
                                                ? 'fa-trophy'
                                                : ($score >= 60
                                                    ? 'fa-star'
                                                    : 'fa-exclamation-triangle');
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <i
                                            class="fa-solid {{ $scoreIcon }} {{ $score >= 80 ? 'text-green-500' : ($score >= 60 ? 'text-yellow-500' : 'text-red-500') }}"></i>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Skor</p>
                                            <p class="text-sm font-bold {{ $scoreClass }}">
                                                {{ $score }}/100
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <a href="{{ route('riwayat.show', $result->id) }}"
                                    class="w-full inline-flex items-center gap-2 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors duration-200 border border-blue-200 dark:border-blue-800">
                                    <i class="fa-solid fa-eye"></i>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection
