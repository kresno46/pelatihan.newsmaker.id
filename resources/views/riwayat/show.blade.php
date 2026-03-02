@extends('layouts.app')

@section('namePage', 'Riwayat Post Test')

@section('content')
    <div class="mx-auto">
        <header
            class="w-full bg-white dark:bg-gray-800 shadow-2xl rounded-lg p-4 sm:p-6 mb-4 sm:mb-8 border border-gray-100 dark:border-gray-700">
            <div class="flex gap-3 items-center">
                <a href="{{ route('riwayat.index') }}"
                    class="inline-flex items-center gap-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 px-4 py-2 lg:px-6 lg:py-3 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm lg:text-base">
                    <i class="fa-solid fa-arrow-left"></i>
                    {{-- <span class="font-medium">Kembali ke Riwayat</span> --}}
                </a>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Hasil Post Test') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Detail lengkap hasil pengerjaan Anda') }}
                    </p>
                </div>
            </div>
        </header>

        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 lg:p-8">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-8">
                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-user text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-blue-700 dark:text-blue-300 font-medium">Nama</p>
                                <p class="text-lg font-bold text-blue-900 dark:text-blue-100">
                                    {{ auth()->user()->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl p-6 border border-green-200 dark:border-green-800">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-graduation-cap text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-green-700 dark:text-green-300 font-medium">Sesi Test</p>
                                <p class="text-lg font-bold text-green-900 dark:text-green-100">{{ $session->title ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200 dark:border-purple-800">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                                <i class="fa-solid fa-calendar text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-purple-700 dark:text-purple-300 font-medium">Tanggal Pengerjaan</p>
                                <div class="flex items-center gap-1">
                                    <p class="text-lg font-bold text-purple-900 dark:text-purple-100">
                                        {{ $result->created_at->translatedFormat('d F Y') }}</p> <span>-</span>
                                    <p class="text-sm text-purple-600 dark:text-purple-400">
                                        {{ $result->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Information -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-gray-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-clock text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Informasi Test</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Durasi</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $session->duration ?? '-' }}
                                    menit</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Total Soal</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $session->questions->count() }}
                                    soal</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fa-solid fa-chart-line text-white"></i>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Hasil Test</h3>
                        </div>
                        <div class="space-y-3">
                            @php
                                $correct = round(($result->score / 100) * $session->questions->count());
                                $pass = $result->score >= 60;
                            @endphp
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Jawaban Benar</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $correct }} dari
                                    {{ $session->questions->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Nilai Akhir</span>
                                <span
                                    class="text-xl font-bold {{ $pass ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                    {{ $result->score ?? '-' }}/100
                                </span>
                            </div>
                            <div class="flex justify-center mt-4">
                                <span
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold {{ $pass ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' }}">
                                    <i class="fa-solid {{ $pass ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    {{ $pass ? 'Lulus' : 'Tidak Lulus' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
