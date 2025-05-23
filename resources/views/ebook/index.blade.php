@extends('layouts.app')

@section('namePage', 'eBook')

@section('content')
<div class="bg-white dark:bg-gray-800 p-3 rounded-2xl">
    <!-- Search Bar -->
    <div class="w-full flex items-center gap-2 mb-5">
        <form action="" method="GET" class="flex items-center gap-2 flex-grow">
            <input type="text" name="search" placeholder="Cari judul atau penulis eBook..."
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" />
            <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition cursor-pointer">
                Cari
            </button>
        </form>

        <a href="{{ route('ebook.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition cursor-pointer">
            <span class="block sm:hidden">+</span>
            <span class="hidden sm:block">Tambah e-Book</span>
        </a>
    </div>

    <!-- Grid Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
        @forelse ($ebooks as $item)
        <div
            class="flex flex-col gap-3 bg-gray-200 dark:bg-gray-700 p-4 rounded-xl group hover:text-blue-500 transition-all">
            <a href="{{ route('ebook.show', $item->id) }}" class="overflow-hidden rounded-lg block">
                <img src="{{ asset($item->cover_image) }}" alt="{{ $item->judul }}"
                    class="rounded-lg transition-transform duration-300 ease-in-out w-full aspect-[3/4] object-cover" />
            </a>

            <div class="text-center h-20 flex flex-col justify-between items-center space-y-1">
                <a href="{{ route('ebook.show', $item->id) }}"
                    class="text-base font-semibold leading-tight line-clamp-2" title="{{ $item->judul }}">
                    {{ $item->judul }}
                </a>

                <a href="{{ route('ebook.show', $item->id) }}"
                    class="text-sm font-medium text-gray-600 dark:text-gray-300 leading-snug line-clamp-1"
                    title="{{ $item->penulis }} - {{ $item->tahun_terbit }}">
                    {{ $item->penulis }} - {{ $item->tahun_terbit }}
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10 text-gray-500 dark:text-gray-400">
            Tidak ada ebook ditemukan.
        </div>
        @endforelse
    </div>
</div>
@endsection