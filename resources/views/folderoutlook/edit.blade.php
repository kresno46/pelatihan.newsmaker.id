@extends('layouts.app')

@section('namePage', $FolderOutlook->folder_name)

@section('content')
    <div class="card p-6 bg-white dark:bg-gray-800 rounded shadow">

        <div class="space-y-5">
            <div class="text-lg font-semibold">
                Edit folder "{{ $FolderOutlook->folder_name }}"
            </div>

            <hr>

            <form id="updateForm" method="POST" action="{{ route('outlookfolder.update', $FolderOutlook->id) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Cover Folder --}}
                <div class="flex flex-col md:flex-row items-center gap-5 mb-4">
                    <div class="w-24 h-24 bg-green-300 dark:bg-green-800 rounded-full overflow-hidden flex-shrink-0">
                        <img src="{{ asset($FolderOutlook->cover_folder) }}" alt="{{ $FolderOutlook->folder_name }}"
                            class="w-full h-full object-cover object-center">
                    </div>
                    <div class="w-full">
                        <label for="cover_folder" class="block text-sm font-medium mb-1">
                            Icon Folder
                        </label>
                        <input type="file" id="cover_folder" name="cover_folder"
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring dark:bg-gray-800 focus:ring-blue-200 focus:outline-none
                                  @error('folder_name') border-red-500 @enderror"
                            value="{{ old('cover_folder', $FolderOutlook->cover_folder ?? '') }}" required>
                        @error('cover_folder')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Folder Name --}}
                <div class="mb-4">
                    <label for="folder_name" class="block text-sm font-medium mb-1">
                        Folder Name
                    </label>
                    <input type="text" id="folder_name" name="folder_name"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring dark:bg-gray-800 focus:ring-blue-200 focus:outline-none
                                  @error('folder_name') border-red-500 @enderror"
                        value="{{ old('folder_name', $FolderOutlook->folder_name ?? '') }}" required>
                    @error('folder_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Category --}}
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium mb-1">
                        Category
                    </label>
                    <select id="category" name="category"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring dark:bg-gray-800 focus:ring-blue-200 focus:outline-none
                                  @error('category') border-red-500 @enderror"
                        required>
                        <option value="daily"
                            {{ old('category', $FolderOutlook->category ?? 'daily') == 'daily' ? 'selected' : '' }}>Daily
                        </option>
                        <option value="weekly"
                            {{ old('category', $FolderOutlook->category ?? 'daily') == 'weekly' ? 'selected' : '' }}>Weekly
                        </option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="mb-4">
                    <label for="deskripsi" class="block text-sm font-medium mb-1">
                        Description
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring focus:ring-blue-200 dark:bg-gray-800 focus:outline-none
                                     @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $FolderOutlook->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Buttons --}}
                <div class="flex items-center space-x-3">
                    <button type="button" onclick="openModalUpdate()"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Simpan Pembaharuan
                    </button>
                    <button type="button" onclick="openModalBack()"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Confirm Update --}}
    <div id="modalUpdate"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center px-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-blue-500">
                    <i class="fa-solid fa-circle-info mr-2"></i> Konfirmasi Simpan
                </h3>
            </div>

            <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menyimpan perubahan pada folder ini?
                </p>
            </div>

            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeModalUpdate()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md dark:bg-gray-600 dark:text-white">
                    Batal
                </button>
                <button type="submit" form="updateForm"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md">
                    Simpan
                </button>
            </div>
        </div>
    </div>

    {{-- Modal Confirm Back --}}
    <div id="modalBack"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center px-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-red-500">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> Batal Edit?
                </h3>
            </div>

            <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Perubahan Anda belum disimpan. Apakah Anda yakin ingin kembali?
                </p>
            </div>

            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeModalBack()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md dark:bg-gray-600 dark:text-white">
                    Tidak
                </button>
                <a href="{{ route('outlookfolder.index') }}"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                    Ya, Kembali
                </a>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        function openModalUpdate() {
            document.getElementById('modalUpdate').classList.remove('hidden');
        }

        function closeModalUpdate() {
            document.getElementById('modalUpdate').classList.add('hidden');
        }

        function openModalBack() {
            document.getElementById('modalBack').classList.remove('hidden');
        }

        function closeModalBack() {
            document.getElementById('modalBack').classList.add('hidden');
        }
    </script>
@endsection
