@extends('layouts.app')

@section('namePage', 'eBook')

@section('content')
    <div class="space-y-4">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md space-y-4">
            {{-- Tombol Kembali --}}
            <div>
                <a href="{{ route('ebook.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-100 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
                </a>
            </div>

            <div class="flex flex-col md:flex-row gap-6">
                {{-- Cover Image --}}
                <div class="w-full md:w-[300px] aspect-[3/4.5] rounded overflow-hidden shadow mx-auto md:mx-0">
                    <img src="{{ asset($ebook->cover) }}" alt="Cover {{ $ebook->title }}" class="w-full h-full object-cover" />
                </div>

                {{-- Detail --}}
                <div class="flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h1 class="text-2xl font-bold mb-2 text-center md:text-left text-gray-900 dark:text-white">
                            {{ $ebook->title }}</h1>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line text-justify mb-3">
                            {{ $ebook->deskripsi }}</p>

                        <div class="text-sm text-gray-800 dark:text-gray-200 space-y-1">
                            <p><strong>Dibuat {{ $ebook->created_at->diffForHumans() }}</strong></p>
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="space-y-4">
                        <div class="w-full flex flex-col md:flex-row gap-4 mt-4">
                            @auth
                                @php
                                    $role = Auth::user()->role ?? '';
                                @endphp

                                @if ($role === 'Admin')
                                    <a href="{{ route('ebook.edit', $ebook->slug) }}"
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
                                @else
                                    <a href="{{ asset($ebook->file) }}" target="_blank"
                                        class="flex-1 px-4 py-2 text-center bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center justify-center">
                                        <i class="fa-solid fa-download"></i>
                                        <span class="hidden md:inline ml-2 text-center">Download e-Book</span>
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center justify-between">
            <div class="flex items-center gap-5">
                <div class="bg-slate-200 dark:bg-gray-900 text-gray-800 dark:text-gray-100 p-4 rounded-full">
                    <i class="fa-regular fa-circle-question fa-3x"></i>
                </div>
                <h2 class="text-lg font-bold text-center">
                    @if ($ebook->postTestSessions->count())
                        @foreach ($ebook->postTestSessions as $session)
                            {{ $session->title }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    @else
                        Tidak ada Post-Test Session
                    @endif
                </h2>
            </div>
            <div>
                @auth
                    @if ($role === 'Admin')
                        <a href="{{ route('quiz.index', $ebook->slug) }}"
                            class="w-full flex-1 px-4 py-2 text-center bg-blue-600 text-white rounded hover:bg-blue-700 transition flex items-center justify-center">Tambah
                            Pertanyaan</a>
                    @else
                        <a href=""
                            class="w-full flex-1 px-4 py-2 text-center bg-green-600 text-white rounded hover:bg-green-700 transition flex items-center justify-center">Kerjakan
                            Post-Test</a>
                    @endif
                @endauth
            </div>
        </div> --}}

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md space-y-4">
            <embed src="{{ asset($ebook->file) }}" type="application/pdf" width="100%" height="1080px"
                class="rounded-lg border-gray-500 shadow-lg" />
        </div>
    </div>
@endsection
