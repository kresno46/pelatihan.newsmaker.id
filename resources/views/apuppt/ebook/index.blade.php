@extends('layouts.app')
@section('namePage', $folder->folder_name)
@section('content')
<div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-lg">
    <div class="w-full flex items-center justify-between mb-5">
        <a href="{{ route('apuppt.ebookfolder.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition">Kembali</a>
        <a href="{{ route('apuppt.ebook.create', $folder->slug) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">Tambah Ebook</a>
    </div>

    <form method="GET" action="{{ route('apuppt.ebook.index', $folder->slug) }}" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul/deskripsi" class="border rounded p-2">
        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}" class="border rounded p-2">
        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}" class="border rounded p-2">
        <button class="bg-gray-700 text-white rounded p-2">Filter</button>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @forelse ($ebooks as $item)
            <a href="{{ route('apuppt.ebook.show', [$folder->slug, $item->slug]) }}" class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md border group flex flex-col h-full">
                <div class="h-40 bg-gray-200 overflow-hidden"><img src="{{ asset($item->cover) }}" alt="{{ $item->title }}" class="object-cover w-full h-full transition group-hover:scale-105"></div>
                <div class="p-4 flex-1 flex flex-col">
                    <h3 class="font-semibold text-base mb-2">{{ $item->title }}</h3>
                    <p class="text-sm line-clamp-3 flex-grow">{{ $item->deskripsi }}</p>
                    <div class="mt-3 text-xs text-gray-500">{{ $item->created_at->diffForHumans() }}</div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-10 text-gray-500">Tidak ada ebook APUPPT ditemukan.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $ebooks->links() }}</div>
</div>
@endsection