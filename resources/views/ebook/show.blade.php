@extends('layouts.app')

@section('namePage', 'eBook')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('ebook.index') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="flex flex-col md:flex-row gap-6">
        {{-- Cover Image --}}
        <div class="w-full md:w-[300px] aspect-[3/4.5] rounded overflow-hidden shadow mx-auto md:mx-0">
            <img src="{{ asset($ebook->cover_image) }}" alt="Cover {{ $ebook->judul }}"
                class="w-full h-full object-cover" />
        </div>

        {{-- Detail --}}
        <div class="flex-1 flex flex-col justify-between space-y-4">
            <div>
                <h1 class="text-2xl font-bold mb-2 text-center md:text-left text-gray-900 dark:text-white">{{
                    $ebook->judul }}</h1>
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line text-justify mb-3">{{ $ebook->deskripsi
                    }}</p>

                <div class="text-sm text-gray-800 dark:text-gray-200 space-y-1">
                    <p><strong>Penulis:</strong> {{ $ebook->penulis }}</p>
                    <p><strong>Tahun Terbit:</strong> {{ $ebook->tahun_terbit }}</p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="w-full flex flex-col md:flex-row gap-4 mt-4">
                <a href="{{ asset('storage/' . $ebook->file_ebook) }}" target="_blank"
                    class="flex-1 px-4 py-2 text-center bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center justify-center">
                    <i class="fa-solid fa-download"></i>
                    <span class="hidden md:inline ml-2 text-center">Download e-Book</span>
                </a>

                @auth
                @php
                $role = strtolower(Auth::user()->role ?? '');
                @endphp

                @if ($role === 'admin' || $role === 'superadmin')
                <a href="{{ route('ebook.edit', $ebook->id) }}"
                    class="flex-1 px-4 py-2 text-center bg-green-600 text-white rounded hover:bg-green-700 transition flex items-center justify-center">
                    <i class="fa-solid fa-pen-to-square"></i>
                    <span class="hidden md:inline ml-2 text-center">Edit e-Book</span>
                </a>

                <div x-data="{ showModal: false }" class="flex-1">
                    <button @click="showModal = true"
                        class="w-full flex-1 px-4 py-2 text-center bg-red-600 text-white rounded hover:bg-red-700 transition flex items-center justify-center">
                        <i class="fa-solid fa-trash"></i>
                        <span class="hidden md:inline ml-2 text-center">Delete e-Book</span>
                    </button>

                    {{-- Modal Konfirmasi Hapus --}}
                    <div x-show="showModal" x-cloak
                        class="fixed inset-0 flex items-center justify-center z-50 bg-black/50 px-5">
                        <div @click.away="showModal = false"
                            class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 w-full max-w-md text-gray-800 dark:text-gray-200">
                            <h2 class="text-lg font-semibold mb-4">Konfirmasi Penghapusan</h2>
                            <p class="text-gray-700 dark:text-gray-300 mb-6">
                                Apakah Anda yakin ingin menghapus e-Book
                                <strong>{{ $ebook->judul }}</strong>?
                            </p>

                            <div class="flex justify-end gap-4">
                                <button @click="showModal = false"
                                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-500">
                                    Batal
                                </button>

                                <form action="{{ route('ebook.destroy', $ebook->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection