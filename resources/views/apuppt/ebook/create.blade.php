@extends('layouts.app')
@section('namePage', 'Tambah Ebook APUPPT')
@section('content')
<div class="card p-6 bg-white dark:bg-gray-800 rounded shadow">
    @if ($errors->any())
        <div class="mb-4 rounded border border-red-300 bg-red-50 px-4 py-3 text-red-700">
            <div class="font-semibold mb-1">Gagal menyimpan. Cek input berikut:</div>
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('apuppt.ebook.store', $folder->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label class="block font-medium">Judul Ebook</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded p-2" required>
            @error('title')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Cover</label>
                <input type="file" name="cover" class="w-full border rounded p-2" accept=".jpg,.jpeg,.png" required>
                <p class="text-xs text-gray-500 mt-1">Wajib, format JPG/JPEG/PNG, maks 2MB.</p>
                @error('cover')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block font-medium">File PDF</label>
                <input type="file" name="file" class="w-full border rounded p-2" accept=".pdf" required>
                <p class="text-xs text-gray-500 mt-1">Wajib, format PDF, maks 10MB.</p>
                @error('file')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div>
            <label class="block font-medium">Deskripsi</label>
            <textarea name="deskripsi" rows="8" class="w-full border rounded p-2" required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-end gap-2"><a href="{{ route('apuppt.ebook.index', $folder->slug) }}" class="px-4 py-2 bg-gray-300 rounded">Batal</a><button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button></div>
    </form>
</div>
@endsection
