@extends('layouts.app')

@section('namePage', $folder->folder_name)

@section('content')
    <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-lg">

        <div class="w-full flex items-center justify-between mb-5">
            {{-- Tombol Kembali --}}
            <a href="{{ route('outlookfolder.index') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition cursor-pointer sm:w-auto">
                <span class="block sm:hidden"><i class="fa-solid fa-arrow-left"></i></span>
                <span class="hidden sm:block">Kembali</span>
            </a>

            {{-- Kumpulan Tombol Aksi --}}
            <div class="flex gap-2">
                {{-- Tombol Filter --}}
                <button onclick="document.getElementById('filterModal').classList.remove('hidden')"
                    class="px-4 py-2 bg-zinc-300 hover:bg-zinc-200 transition-all duration-300 rounded-lg text-sm">
                    <i class="fa-solid fa-sliders"></i>
                </button>

                {{-- Tombol Tambah (Hanya Admin) --}}
                @if (Auth::user()->role === 'Admin')
                    <a href="{{ route('outlook.create', $folder->slug) }}"
                        class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition cursor-pointer">
                        <span class="block sm:hidden">+</span>
                        <span class="hidden sm:block">Tambah Outlook</span>
                    </a>
                @endif
            </div>
        </div>

        {{-- Modal Filter --}}
        <div id="filterModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden px-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-lg w-full p-6">
                <form method="GET" action="{{ route('outlook.index', $folder->slug) }}">
                    <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Filter Pencarian</h2>

                    <div class="mb-4">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Kata Kunci</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul atau deskripsi"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Dari</label>
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                            Sampai</label>
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')"
                            class="px-4 py-2 rounded-lg bg-gray-300 hover:bg-gray-400 text-sm text-gray-800 dark:text-gray-900">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-sm text-white">Terapkan</button>

                        {{-- Tombol Reset --}}
                        <a href="{{ route('outlook.index', $folder->slug) }}"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition cursor-pointer">
                            Reset
                        </a>
                    </div>
                </form>
            </div>
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
