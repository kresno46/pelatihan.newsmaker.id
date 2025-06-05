@extends('layouts.app')

@section('namePage', 'eBook')

@section('content')
<div class="bg-white dark:bg-gray-800 p-3 rounded-2xl">
    <!-- Search Bar -->
    <div class="w-full flex items-center gap-2 mb-5">
        <form action="{{ route('ebook.index') }}" method="GET" class="flex items-center gap-2 flex-grow">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari judul atau penulis eBook..."
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" />
        </form>

        <a href="{{ route('ebook.index') }}"
            class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition cursor-pointer">
            Reset
        </a>

        @if (Auth::user()->role === 'Admin')
        <a href="{{ route('ebook.create') }}"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition cursor-pointer">
            <span class="block sm:hidden">+</span>
            <span class="hidden sm:block">Tambah e-Book</span>
        </a>
        @endif
    </div>

    <!-- Grid Card -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-6">
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
                    class="text-sm font-medium text-gray-600 dark:text-gray-300 leading-snug"
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

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 gap-2">
        @if ($ebooks->total() > 8)
        <div class="w-full">
            {{ $ebooks->appends(['search' => request('search')])->links() }}
        </div>
        @else
        <div class="text-sm text-gray-600 dark:text-gray-300 p-2">
            Menampilkan
            @if ($ebooks->total() > 0)
            {{ $ebooks->firstItem() }} sampai {{ $ebooks->lastItem() }} dari total {{ $ebooks->total() }} hasil
            @else
            0 sampai 0 dari total 0 hasil
            @endif
        </div>
        @endif

    </div>

</div>
@endsection