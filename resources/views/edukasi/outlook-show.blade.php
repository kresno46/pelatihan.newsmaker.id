@extends('layouts.app')

@section('namePage', 'Edukasi - Outlook')

@section('content')
    <div class="container">
        <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl shadow-lg">
            @if (!empty($folder))
                <div
                    class="mb-6 rounded-2xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/40 p-4 sm:p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <a href="{{ route('edukasi.outlook') }}"
                            class="w-12 h-12 rounded-xl bg-orange-100 hover:bg-orange-200 transition-all text-orange-700 flex items-center justify-center">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                        <div class="flex-1">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $folder['folder_name'] ?? 'Tanpa Nama' }}
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $folder['deskripsi'] ?? 'Tidak ada deskripsi.' }}
                            </p>
                        </div>
                        <div class="text-xs text-gray-500">
                            @if (!empty($folder['category']))
                                <span
                                    class="rounded-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-3 py-1">
                                    Kategori: {{ $folder['category'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse ($outlooks as $outlook)
                    <div
                        class="group rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900/40 overflow-hidden shadow-sm hover:shadow-md transition">
                        <div class="p-4 space-y-3">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2">
                                    {{ $outlook['title'] ?? ($outlook['judul'] ?? 'Tanpa Judul') }}
                                </h3>
                                <span
                                    class="text-[10px] uppercase tracking-wide text-orange-600 bg-orange-50 dark:bg-orange-900/30 px-2 py-1 rounded-full">
                                    PDF
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">
                                {{ $outlook['deskripsi'] ?? ($outlook['description'] ?? 'Tidak ada deskripsi.') }}
                            </p>
                            <div class="pt-2">
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
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-orange-600 text-white hover:bg-orange-700 transition shadow-sm ring-1 ring-orange-700/30">
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

            @if (!empty($pagination['links']))
                <div class="mt-6 flex flex-wrap gap-2 justify-center">
                    @foreach ($pagination['links'] as $link)
                        @php
                            $label = $link['label'] ?? '';
                            $page = $link['page'] ?? null;
                            $isActive = $link['active'] ?? false;
                            $isDisabled = empty($link['url']) || empty($page);
                            $targetUrl = $isDisabled
                                ? '#'
                                : route('edukasi.outlook.show', $folderSlug) . '?page=' . $page;
                        @endphp
                        <a href="{{ $targetUrl }}"
                            class="px-3 py-1 rounded-md text-sm border transition
                            {{ $isActive ? 'bg-orange-600 text-white border-orange-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700' }}
                            {{ $isDisabled ? 'pointer-events-none opacity-50' : '' }}">
                            {!! $label !!}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
