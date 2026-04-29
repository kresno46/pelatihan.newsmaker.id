@extends('layouts.app')

@section('namePage', 'APUPPT Folder Ebook')

@section('content')
<div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-lg">
    <div class="w-full flex items-center gap-2 mb-5">
        <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition"><i class="fa-solid fa-arrow-left text-sm"></i></a>
        <form action="{{ route('apuppt.ebookfolder.index') }}" method="GET" class="flex items-center gap-2 flex-grow">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Folder APUPPT..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg text-sm bg-white dark:bg-gray-800" />
        </form>
        <a href="{{ route('apuppt.ebookfolder.index') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition">Reset</a>
        <a href="{{ route('apuppt.ebookfolder.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">Tambah Folder</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse ($folders as $folder)
            <div class="shadow-lg rounded-2xl bg-zinc-50 dark:bg-zinc-700 p-6 space-y-4 border">
                <h1 class="text-xl font-semibold">{{ $folder->folder_name }}</h1>
                <p class="text-sm">
                    Status:
                    <span class="font-semibold {{ $folder->is_active ? 'text-green-600' : 'text-red-600' }}">
                        {{ $folder->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </p>
                <p class="text-sm">{{ $folder->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                <p class="text-sm">{{ $folder->ebooks_count }} Ebook APUPPT</p>
                <div class="flex items-stretch gap-2">
                    <a href="{{ route('apuppt.ebook.index', $folder->slug) }}" class="w-full px-4 py-1 bg-blue-500 text-white rounded-lg text-sm text-center">Lihat</a>
                    <a href="{{ route('apuppt.ebookfolder.edit', $folder->slug) }}" class="w-full px-4 py-1 bg-yellow-500 text-white rounded-lg text-sm text-center">Edit</a>
                    <form action="{{ route('apuppt.ebookfolder.toggle', $folder->id) }}" method="POST" class="w-full">@csrf
                        <button type="submit" class="w-full px-4 py-1 {{ $folder->is_active ? 'bg-orange-500' : 'bg-green-500' }} text-white rounded-lg text-sm text-center">
                            {{ $folder->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <form action="{{ route('apuppt.ebookfolder.destroy', $folder->id) }}" method="POST" class="w-full">@csrf @method('DELETE')
                        <button type="submit" onclick="return confirm('Yakin hapus folder ini?')" class="w-full px-4 py-1 bg-red-500 text-white rounded-lg text-sm text-center">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-4 text-center py-10 text-gray-500">Tidak ada folder APUPPT ditemukan.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $folders->links() }}</div>
</div>
@endsection
