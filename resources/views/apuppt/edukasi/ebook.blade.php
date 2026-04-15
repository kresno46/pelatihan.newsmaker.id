@extends('layouts.app')

@section('namePage', 'Edukasi APUPPT - Ebook')

@section('content')
<div class="w-full">
    <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl shadow-lg">
        <div class="mb-6 rounded-2xl border border-gray-200 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <div class="inline-flex items-center gap-2 rounded-full bg-blue-100 text-blue-700 px-3 py-1 text-xs font-semibold">
                        <i class="fa-solid fa-book-bookmark"></i>
                        Ebook APUPPT
                    </div>
                    <h1 class="mt-2 text-2xl sm:text-3xl font-bold">Edukasi APUPPT</h1>
                    <p class="text-sm text-gray-600">Pilih folder untuk melihat daftar ebook APUPPT.</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($folders as $folder)
                <a href="{{ route('apuppt.edukasi.ebook.show', $folder->slug) }}" class="group rounded-2xl border border-gray-200 bg-white p-5 shadow-sm hover:shadow-md transition">
                    <div class="flex items-start gap-4">
                        <div class="h-12 w-12 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center"><i class="fa-solid fa-book-bookmark text-blue-600"></i></div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold">{{ $folder->folder_name }}</h3>
                            <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $folder->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                            <div class="mt-3 flex items-center justify-between text-xs text-gray-500">
                                <span>{{ $folder->ebooks_count ?? 0 }} ebook</span>
                                <span class="group-hover:text-blue-600 transition">Lihat folder -></span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 text-center py-10 text-gray-500">Tidak ada folder ebook APUPPT ditemukan.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection