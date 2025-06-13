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
                                @php $role = Auth::user()->role ?? ''; @endphp

                                @if ($role === 'Admin')
                                    <a href="{{ route('ebook.edit', $ebook->slug) }}"
                                        class="flex-1 px-4 py-2 text-center bg-green-600 text-white rounded hover:bg-green-700 transition flex items-center justify-center">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span class="hidden md:inline ml-2 text-center">Edit e-Book</span>
                                    </a>

                                    <button onclick="document.getElementById('modalDelete').classList.remove('hidden')"
                                        class="flex-1 px-4 py-2 text-center bg-red-600 text-white rounded hover:bg-red-700 transition flex items-center justify-center">
                                        <i class="fa-solid fa-trash"></i>
                                        <span class="hidden md:inline ml-2 text-center">Delete e-Book</span>
                                    </button>
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

        {{-- SECTION POST TEST --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md flex items-center justify-between">
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
                            class="px-4 py-2 text-center bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Tambah Pertanyaan
                        </a>
                    @else
                        <div class="flex items-center gap-5">
                            @if (session('info'))
                                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                                    class="text-xs bg-red-200 text-red-800 py-1 px-3 rounded-lg">
                                    {{ session('info') }}
                                </div>
                            @endif

                            @if (isset($result))
                                <div class="text-xs bg-green-200 text-green-800 py-1 px-3 rounded-lg">
                                    Skor: {{ $result->score }}/100
                                </div>
                                <button disabled class="px-4 py-2 bg-gray-400 text-white rounded cursor-not-allowed">
                                    Sudah Dikerjakan
                                </button>
                            @else
                                <a href="{{ route('posttest.show', ['slug' => $ebook->slug, 'session' => $session->id]) }}"
                                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                    Kerjakan Post-Test
                                </a>
                            @endif
                        </div>
                    @endif
                @endauth
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md space-y-4">
            <embed src="{{ asset($ebook->file) }}" type="application/pdf" width="100%" height="1080px"
                class="rounded-lg border-gray-500 shadow-lg" />
        </div>
    </div>

    {{-- MODAL DELETE --}}
    <div id="modalDelete"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            <!-- Header -->
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-red-500">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>Konfirmasi Hapus
                </h3>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus e-Book <strong>{{ $ebook->judul }}</strong>? Tindakan ini tidak
                    dapat dibatalkan.
                </p>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeModalDelete()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md dark:bg-gray-600 dark:text-white">
                    Batal
                </button>
                <form id="deleteForm" action="{{ route('ebook.destroy', $ebook->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openModalDelete() {
            document.getElementById('modalDelete').classList.remove('hidden');
            document.body.classList.add('overflow-hidden'); // mencegah scroll
        }

        function closeModalDelete() {
            document.getElementById('modalDelete').classList.add('hidden');
            document.body.classList.remove('overflow-hidden'); // izinkan scroll lagi
        }
    </script>
@endsection
