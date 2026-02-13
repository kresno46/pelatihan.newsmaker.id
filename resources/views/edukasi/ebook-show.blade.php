@extends('layouts.app')

@section('namePage', 'Edukasi - Ebook')

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl shadow-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Ebook</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Folder: <span class="font-semibold">{{ $folderSlug }}</span>
                    </p>
                </div>
                <a href="{{ route('edukasi.ebook') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($ebooks as $ebook)
                    <div
                        class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 overflow-hidden shadow-sm">
                        <div class="aspect-[4/3] bg-gray-200 dark:bg-gray-700">
                            @if (!empty($ebook['cover']))
                                @php
                                    $coverUrl = \Illuminate\Support\Str::startsWith($ebook['cover'], [
                                        'http://',
                                        'https://',
                                    ])
                                        ? $ebook['cover']
                                        : 'https://ebook.newsmaker.id/' . ltrim($ebook['cover'], '/');
                                @endphp
                                <img src="{{ $coverUrl }}" alt="Cover {{ $ebook['title'] ?? 'Ebook' }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500 text-sm">
                                    Tidak ada cover
                                </div>
                            @endif
                        </div>
                        <div class="p-4 space-y-2">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                                {{ $ebook['title'] ?? 'Tanpa Judul' }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">
                                {{ $ebook['deskripsi'] ?? ($ebook['description'] ?? 'Tidak ada deskripsi.') }}
                            </p>
                            <div class="pt-2">
                                @if (!empty($ebook['file']))
                                    @php
                                        $fileUrl = \Illuminate\Support\Str::startsWith($ebook['file'], [
                                            'http://',
                                            'https://',
                                        ])
                                            ? $ebook['file']
                                            : 'https://ebook.newsmaker.id/' . ltrim($ebook['file'], '/');
                                    @endphp
                                    <a href="{{ $fileUrl }}" target="_blank" download
                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                        <i class="fa-solid fa-download"></i>
                                        Download Ebook
                                    </a>
                                @else
                                    <span class="text-xs text-gray-500">File belum tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-10">
                        <p class="text-gray-500">Tidak ada ebook ditemukan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
