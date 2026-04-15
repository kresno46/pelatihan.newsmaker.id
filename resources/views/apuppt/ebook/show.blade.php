@extends('layouts.app')
@section('namePage', $ebook->title)
@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
    <div class="space-y-5">
        <div class="flex items-center justify-between">
            <a href="{{ route('apuppt.ebook.index', $folder->slug) }}" class="bg-gray-200 px-4 py-2 rounded-lg text-sm">Kembali</a>
            <div class="flex gap-2">
                <a href="{{ route('apuppt.ebook.edit', [$folder->slug, $ebook->slug]) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm">Edit</a>
                <form action="{{ route('apuppt.ebook.destroy', [$folder->slug, $ebook->slug]) }}" method="POST" onsubmit="return confirm('Hapus ebook ini?')">@csrf @method('DELETE')<button class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm">Hapus</button></form>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-5">
            <img src="{{ asset($ebook->cover) }}" alt="{{ $ebook->title }}" class="border border-gray-400 rounded-lg overflow-hidden w-full md:w-80">
            <div class="w-full space-y-5">
                <div><label class="text-gray-700">Judul:</label><div class="bg-gray-200 w-full rounded-lg p-3">{{ $ebook->title }}</div></div>
                <div><label class="text-gray-700">Deskripsi:</label><div class="bg-gray-200 w-full rounded-lg p-3">{{ $ebook->deskripsi }}</div></div>
                <a href="{{ asset($ebook->file) }}" target="_blank" class="bg-blue-500 hover:bg-blue-600 py-3 w-full text-center text-white rounded-lg block"><i class="fa-solid fa-download me-2"></i> Download File</a>
            </div>
        </div>
    </div>
</div>
@endsection