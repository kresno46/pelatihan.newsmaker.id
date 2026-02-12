@extends('layouts.app')

@section('namePage', 'Edukasi - Outlook')

@section('content')
    <div class="container">
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl shadow-lg">
            <div
                class="mb-6 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50 via-white to-orange-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <div
                            class="inline-flex items-center gap-2 rounded-full bg-orange-100 text-orange-700 px-3 py-1 text-xs font-semibold">
                            <i class="fa-solid fa-newspaper"></i>
                            Outlook
                        </div>
                        <h1 class="mt-2 text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Outlook</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Pilih folder untuk melihat daftar outlook.
                        </p>
                    </div>
                    <div class="text-xs text-gray-500">
                        Update harian, mingguan, dan insight.
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($folders as $folder)
                    <a href="{{ route('edukasi.outlook.show', $folder['slug']) }}"
                        class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900/40 p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <div
                                class="h-12 w-12 rounded-full bg-orange-50 border border-orange-100 overflow-hidden flex items-center justify-center">
                                @if (!empty($folder['cover_folder']))
                                    @php
                                        $folderCoverUrl = \Illuminate\Support\Str::startsWith($folder['cover_folder'], [
                                            'http://',
                                            'https://',
                                        ])
                                            ? $folder['cover_folder']
                                            : 'https://ebook.newsmaker.id/' . ltrim($folder['cover_folder'], '/');
                                    @endphp
                                    <img src="{{ $folderCoverUrl }}" alt="Icon {{ $folder['folder_name'] ?? 'Outlook' }}"
                                        class="w-full h-full object-contain rounded-full">
                                @else
                                    <i class="fa-solid fa-folder-open text-orange-600"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex items-start justify-between gap-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $folder['folder_name'] ?? 'Tanpa Nama' }}
                                    </h3>
                                    @if (!empty($folder['category']))
                                        <span
                                            class="text-[10px] uppercase tracking-wide text-orange-600 bg-orange-50 dark:bg-orange-900/30 px-2 py-1 rounded-full">
                                            {{ $folder['category'] }}
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                    {{ $folder['Deskripsi'] ?? ($folder['deskripsi'] ?? 'Tidak ada deskripsi.') }}
                                </p>
                                <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                                    <span>{{ $folder['outlooks_count'] ?? 0 }} outlook</span>
                                    <span class="group-hover:text-orange-600 transition">Lihat folder →</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center py-10">
                        <p class="text-gray-500">Tidak ada folder outlook ditemukan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
