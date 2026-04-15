@extends('layouts.app')
@section('namePage', 'Edit Folder Ebook APUPPT')
@section('content')
<div class="card p-6 bg-white dark:bg-gray-800 rounded shadow max-w-3xl">
    <form action="{{ route('apuppt.ebookfolder.update', $folder->id) }}" method="POST" class="space-y-4">
        @csrf @method('PUT')
        <div><label class="block font-medium">Nama Folder</label><input type="text" name="folder_name" value="{{ old('folder_name', $folder->folder_name) }}" class="w-full border rounded p-2"></div>
        <div><label class="block font-medium">Deskripsi</label><textarea name="deskripsi" rows="6" class="w-full border rounded p-2">{{ old('deskripsi', $folder->deskripsi) }}</textarea></div>
        <div class="flex justify-end gap-2"><a href="{{ route('apuppt.ebookfolder.index') }}" class="px-4 py-2 bg-gray-300 rounded">Batal</a><button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button></div>
    </form>
</div>
@endsection