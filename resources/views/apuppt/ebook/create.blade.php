@extends('layouts.app')
@section('namePage', 'Tambah Ebook APUPPT')
@section('content')
<div class="card p-6 bg-white dark:bg-gray-800 rounded shadow">
    <form action="{{ route('apuppt.ebook.store', $folder->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div><label class="block font-medium">Judul Ebook</label><input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded p-2"></div>
        <div class="grid md:grid-cols-2 gap-4">
            <div><label class="block font-medium">Cover</label><input type="file" name="cover" class="w-full border rounded p-2"></div>
            <div><label class="block font-medium">File PDF</label><input type="file" name="file" class="w-full border rounded p-2"></div>
        </div>
        <div><label class="block font-medium">Deskripsi</label><textarea name="deskripsi" rows="8" class="w-full border rounded p-2">{{ old('deskripsi') }}</textarea></div>
        <div class="flex justify-end gap-2"><a href="{{ route('apuppt.ebook.index', $folder->slug) }}" class="px-4 py-2 bg-gray-300 rounded">Batal</a><button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button></div>
    </form>
</div>
@endsection