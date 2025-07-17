@extends('layouts.app')

@section('namePage', 'Outlook')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-lg">

        {{-- Search Bar --}}
        <div class="w-full flex items-center gap-2 mb-5">
            <a href="{{ route('outlookfolder.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition cursor-pointer">
                <span class="block sm:hidden"><i class="fa-solid fa-arrow-left"></i></span>
                <span class="hidden sm:block">Kembali</span>
            </a>

            <form action="{{ route('outlookfolder.index') }}" method="GET" class="flex items-center gap-2 flex-grow">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari judul atau penulis Outlook..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" />
            </form>

            <a href="{{ route('outlookfolder.index') }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition cursor-pointer">
                Reset
            </a>

            @if (Auth::user()->role === 'Admin')
                <a href="{{ route('outlookfolder.create') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition cursor-pointer">
                    <span class="block sm:hidden">+</span>
                    <span class="hidden sm:block">Tambah Outlook</span>
                </a>
            @endif
        </div>

        {{-- List Outlook dengan Baris Bergantian --}}
        <div class="flex flex-col divide-y divide-gray-200 dark:divide-gray-600">
            @forelse ($outlooks as $index => $item)
                <a href="{{ route('outlook.show', [$folder->slug, $item->slug]) }}"
                    class="flex items-start gap-4 py-4 px-4 transition rounded-lg md:rounded-none
                           @if ($index % 2 === 0) bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 
                           @else bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 @endif">

                    {{-- Gambar kecil --}}
                    <div class="w-16 h-24 overflow-hidden rounded shadow flex-shrink-0">
                        <img src="{{ asset($item->cover) }}" alt="Cover {{ $item->title }}"
                            class="object-cover w-full h-full" />
                    </div>

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

                </a>
            @empty
                <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                    Tidak ada outlook ditemukan.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 gap-2">
            @if ($outlooks->total() > 8)
                <div class="w-full">
                    {{ $outlooks->appends(['search' => request('search')])->links() }}
                </div>
            @else
                <div class="text-sm text-gray-600 dark:text-gray-300 p-2">
                    Menampilkan
                    @if ($outlooks->total() > 0)
                        {{ $outlooks->firstItem() }} sampai {{ $outlooks->lastItem() }} dari total {{ $outlooks->total() }}
                        hasil
                    @else
                        0 sampai 0 dari total 0 hasil
                    @endif
                </div>
            @endif
        </div>

    </div>
@endsection
