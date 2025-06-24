@extends('layouts.app')

@section('namePage', 'Detail Hasil - ' . $laporan->user->name)

@section('content')
    <div class="space-y-6">
        {{-- Tombol Kembali --}}
        <a href="{{ route('laporan.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-white rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>

        {{-- Info --}}
        <div class="md:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 space-y-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $laporan->ebook->title }} - {{ $laporan->user->name }}
                </h2>
            </div>

            @php
                $jumlahSoal = $laporan->session->questions->count() ?? 0;
                $jawabanBenar = round(($laporan->score / 100) * $jumlahSoal);
            @endphp

            {{-- Detail --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-base text-gray-700 dark:text-gray-300">
                <x-laporan-info icon="fa-user" label="Peserta" :value="$laporan->user->name" />
                <x-laporan-info icon="fa-envelope" label="Email" :value="$laporan->user->email" />
                <x-laporan-info icon="fa-phone" label="No. Telepon" :value="$laporan->user->no_tlp" />
                <x-laporan-info icon="fa-layer-group" label="Nama Sesi" :value="$laporan->session->title ?? '-'" />
                <x-laporan-info icon="fa-calendar-alt" label="Tanggal Pengerjaan" :value="$laporan->created_at->format('d F Y')" />
                <x-laporan-info icon="fa-clock" label="Durasi Sesi" :value="$laporan->session->duration . ' menit'" />
            </div>

            <x-laporan-info icon="fa-check-circle" label="Jawaban Benar" :value="$jawabanBenar . ' dari ' . $jumlahSoal . ' soal'" />

            {{-- Skor & Grade --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @php
                    $colorClass =
                        $laporan->score >= 85
                            ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300'
                            : ($laporan->score <= 50
                                ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300'
                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300');
                @endphp

                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 flex items-center justify-between">
                    <div class="flex flex-col gap-3">
                        <p class="text-gray-500"><i class="fa-solid fa-star me-2"></i>Skor</p>
                        <span class="text-4xl text-center font-bold {{ $colorClass }} px-4 py-1 rounded-full">
                            {{ $laporan->score }}/100
                        </span>
                    </div>
                </div>

                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 flex items-center justify-between">
                    <div class="flex flex-col gap-3">
                        <p class="text-gray-500"><i class="fa-solid fa-certificate me-2"></i>Grade</p>
                        <span class="text-4xl text-center font-bold {{ $colorClass }} px-4 py-1 rounded-full">
                            {{ $laporan->score >= 85 ? 'A' : ($laporan->score >= 70 ? 'B' : ($laporan->score >= 50 ? 'C' : 'D')) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
