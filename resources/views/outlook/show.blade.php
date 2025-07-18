@extends('layouts.app')

@section('namePage', $outlook->title)

@section('content')

    @php $role = Auth::user()->role ?? ''; @endphp

    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <div class="space-y-5">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-5">
                    <a href="{{ route('outlook.index', $folder->slug) }}"
                        class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition cursor-pointer inline-block">
                        <span><i class="fa-solid fa-xmark"></i></span>
                    </a>
                </div>
                @if ($role === 'Admin')
                    <div class="flex items-center gap-3">
                        <a href="{{ route('outlook.edit', ['slug' => $folder->slug, 'outlookSlug' => $outlook->slug]) }}"
                            class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-600 transition cursor-pointer inline-block">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-file-pen"></i> <span class="hidden md:block">Edit</span>
                            </div>
                        </a>
                        <button type="button" onclick="openModalDelete()"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition cursor-pointer inline-block">
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-trash"></i> <span class="hidden md:block">Hapus</span>
                            </div>
                        </button>
                    </div>
                @endif
            </div>

            <div class="flex flex-col md:flex-row gap-5">
                <img src="{{ asset($outlook->cover) }}" alt="{{ $outlook->title }}"
                    class="border border-gray-400 rounded-lg overflow-hidden">

                <div class="w-full space-y-5">
                    <div>
                        <label class="text-gray-700 dark:text-gray-300">Judul:</label>
                        <div class="bg-gray-200 dark:bg-gray-700 w-full rounded-lg p-3 text-gray-800 dark:text-gray-100">
                            <p>{{ $outlook->title }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="text-gray-700 dark:text-gray-300">Deskripsi:</label>
                        <div class="bg-gray-200 dark:bg-gray-700 w-full rounded-lg p-3 text-gray-800 dark:text-gray-100">
                            <p>{{ $outlook->deskripsi }}</p>
                        </div>
                    </div>
                    <a href="{{ asset($outlook->file) }}" target="_blank" rel="noopener noreferrer"
                        class="bg-blue-500 hover:bg-blue-600 py-3 w-full text-center text-white rounded-lg block transition-all duration-300">
                        <i class="fa-solid fa-download me-2"></i> Download File
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div id="modalDelete"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-red-500">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>Konfirmasi Hapus
                </h3>
            </div>

            <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus Outlook "<strong>{{ $outlook->title }}</strong>"? Tindakan ini tidak
                    dapat
                    dibatalkan.
                </p>
            </div>

            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeModalDelete()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md dark:bg-gray-600 dark:text-white">
                    Batal
                </button>
                <form id="deleteForm"
                    action="{{ route('outlook.destroy', ['slug' => $folder->slug, 'outlookSlug' => $outlook->slug]) }}"
                    method="POST">
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
        }

        function closeModalDelete() {
            document.getElementById('modalDelete').classList.add('hidden');
        }
    </script>
@endsection
