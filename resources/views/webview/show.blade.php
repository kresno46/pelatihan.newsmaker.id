@extends('layouts.app')

@section('namePage', $title)

@section('content')
    <div class="flex flex-1 flex-col min-h-0">
        <div
            class="flex items-center justify-between gap-5 border-b border-gray-200 bg-white px-4 py-2 dark:border-gray-700 dark:bg-gray-800 sm:py-4 sm:px-6">
            <div>
                <h1 class="text-lg font-semibold text-gray-900 dark:text-white sm:text-2xl">{{ $title }}</h1>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Jika tampilan kosong, kemungkinan situs tujuan memblokir iframe. Gunakan tombol "Buka Tab Baru".
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-700 sm:px-4">
                    <i class="fa-solid fa-up-right-from-square"></i>
                    <span class="hidden sm:inline">Buka Tab Baru</span>
                </a>
            </div>
        </div>

        <iframe src="{{ $url }}" title="{{ $title }}" class="w-full flex-1 bg-white dark:bg-gray-900"
            loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
@endsection
