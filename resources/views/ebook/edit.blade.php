@extends('layouts.app')

@section('namePage', 'eBook')

@section('content')
    <div class="card p-6 bg-white dark:bg-gray-800 rounded shadow">
        <form id="pivotForm" action="{{ route('ebook.update', [$folder->slug, $ebook->slug]) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="flex justify-between items-center mb-5">
                <button type="button" onclick="openBackModal()"
                    class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition">
                    <i class="fa-solid fa-chevron-left"></i>
                    <span class="hidden md:block">Kembali</span>
                </button>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Buku</h2>

                <button type="button" onclick="openSubmitModal()"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold transition">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span class="hidden md:block">Perbarui</span>
                </button>
            </div>

            <div class="space-y-5">
                {{-- Judul --}}
                <div class="w-full">
                    <label for="title" class="block font-medium text-gray-900 dark:text-gray-100">Judul Buku</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $ebook->title) }}"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 dark:border-red-400 @enderror">
                    @error('title')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    {{-- Cover --}}
                    <div class="w-full">
                        <label for="cover" class="block font-medium text-gray-900 dark:text-gray-100">Gambar Cover
                            Buku</label>
                        <input type="file" name="cover" id="cover"
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cover') border-red-500 dark:border-red-400 @enderror">
                        @error('cover')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($ebook->cover)
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">File saat ini:
                                {{ basename($ebook->cover) }}</p>
                        @endif
                    </div>

                    {{-- File eBook --}}
                    <div class="w-full">
                        <label for="file" class="block font-medium text-gray-900 dark:text-gray-100">File e-Book
                            (opsional)</label>
                        <input type="file" name="file" id="file"
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('file') border-red-500 dark:border-red-400 @enderror">
                        @error('file')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @if ($ebook->file)
                            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">File saat ini:
                                {{ basename($ebook->file) }}</p>
                        @endif
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="w-full">
                    <label for="deskripsi" class="block font-medium text-gray-900 dark:text-gray-100">Deskripsi Buku</label>
                    <textarea name="deskripsi" id="deskripsi" rows="10"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 dark:border-red-400 @enderror">{{ old('deskripsi', $ebook->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </form>
    </div>

    {{-- Modal Konfirmasi Perbarui --}}
    <div id="submitModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            <!-- Header -->
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-blue-600 dark:text-blue-400 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>Konfirmasi Perbarui</span>
                </h3>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 border-t border-b border-gray-200 dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin memperbarui buku ini?
                </p>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeSubmitModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition">
                    Batal
                </button>
                <button onclick="submitForm()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                    Ya, Perbarui
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Kembali --}}
    <div id="backModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            <!-- Header -->
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-red-500 dark:text-red-400 flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konfirmasi Kembali</span>
                </h3>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 border-t border-b border-gray-200 dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin kembali? Perubahan yang belum disimpan akan hilang.
                </p>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeBackModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition">
                    Batal
                </button>
                <a href="{{ route('ebook.show', [$folder->slug, $ebook->slug]) }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                    Ya, Kembali
                </a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openSubmitModal() {
            document.getElementById('submitModal').classList.remove('hidden');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }

        function submitForm() {
            document.getElementById('pivotForm').submit();
        }

        function openBackModal() {
            document.getElementById('backModal').classList.remove('hidden');
        }

        function closeBackModal() {
            document.getElementById('backModal').classList.add('hidden');
        }
    </script>
@endsection
