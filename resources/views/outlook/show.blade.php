@extends('layouts.app')

@section('namePage', 'Outlook')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">

        <a href="{{ route('outlook.index', $folder->slug) }}"
            class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition cursor-pointer mb-5 inline-block">
            <span class="block sm:hidden"><i class="fa-solid fa-arrow-left"></i></span>
            <span class="hidden sm:block">Kembali</span>
        </a>

        <div class="flex flex-col md:flex-row gap-6">
            <div class="w-full md:w-1/3">
                <img src="{{ asset($outlook->cover) }}" alt="Cover {{ $outlook->title }}" class="rounded shadow w-full h-auto" />
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-3">{{ $outlook->title }}</h1>
                <p class="text-gray-700 dark:text-gray-300 mb-4 whitespace-pre-line">{{ $outlook->deskripsi }}</p>

                <a href="{{ asset($outlook->file) }}" target="_blank"
                    class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Buka File Outlook
                </a>
            </div>
        </div>

    </div>
@endsection
