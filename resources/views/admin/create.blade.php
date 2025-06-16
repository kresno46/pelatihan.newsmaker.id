@extends('layouts.app')

@section('namePage', 'Tambah Admin')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Tambah Admin Baru</h1>

        <form id="adminForm" action="{{ route('admin.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-white">Nama Lengkap</label>
                <input type="text" name="name" id="name" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-white">Email</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-white">Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-6">
                <label for="password_confirmation"
                    class="block text-sm font-medium text-gray-700 dark:text-white">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="document.getElementById('modalBack').classList.remove('hidden')"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-sm rounded text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                    Batal
                </button>

                <button type="button" onclick="document.getElementById('modalSubmit').classList.remove('hidden')"
                    class="px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <!-- Modal Konfirmasi Batal -->
    <div id="modalBack"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-200 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95">
            <!-- Header -->
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-yellow-500 dark:text-yellow-400 flex items-center gap-3">
                    <i class="fa-solid fa-circle-question"></i>
                    <span>Konfirmasi Batal</span>
                </h3>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin membatalkan dan kembali ke halaman sebelumnya?
                </p>
            </div>

            <!-- Footer / Actions -->
            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="document.getElementById('modalBack').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition">
                    Tidak
                </button>
                <a href="{{ route('admin.index') }}"
                    class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition">
                    Ya, Kembali
                </a>
            </div>
        </div>
    </div>


    <!-- Modal Konfirmasi Simpan -->
    <div id="modalSubmit"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-200 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95">
            <!-- Header -->
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-red-500 dark:text-red-500 flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konfirmasi Simpan</span>
                </h3>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menyimpan data admin ini?
                </p>
            </div>

            <!-- Footer / Actions -->
            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="document.getElementById('modalSubmit').classList.add('hidden')"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition">
                    Batal
                </button>
                <button type="button" onclick="document.getElementById('adminForm').submit()"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md transition">
                    Ya, Simpan
                </button>
            </div>
        </div>
    </div>
@endsection
