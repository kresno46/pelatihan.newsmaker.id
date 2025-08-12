@extends('layouts.app')

@section('namePage', 'Dashboard')

@section('content')
    <h1 class="text-3xl font-semibold mb-2">Dashboard</h1>
    <p class="mb-6">Selamat datang kembali, {{ Auth::user()->name }}!</p>

    @if ($isIncomplete)
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 rounded mb-6 flex items-center" role="alert">
            <div class="p-3 bg-yellow-400">
                <strong class="font-bold">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </strong>
            </div>
            <div class="ps-3">
                <span class="block sm:inline">
                    Lengkapi data diri Anda untuk pengalaman yang lebih baik.
                    <a href="{{ route('profile.edit') }}" class="underline text-yellow-700 ml-2">Klik di sini</a>
                </span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Jumlah Pelatihan -->
        <div class="bg-white dark:bg-gray-800 border-l-4 border-emerald-500 rounded-lg shadow">
            <div class="flex items-center p-5">
                <div class="text-emerald-500 px-6 py-5 bg-emerald-100 dark:bg-emerald-900 rounded-full">
                    <i class="fa-solid fa-certificate text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jumlah Pelatihan Tersedia</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $jumlahPelatihan }} Pelatihan</p>
                </div>
            </div>
        </div>

        <!-- Jumlah e-Book -->
        <div class="bg-white dark:bg-gray-800 border-l-4 border-blue-500 rounded-lg shadow">
            <div class="flex items-center p-5">
                <div class="text-blue-500 px-6 py-5 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <i class="fa-solid fa-book text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jumlah e-Book</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $jumlahEbook }} eBook</p>
                </div>
            </div>
        </div>

        <!-- Jumlah Kuis -->
        <div class="bg-white dark:bg-gray-800 border-l-4 border-red-500 rounded-lg shadow">
            <div class="flex items-center p-5">
                <div class="text-red-500 px-6 py-5 bg-red-100 dark:bg-red-900 rounded-full">
                    <i class="fa-solid fa-question text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jumlah Kuis</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $jumlahSession }} Kuis</p>
                </div>
            </div>
        </div>

        <!-- Kuis Sudah Dikerjakan -->
        <div class="bg-white dark:bg-gray-800 border-l-4 border-green-500 rounded-lg shadow">
            <div class="flex items-center p-5">
                <div class="text-green-500 px-6 py-5 bg-green-100 dark:bg-green-900 rounded-full">
                    <i class="fa-solid fa-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Kuis Dikerjakan</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $riwayatUserLogin }} Kuis</p>
                </div>
            </div>
        </div>

        <!-- Sertifkat Sudah Dikerjakan -->
        <div class="bg-white dark:bg-gray-800 border-l-4 border-orange-500 rounded-lg shadow">
            <div class="flex items-center p-5">
                <div class="text-orange-500 px-6 py-5 bg-orange-100 dark:bg-orange-900 rounded-full">
                    <i class="fa-solid fa-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Sertifikat di download</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $jumlahSertifikatSelesai }} Kuis</p>
                </div>
            </div>
        </div>

        <!-- Jumlah Absensi -->
        <div class="bg-white dark:bg-gray-800 border-l-4 border-pink-500 rounded-lg shadow">
            <div class="flex items-center p-5">
                <div class="text-pink-500 px-6 py-5 bg-pink-100 dark:bg-pink-900 rounded-full">
                    <i class="fa-solid fa-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jumlah Daftar Absensi</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $jumlahJadwalAbsensi }} Jadwal Absensi</p>
                </div>
            </div>
        </div>

        <!-- Absensi terisi -->
        <div class="bg-white dark:bg-gray-800 border-l-4 border-violet-500 rounded-lg shadow">
            <div class="flex items-center p-5">
                <div class="text-violet-500 px-6 py-5 bg-violet-100 dark:bg-violet-900 rounded-full">
                    <i class="fa-solid fa-check text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Mengisi Absensi</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $jumlahAbsensiTerisi }} Terisi</p>
                </div>
            </div>
        </div>

        @if (Auth::check() && Auth::user()->role === 'Admin')
            <!-- Jumlah User -->
            <div class="bg-white dark:bg-gray-800 border-l-4 border-purple-500 rounded-lg shadow">
                <div class="flex items-center p-5">
                    <div class="text-purple-500 px-6 py-5 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <i class="fa-solid fa-users text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jumlah User</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $jumlahUser }} User</p>
                    </div>
                </div>
            </div>

            <!-- Jumlah Admin -->
            <div class="bg-white dark:bg-gray-800 border-l-4 border-indigo-500 rounded-lg shadow md:col-span-1">
                <div class="flex items-center p-5">
                    <div class="text-indigo-500 px-6 py-5 bg-indigo-100 dark:bg-indigo-900 rounded-full">
                        <i class="fa-solid fa-user-shield text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Jumlah Admin</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $jumlahAdmin }} Admin</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
