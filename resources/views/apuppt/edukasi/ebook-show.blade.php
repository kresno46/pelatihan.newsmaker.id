@extends('layouts.app')

@section('namePage', 'Edukasi APUPPT - Ebook')

@section('content')
<div class="w-full">
    <div class="bg-white dark:bg-gray-800 p-4 sm:p-6 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between gap-3 mb-6">
            <div class="flex items-center gap-5">
                <a href="{{ route('apuppt.edukasi.ebook.index') }}" class="inline-flex items-center gap-2 p-3 text-sm font-semibold rounded-lg bg-gray-200"><i class="fa-solid fa-arrow-left"></i></a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold">Ebook APUPPT</h1>
                    <p class="text-sm text-gray-600 font-semibold">{{ $folderName ?? $folderSlug }}</p>
                </div>
            </div>
            <div class="text-xs text-gray-500">Materi APUPPT dikelola internal admin.</div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($ebooks as $ebook)
                <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden shadow-sm hover:shadow-md transition flex flex-col h-full">
                    <div class="aspect-[4/3] bg-gray-200">
                        @if (!empty($ebook->cover))
                            <img src="{{ asset($ebook->cover) }}" alt="Cover {{ $ebook->title ?? 'Ebook' }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500 text-sm">Tidak ada cover</div>
                        @endif
                    </div>
                    <div class="p-4 flex flex-col flex-1">
                        <div class="space-y-2">
                            <h3 class="text-lg font-semibold line-clamp-2">{{ $ebook->title ?? 'Tanpa Judul' }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-3">{{ $ebook->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                        </div>
                        <div class="mt-auto pt-4">
                            @if (!empty($ebook->file))
                                <a href="{{ asset($ebook->file) }}" target="_blank" download class="inline-flex w-full items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                                    <i class="fa-solid fa-download"></i>
                                    Download Ebook
                                </a>
                            @else
                                <span class="text-xs text-gray-500">File belum tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-10 text-gray-500">Tidak ada ebook APUPPT ditemukan.</div>
            @endforelse
        </div>

        <div class="mt-6">{{ $ebooks->links() }}</div>
    </div>
</div>
@endsection