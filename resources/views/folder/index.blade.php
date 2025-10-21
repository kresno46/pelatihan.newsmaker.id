@extends('layouts.app')

@section('namePage', 'ebook')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-lg">

        {{-- API Indicator --}}
        <div class="mb-4">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                </svg>
                Data diambil dari API
            </span>
        </div>

        {{-- Search & Actions --}}
        <div class="w-full flex items-center gap-2 mb-5">
            <a href="{{ route('dashboard') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition cursor-pointer sm:w-auto">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <form action="{{ route('folder.index') }}" method="GET" class="flex items-center gap-2 flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Folder..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" />
            </form>

            <a href="{{ route('folder.index') }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition">
                Reset
            </a>

            {{-- Admin input disabled - buttons hidden but routes kept for compatibility --}}
            {{-- <a href="{{ route('folder.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">
                <span class="block sm:hidden">+</span>
                <span class="hidden sm:block">Tambah Folder</span>
            </a> --}}

            @if (Auth::user()->role === 'Admin')
                <form action="{{ route('folder.sync') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-green-600 transition">
                        <span class="block sm:hidden">🔄</span>
                        <span class="hidden sm:block">Sync dari API</span>
                    </button>
                </form>
            @endif
        </div>

        {{-- Grid Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($folders as $folder)
                <div
                    class="shadow-lg rounded-2xl bg-zinc-50 dark:bg-zinc-700 dark:text-gray-100 p-6 space-y-4 transition-all border dark:border-zinc-800 duration-300 hover:shadow-xl h-full">

                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        <div class="py-4 px-5 bg-blue-300 dark:bg-blue-800 rounded-full">
                            <i class="fa-solid fa-book-bookmark fa-2x text-blue-500 dark:text-blue-300"></i>
                        </div>

                        <div class="flex-1">
                            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">
                                {{ $folder->folder_name }}
                            </h1>

                            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed line-clamp-1 mb-1">
                                {{ $folder->Deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300 text-sm">
                                <i class="fa-solid fa-book-open mr-1"></i>
                                {{ $folder->ebooks_count }} eBook dalam materi ini
                            </p>
                        </div>
                    </div>

                    <div class="flex items-stretch gap-2 mt-4">
                        {{-- Tombol Lihat eBook --}}
                        <a href="{{ route('ebook.index', $folder->slug) }}"
                            class="w-full px-4 py-1 bg-blue-500 text-white rounded-lg text-sm hover:bg-blue-600 transition text-center block">
                            Lihat
                        </a>

                        {{-- Admin input disabled - buttons hidden but routes kept for compatibility --}}
                        {{-- <a href="{{ route('folder.edit', $folder->slug) }}"
                            class="w-full px-4 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 transition text-center block">
                            Edit
                        </a> --}}

                        {{-- <form action="{{ route('folder.destroy', $folder->id) }}" method="POST" class="w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin menghapus folder ini?')"
                                class="w-full px-4 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition text-center">
                                Hapus
                            </button>
                        </form> --}}
                    </div>
                </div>

            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-4 text-center py-10">
                    <p class="text-gray-500">Tidak ada folder eBook ditemukan.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 gap-2">
            @if ($folders->total() > 8)
                <div class="w-full">
                    {{ $folders->appends(['search' => request('search')])->links() }}
                </div>
            @else
                <div class="text-sm text-gray-600 dark:text-gray-300 p-2">
                    Menampilkan
                    @if ($folders->total() > 0)
                        {{ $folders->firstItem() }} sampai {{ $folders->lastItem() }} dari total {{ $folders->total() }}
                        hasil
                    @else
                        0 sampai 0 dari total 0 hasil
                    @endif
                </div>
            @endif
        </div>

    </div>
@endsection
