@extends('layouts.app')

@section('namePage', 'Edukasi - Ebook')

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl shadow-lg">
            <div class="flex items-center justify-between gap-3 mb-6">
                <div class="flex items-center gap-5">
                    <a href="{{ route('edukasi.ebook') }}"
                        class="inline-flex items-center gap-2 p-3 text-sm font-semibold rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>

                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Ebook</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-300 font-semibold">
                            {{ $folderName ?? $folderSlug }}
                        </p>
                    </div>
                </div>

                <a href="https://ebook.newsmaker.id/ebook" target="_blank" rel="noopener noreferrer"
                    class="items-center gap-2 px-3 py-2 md:px-4 md:py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-up-right-from-square"></i>
                        <span class="hidden md:block">Buka Ebook</span>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse ($ebooks as $ebook)
                    <div
                        class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900/40 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col h-full">
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

                        <div class="p-4 flex flex-col flex-1">
                            <div class="space-y-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                                    {{ $ebook['title'] ?? 'Tanpa Judul' }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">
                                    {{ $ebook['deskripsi'] ?? ($ebook['description'] ?? 'Tidak ada deskripsi.') }}
                                </p>
                            </div>

                            <div class="mt-auto pt-4">
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
                                        class="inline-flex w-full items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
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

            @if (!empty($pagination))
                <div class="mt-6 w-full flex items-center justify-between gap-3">
                    <div class="flex items-center">
                        @if (!empty($pagination['prev_url']))
                            <a href="{{ $pagination['prev_url'] }}"
                                class="px-3 py-2 text-sm font-semibold rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Sebelumnya
                            </a>
                        @else
                            <span
                                class="px-3 py-2 text-sm font-semibold rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-400 cursor-not-allowed">
                                Sebelumnya
                            </span>
                        @endif
                    </div>

                    <div class="text-sm text-gray-600 dark:text-gray-300 text-center flex-1">
                        Halaman {{ $pagination['current_page'] }} dari {{ $pagination['last_page'] }}
                    </div>

                    <div class="flex items-center justify-end">
                        @if (!empty($pagination['next_url']))
                            <a href="{{ $pagination['next_url'] }}"
                                class="px-3 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                Berikutnya
                            </a>
                        @else
                            <span
                                class="px-3 py-2 text-sm font-semibold rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-400 cursor-not-allowed">
                                Berikutnya
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
