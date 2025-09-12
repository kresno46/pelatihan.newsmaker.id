@extends('layouts.app')

@section('namePage', 'Tambah Kuis')

@section('content')
    <form action="{{ route('posttest.store') }}" method="POST">
        @csrf
        <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Profile') }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Lengkapi dan perbarui data diri Anda agar informasi selalu akurat.') }}
                    </p>
                </div>

                <div class="flex items-center">
                    @if (session('status') === '')
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                            class="text-xs bg-green-100 dark:bg-green-200 text-green-800 py-1 px-3 rounded-lg mr-3">
                            {{ __('Post Test berhasil ditambahkan.') }}
                        </div>
                    @endif

                    <button type="submit"
                        class="bg-blue-500 px-4 py-2 text-sm w-full hover:bg-blue-600 text-white rounded transition-all text-center">Tambah</button>
                </div>
            </div>
        </header>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-md">
            <div class="w-full flex flex-col gap-5">
                <div class="w-full flex flex-col gap-2">
                    <label for="title" class="block font-medium text-gray-900 dark:text-gray-100">Judul Post Test</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 dark:border-red-400 @enderror">
                    @error('title')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full flex flex-col gap-2">
                    <label for="duration" class="block font-medium text-gray-900 dark:text-gray-100">Durasi (Menit)</label>
                    <input type="text" name="duration" id="duration" value="{{ old('duration') }}"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('duration') border-red-500 dark:border-red-400 @enderror">
                    @error('duration')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full flex flex-col gap-2">
                    <label for="status" class="block font-medium text-gray-900 dark:text-gray-100">Status</label>
                    <select name="status" id="status"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 dark:border-red-400 @enderror">
                        <option value="" disabled {{ old('status') ? '' : 'selected' }}>Pilih status</option>
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                    @error('status')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full flex flex-col gap-2">
                    <label for="tipe" class="block font-medium text-gray-900 dark:text-gray-100">Tipe Soal</label>
                    <select name="tipe" id="tipe"
                        class="w-full border rounded p-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 border-gray-400 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('tipe') border-red-500 dark:border-red-400 @enderror">
                        <option value="" disabled {{ old('tipe') ? '' : 'selected' }}>Pilih tipe</option>
                        <option value="PATD" {{ old('tipe') == 'PATD' ? 'selected' : '' }}>PATD</option>
                        <option value="PATL" {{ old('tipe') == 'PATL' ? 'selected' : '' }}>PATL</option>
                    </select>
                    @error('tipe')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </form>
@endsection
