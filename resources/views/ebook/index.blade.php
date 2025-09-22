@extends('layouts.app')

@section('namePage', 'eBook')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-lg">

        {{-- API Indicator --}}
        <div class="mb-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                </svg>
                Data diambil dari API
            </span>
        </div>

        {{-- Search Bar --}}
        <div class="w-full flex items-center gap-2 mb-5">
            <a href="{{ route('folder.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition cursor-pointer">
                <span class="block sm:hidden"><i class="fa-solid fa-arrow-left"></i></span>
                <span class="hidden sm:block">Kembali</span>
            </a>

            <form action="{{ route('ebook.index', $folder->slug) }}" method="GET" class="flex items-center gap-2 flex-grow">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari judul atau penulis eBook..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" />
            </form>

            <a href="{{ route('ebook.index', $folder->slug) }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition cursor-pointer">
                Reset
            </a>

            {{-- Admin input disabled - button hidden but route kept for compatibility --}}
            {{-- <a href="{{ route('ebook.create', $folder->slug) }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition cursor-pointer">
                <span class="block sm:hidden">+</span>
                <span class="hidden sm:block">Tambah e-Book</span>
            </a> --}}
        </div>

        {{-- List eBook dengan Baris Bergantian --}}
        <div class="flex flex-col divide-y divide-gray-200 dark:divide-gray-600">
            @forelse ($ebooks as $index => $item)
                <div class="flex items-start gap-4 py-4 px-4 transition rounded-lg md:rounded-none
                           @if ($index % 2 === 0) bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600
                           @else bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 @endif">

                    {{-- Gambar kecil - Disabled as requested --}}
                    {{-- <div class="w-16 h-24 overflow-hidden rounded shadow flex-shrink-0">
                        <img src="{{ asset($item->cover) }}" alt="Cover {{ $item->title }}"
                            class="object-cover w-full h-full" />
                    </div> --}}

                    {{-- Konten --}}
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-base mb-1">{{ $item->title }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 mb-1">
                            {{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            (NewsMaker 23)
                            {{ $item->created_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Download Button --}}
                    <div class="flex-shrink-0">
                        <a href="{{ route('ebook.download', [$folder->slug, $item->slug]) }}"
                            class="bg-green-500 text-white px-3 py-2 rounded-lg text-sm font-semibold hover:bg-green-600 transition cursor-pointer"
                            target="_blank"
                            title="Download PDF">
                            <i class="fa-solid fa-download"></i>
                            <span class="hidden sm:inline ml-1">Download</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                    Tidak ada ebook ditemukan.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 gap-2">
            @if ($ebooks->total() > 8)
                <div class="w-full">
                    {{ $ebooks->appends(['search' => request('search')])->links() }}
                </div>
            @else
                <div class="text-sm text-gray-600 dark:text-gray-300 p-2">
                    Menampilkan
                    @if ($ebooks->total() > 0)
                        {{ $ebooks->firstItem() }} sampai {{ $ebooks->lastItem() }} dari total {{ $ebooks->total() }}
                        hasil
                    @else
                        0 sampai 0 dari total 0 hasil
                    @endif
                </div>
            @endif
        </div>

    </div>
@endsection
