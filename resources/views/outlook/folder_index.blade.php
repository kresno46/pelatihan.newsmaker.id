@extends('layouts.app')

@section('namePage', 'Outlook')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-lg">

        {{-- Search & Actions --}}
        <div class="w-full flex items-center gap-2 mb-5">
            <form action="{{ route('outlook.folder.index') }}" method="GET" class="flex items-center gap-2 flex-grow">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama Folder..."
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-800" />
            </form>

            <a href="{{ route('outlook.folder.index') }}"
                class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition">
                Reset
            </a>

            @if (Auth::user()->role === 'Admin')
                <a href="{{ route('outlook.folder.create') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-600 transition">
                    <span class="block sm:hidden">+</span>
                    <span class="hidden sm:block">Tambah Folder</span>
                </a>
            @endif
        </div>

        {{-- Grid Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($folders as $folder)
                <div
                    class="shadow-lg rounded-2xl bg-zinc-50 dark:bg-zinc-700 dark:text-gray-100 p-6 space-y-4 transition-all border dark:border-zinc-800 duration-300 hover:shadow-xl h-full">

                    <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                        <div class="py-4 px-5 bg-green-300 dark:bg-green-800 rounded-full">
                            <i class="fa-solid fa-envelope fa-2x text-green-500 dark:text-green-300"></i>
                        </div>

                        <div class="flex-1">
                            <h1 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2">
                                {{ $folder->folder_name }}
                            </h1>

                            <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed line-clamp-1 mb-1">
                                {{ $folder->Deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>

                            <p class="text-gray-600 dark:text-gray-300 text-sm">
                                <i class="fa-solid fa-envelope-open mr-1"></i>
                                {{ $folder->ebooks_count }} Outlook dalam materi ini
                            </p>
                        </div>
                    </div>

                    <div class="flex items-stretch gap-2 mt-4">
                        {{-- Tombol Lihat Outlook --}}
                        <a href="{{ route('outlook.index', $folder->slug) }}"
                            class="w-full px-4 py-1 bg-green-500 text-white rounded-lg text-sm hover:bg-green-600 transition text-center block">
                            Lihat
                        </a>

                        @if (Auth::user()->role === 'Admin')
                            {{-- Tombol Edit Folder --}}
                            <a href="{{ route('outlook.folder.edit', $folder->slug) }}"
                                class="w-full px-4 py-1 bg-yellow-500 text-white rounded-lg text-sm hover:bg-yellow-600 transition text-center block">
                                Edit
                            </a>

                            {{-- Tombol Hapus Folder --}}
                            <form action="{{ route('outlook.folder.destroy', $folder->id) }}" method="POST" class="w-full">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin ingin menghapus folder ini?')"
                                    class="w-full px-4 py-1 bg-red-500 text-white rounded-lg text-sm hover:bg-red-600 transition text-center">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-4 text-center py-10">
                    <p class="text-gray-500">Tidak ada folder Outlook ditemukan.</p>
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
