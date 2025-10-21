@extends('layouts.app')

@section('namePage', $user->name . ' - Hasil Post Test')

@section('content')
    <div
        class="max-w-4xl mx-auto p-8 bg-white dark:bg-gray-900 rounded-2xl shadow-lg mt-10 border border-gray-200 dark:border-gray-700">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-bold text-gray-800 dark:text-white">📊 Hasil Post Test</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-2 text-lg">Detail lengkap hasil pengerjaan Anda</p>
            @if ($result->score < 60)
                <p class="text-red-600 font-semibold">Nilai Anda kurang dari 60. Silakan Remedial PATD Gelombang Berikutnya😭.</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm md:text-base">
            <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-lg shadow-sm">
                <p class="text-gray-500 dark:text-gray-400">👤 <span class="font-semibold">Nama</span></p>
                <p class="text-gray-800 dark:text-white text-lg">{{ $user->name ?? '-' }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-lg shadow-sm">
                <p class="text-gray-500 dark:text-gray-400">📚 <span class="font-semibold">Ebook</span></p>
                <p class="text-gray-800 dark:text-white text-lg">{{ $session->ebook->title ?? '-' }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-lg shadow-sm">
                <p class="text-gray-500 dark:text-gray-400">📝 <span class="font-semibold">Sesi Post Test</span></p>
                <p class="text-gray-800 dark:text-white text-lg">{{ $session->title ?? '-' }}</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-lg shadow-sm">
                <p class="text-gray-500 dark:text-gray-400">⏱️ <span class="font-semibold">Durasi</span></p>
                <p class="text-gray-800 dark:text-white text-lg">{{ $session->duration ?? '-' }} menit</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-lg shadow-sm">
                @php
                    $correct = round(($result->score / 100) * $session->questions->count());
                    $pass = $result->score >= 60;
                @endphp
                <p class="text-gray-500 dark:text-gray-400">✅ <span class="font-semibold">Jawaban Benar</span></p>
                <p class="text-gray-800 dark:text-white text-lg">{{ $correct }} dari {{ $session->questions->count() }}
                    soal</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-lg shadow-sm">
                <p class="text-gray-500 dark:text-gray-400">🗓️ <span class="font-semibold">Tanggal Pengerjaan</span></p>
                <p class="text-gray-800 dark:text-white text-lg">{{ $result->created_at->translatedFormat('d F Y - H:i') }}
                </p>
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-800 p-5 rounded-lg shadow-sm mt-6">
            <p class="text-gray-500 dark:text-gray-400">📈 <span class="font-semibold">Nilai Akhir</span></p>
            <p
                class="text-2xl font-extrabold {{ $pass ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                {{ $result->score ?? '-' }}/100
            </p>
            <p class="mt-1 text-sm {{ $pass ? 'text-green-500' : 'text-red-500' }}">
                {{ $pass ? 'Lulus ✅' : 'Tidak Lulus ❌' }}
            </p>
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('post-test.index') }}"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2 rounded-lg transition-all">
                ← Kembali ke Halaman Ebook
            </a>
        </div>
    </div>
@endsection
