@extends('layouts.app')

@section('namePage', 'Detail Hasil ' . $laporan->user->name)

@section('content')
    <div class="space-y-6">
        {{-- Tombol kembali --}}
        <a href="{{ route('laporan.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>

        <div class="flex flex-col md:flex-row gap-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
            {{-- Cover eBook --}}
            <div class="w-full md:w-[300px] aspect-[3/4.5] rounded overflow-hidden shadow mx-auto md:mx-0">
                <img src="{{ asset($laporan->ebook->cover) }}" alt="Cover {{ $laporan->ebook->title }}"
                    class="w-full h-full object-cover">
            </div>

            {{-- Informasi Laporan --}}
            <div class="flex-1 space-y-4">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Hasil Post-Test: {{ $laporan->ebook->title }}
                </h1>

                <div class="space-y-4 text-gray-800 dark:text-gray-200">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Tanggal Pengerjaan</p>
                        <p class="text-lg">{{ $laporan->created_at->format('d F Y') }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">eBook</p>
                        <p class="text-lg">{{ $laporan->user->name }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Post-Test Session</p>
                        <p class="text-lg">{{ $laporan->session->title ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Durasi Pengerjaan</p>
                        <p class="text-lg">{{ $laporan->session->duration ?? '-' }} menit</p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Skor</p>
                        <p class="text-lg font-bold text-green-600 dark:text-green-400">{{ $laporan->score }}/100</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
