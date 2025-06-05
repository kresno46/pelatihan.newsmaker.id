@extends('layouts.app')

@section('namePage', 'Dashboard Admin')

@section('content')
    <h1 class="text-3xl font-semibold mb-2">Dashboard</h1>
    <p class="mb-6">Selamat datang kembali, {{ Auth::user()->name }}!</p>

    @if ($isIncomplete)
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold"><i class="fa-solid fa-triangle-exclamation"></i> Perhatian!</strong>
            <span class="block sm:inline">Lengkapi data diri Anda untuk pengalaman yang lebih baik.</span>
            <a href="{{ route('profile.edit') }}" class="underline text-yellow-700 ml-2">Klik di sini</a>
        </div>
    @endif


    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 border-l-4 border-blue-500 rounded-lg shadow">
            <div class="flex items-center p-5">
                <!-- Icon -->
                <div class="text-blue-500 px-6 py-5 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <i class="fa-solid fa-book text-2xl"></i>
                </div>

                <!-- Content -->
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jumlah e-Book</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Deskripsi atau keterangan tambahan bisa ditulis di
                        sini.</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 border-l-4 border-red-500 rounded-lg shadow">
            <div class="flex items-center p-5">
                <!-- Icon -->
                <div class="text-red-500 px-6 py-5 bg-red-100 dark:bg-red-900 rounded-full">
                    <i class="fa-solid fa-book text-2xl"></i>
                </div>

                <!-- Content -->
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jumlah Kuis</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Deskripsi atau keterangan tambahan bisa ditulis di
                        sini.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
