@extends('layouts.app')

@section('namePage', 'eBook')

@section('content')
    <div class="card p-6 bg-white dark:bg-gray-800 rounded shadow">
        <form id="pivotForm" action="{{ route('ebook.store') }}" method="POST" enctype="multipart/form-data" class="">
            @csrf

            <div class="flex justify-between items-center mb-5">
                <button type="button" onclick="openBackModal()"
                    class="inline-flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 py-2 px-6 rounded-lg text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition">
                    <i class="fa-solid fa-chevron-left"></i>
                    <span class="hidden md:block">Kembali</span>
                </button>

                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Tambah Buku</h2>

                <button type="button" onclick="openSubmitModal()"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-semibold transition">
                    <i class="fa-solid fa-plus"></i>
                    <span class="hidden md:block">Tambah</span>
                </button>
            </div>

            <div class="space-y-5">
                {{-- Title --}}
                <div class="w-full">
                    <label for="title" class="block font-medium text-gray-900 dark:text-gray-100">Judul Buku</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 dark:border-red-400 @enderror">
                    @error('title')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    {{-- Cover --}}
                    <div class="w-full">
                        <label for="cover" class="block font-medium text-gray-900 dark:text-gray-100">Gambar cover
                            buku</label>
                        <input type="file" name="cover" id="cover" required
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('cover') border-red-500 dark:border-red-400 @enderror">
                        @error('cover')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- File eBook --}}
                    <div class="w-full">
                        <label for="file" class="block font-medium text-gray-900 dark:text-gray-100">File e-Book</label>
                        <input type="file" name="file" id="file" required
                            class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('file') border-red-500 dark:border-red-400 @enderror">
                        @error('file')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="w-full">
                    <label for="deskripsi" class="block font-medium text-gray-900 dark:text-gray-100">Deskripsi Buku</label>
                    <textarea name="deskripsi" id="deskripsi" rows="10"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deskripsi') border-red-500 dark:border-red-400 @enderror">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </form>
    </div>

    {{-- Modal Konfirmasi Simpan --}}
    <div id="submitModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden px-5">
        <div class="bg-white dark:bg-gray-800 rounded p-6 w-full max-w-md text-gray-900 dark:text-gray-100">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Simpan</h3>
            <p class="mb-6">Apakah Anda yakin ingin menyimpan buku ini?</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeSubmitModal()"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">Batal</button>
                <button onclick="submitForm()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded transition">Ya, Simpan</button>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Kembali --}}
    <div id="backModal" class="fixed inset-0 bg-black/50 backdrop-blur flex items-center justify-center hidden px-5">
        <div class="bg-white dark:bg-gray-800 rounded p-6 w-full max-w-md text-gray-900 dark:text-gray-100">
            <h3 class="text-lg font-semibold mb-4">Konfirmasi Kembali</h3>
            <p class="mb-6">Apakah Anda yakin ingin kembali? Data yang belum disimpan akan hilang.</p>
            <div class="flex justify-end gap-4">
                <button onclick="closeBackModal()"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">Batal</button>
                <a href="{{ route('ebook.index') }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded transition">Ya, Kembali</a>
            </div>
        </div>
    </div>

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
