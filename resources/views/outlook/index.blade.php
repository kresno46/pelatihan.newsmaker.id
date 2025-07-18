@extends('layouts.app')

@section('namePage', $folder->folder_name)

@section('content')
    <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-lg">

        {{-- Search Bar --}}
        <div class="w-full flex items-center gap-2 mb-5">
            <a href="{{ route('outlookfolder.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition cursor-pointer">
                <span class="block sm:hidden"><i class="fa-solid fa-arrow-left"></i></span>
                <span class="hidden sm:block">Kembali</span>
            </a>

            <form action="{{ route('outlook.index', $folder->slug) }}" method="GET"
                class="flex items-center gap-2 flex-grow">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari judul, deskripsi, atau tanggal (cth: 17 Juli 2025)"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" />
            </form>

            <a href="{{ route('outlook.index', $folder->slug) }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition cursor-pointer">
                Reset
            </a>

            @if (Auth::user()->role === 'Admin')
                <a href="{{ route('outlook.create', $folder->slug) }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition cursor-pointer">
                    <span class="block sm:hidden">+</span>
                    <span class="hidden sm:block">Tambah Outlook</span>
                </a>
            @endif
        </div>

        {{-- List Outlook dengan Baris Bergantian --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse ($outlooks as $item)
                <a href="{{ route('outlook.show', [$folder->slug, $item->slug]) }}"
                    class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group flex flex-col h-full">

                    <div class="h-40 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        <img src="{{ asset($item->cover) }}" alt="Cover {{ $item->title }}"
                            class="object-cover w-full h-full transition duration-300 group-hover:scale-105">
                    </div>

                    <div class="p-4 flex-1 flex flex-col">
                        <h3
                            class="font-semibold text-gray-900 dark:text-gray-100 text-base leading-snug mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400">
                            {{ $item->title }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 flex-grow">
                            {{ $item->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </p>
                        <div class="mt-3 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <i class="fa-regular fa-calendar"></i>
                            <span>NewsMaker 23 • {{ $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center py-10 text-gray-500 dark:text-gray-400">
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
                        {{ $outlooks->firstItem() }} sampai {{ $outlooks->lastItem() }} dari total
                        {{ $outlooks->total() }}
                        hasil
                    @else
                        0 sampai 0 dari total 0 hasil
                    @endif
                </div>
            @endif
        </div>

    </div>
@endsection
