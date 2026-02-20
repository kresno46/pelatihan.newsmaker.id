@extends('layouts.app')

@section('namePage', 'Edukasi - Outlook')

@section('content')
    <div class="w-full">
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl shadow-lg">
            @if (!empty($folder))
                <div
                    class="mb-6 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4 sm:p-5">
                    <div class="flex gap-4">
                        <a href="{{ route('edukasi.outlook') }}"
                            class="w-12 h-12 rounded-xl bg-red-100 hover:bg-red-200 transition-all text-red-700 flex items-center justify-center">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                        <div class="flex-1 flex items-center justify-between gap-3">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $folder['folder_name'] ?? 'Tanpa Nama' }}
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    {{ $folder['deskripsi'] ?? 'Tidak ada deskripsi.' }}
                                </p>
                            </div>

                            <div class="text-xs text-red-500">
                                @if (!empty($folder['category']))
                                    <span
                                        class="rounded-full bg-red-50 dark:bg-red-800/50 border border-red-200 dark:border-red-700 px-3 py-1 capitalize">
                                        {{ $folder['category'] }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @forelse ($outlooks as $outlook)
                    <div
                        class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900/40 overflow-hidden shadow-sm hover:shadow-md transition flex flex-col h-full">
                        <div class="aspect-[4/3] bg-gray-200 dark:bg-gray-700">
                            @if (!empty($outlook['cover']))
                                @php
                                    $coverUrl = \Illuminate\Support\Str::startsWith($outlook['cover'], [
                                        'http://',
                                        'https://',
                                    ])
                                        ? $outlook['cover']
                                        : 'https://ebook.newsmaker.id/' . ltrim($outlook['cover'], '/');
                                @endphp
                                <img src="{{ $coverUrl }}"
                                    alt="Cover {{ $outlook['title'] ?? ($outlook['judul'] ?? 'Outlook') }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500 text-sm">
                                    Tidak ada cover
                                </div>
                            @endif
                        </div>
                        <div class="p-4 space-y-3 flex flex-col flex-1">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                                    {{ $outlook['title'] ?? ($outlook['judul'] ?? 'Tanpa Judul') }}
                                </h3>
                                <span
                                    class="text-[10px] uppercase tracking-wide text-red-600 bg-red-50 dark:bg-red-900/30 px-2 py-1 rounded-full">
                                    PDF
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">
                                {{ $outlook['deskripsi'] ?? ($outlook['description'] ?? 'Tidak ada deskripsi.') }}
                            </p>
                            <div class="pt-2 mt-auto">
                                @if (!empty($outlook['file']))
                                    @php
                                        $fileUrl = \Illuminate\Support\Str::startsWith($outlook['file'], [
                                            'http://',
                                            'https://',
                                        ])
                                            ? $outlook['file']
                                            : 'https://ebook.newsmaker.id/' . ltrim($outlook['file'], '/');
                                    @endphp
                                    <a href="{{ $fileUrl }}" target="_blank" download
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-red-600 text-white hover:bg-red-700 transition shadow-sm ring-1 ring-red-700/30">
                                        <i class="fa-solid fa-download"></i>
                                        Download
                                    </a>
                                @else
                                    <span class="text-xs text-gray-500">File belum tersedia</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-10">
                        <p class="text-gray-500">Tidak ada outlook ditemukan.</p>
                    </div>
                @endforelse
            </div>

            @if (!empty($pagination))
                @php
                    $currentPage = $pagination['current_page'] ?? null;
                    $lastPage = $pagination['last_page'] ?? null;
                    $prevHref = !empty($pagination['prev_page_url'])
                        ? route('edukasi.outlook.show', $folderSlug) . '?page=' . ($currentPage - 1)
                        : null;
                    $nextHref = !empty($pagination['next_page_url'])
                        ? route('edukasi.outlook.show', $folderSlug) . '?page=' . ($currentPage + 1)
                        : null;
                @endphp
                <div class="mt-6 w-full flex items-center justify-between gap-3">
                    <div class="flex items-center">
                        @if (!empty($prevHref))
                            <a href="{{ $prevHref }}"
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
                        Halaman {{ $currentPage ?? '-' }} dari {{ $lastPage ?? '-' }}
                    </div>

                    <div class="flex items-center justify-end">
                        @if (!empty($nextHref))
                            <a href="{{ $nextHref }}"
                                class="px-3 py-2 text-sm font-semibold rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
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
