@extends('layouts.app')

@section('namePage', 'Detail ' . $trainer->name)

@section('content')
    <div class="w-full">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-5 sm:p-8 mb-6 sm:mb-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">Detail Trainer</h1>
                    <p class="text-blue-100 text-base sm:text-lg">{{ $trainer->name ?? '-' }}</p>
                    <div class="flex flex-wrap items-center mt-3 gap-2">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm">
                            @switch($trainer->jabatan)
                                @case('BC')
                                    Business Consultant
                                @break

                                @case('SBC')
                                    Senior Business Consultant
                                @break

                                @case('BSM')
                                    Business Support Manager
                                @break

                                @case('BM')
                                    Branch Manager
                                @break

                                @default
                                    -
                            @endswitch
                        </span>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="w-20 h-20 lg:w-24 lg:h-24 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-user text-4xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Cards -->
        <div class="grid lg:grid-cols-3 gap-6 md:gap-8">
            <!-- Personal Information -->
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-user-circle mr-3"></i>
                            Informasi Pribadi
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="grid md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nama
                                        Lengkap</label>
                                    <p class="text-gray-900 dark:text-white font-semibold text-base sm:text-lg">
                                        {{ $trainer->name ?? '-' }}</p>
                                </div>
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Email</label>
                                    <p class="text-gray-900 dark:text-white">{{ $trainer->email ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Jenis
                                        Kelamin</label>
                                    <p class="text-gray-900 dark:text-white">{{ $trainer->jenis_kelamin ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tempat
                                        Lahir</label>
                                    <p class="text-gray-900 dark:text-white">{{ $trainer->tempat_lahir ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tanggal
                                        Lahir</label>
                                    <p class="text-gray-900 dark:text-white">
                                        {{ $trainer->tanggal_lahir ? \Carbon\Carbon::parse($trainer->tanggal_lahir)->format('d M Y') : '-' }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status
                                        Verifikasi Email</label>
                                    <div class="flex flex-wrap items-center mt-2 gap-2">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ is_null($trainer->email_verified_at) ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            <i
                                                class="fas {{ is_null($trainer->email_verified_at) ? 'fa-clock' : 'fa-check-circle' }} mr-1"></i>
                                            {{ is_null($trainer->email_verified_at) ? 'Belum Terverifikasi' : 'Terverifikasi' }}
                                        </span>
                                        @if (!is_null($trainer->email_verified_at))
                                            <span
                                                class="text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($trainer->email_verified_at)->format('d M Y H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status
                                        Akun</label>
                                    <div class="flex flex-wrap items-center mt-2 gap-2">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $trainer->suspended_at ? 'bg-red-100 text-red-800' : 'bg-emerald-100 text-emerald-800' }}">
                                            <i class="fas {{ $trainer->suspended_at ? 'fa-ban' : 'fa-check' }} mr-1"></i>
                                            {{ $trainer->suspended_at ? 'Suspended' : 'Aktif' }}
                                        </span>
                                        @if ($trainer->suspended_at)
                                            <span
                                                class="text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($trainer->suspended_at)->format('d M Y H:i') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information & Actions -->
            <div class="space-y-6">
                <!-- Professional Info Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-briefcase mr-3"></i>
                            Profesional
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Alamat</label>
                            <p class="text-gray-900 dark:text-white">{{ $trainer->alamat ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">No.
                                Telepon</label>
                            <p class="text-gray-900 dark:text-white">{{ $trainer->no_tlp ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Cabang</label>
                            <p class="text-gray-900 dark:text-white">{{ $trainer->cabang ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Bergabung
                                Sejak</label>
                            <p class="text-gray-900 dark:text-white">
                                {{ $trainer->created_at ? $trainer->created_at->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Login Terakhir</label>
                            <p class="text-gray-900 dark:text-white">
                                {{ $trainer->last_login_at ? \Carbon\Carbon::parse($trainer->last_login_at)->translatedFormat('d F Y H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6">
                        <h3 class="text-xl font-semibold text-white flex items-center">
                            <i class="fas fa-bolt mr-3"></i>
                            Aksi Cepat
                        </h3>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3">
                        @if (is_null($trainer->email_verified_at))
                            <form method="POST" action="{{ route('trainer.verify', $trainer->id) }}" class="block">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Verifikasi Email
                                </button>
                            </form>
                        @endif
                        @if ($trainer->role !== 'Admin' && auth()->user()->id !== $trainer->id)
                            @if ($trainer->suspended_at)
                                <form method="POST" action="{{ route('trainer.unsuspend', $trainer->id) }}" class="block">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Aktifkan kembali akun {{ $trainer->name }}?')"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                                        <i class="fas fa-unlock mr-2"></i>
                                        Unsuspend
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('trainer.suspend', $trainer->id) }}" class="block">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Suspend akun {{ $trainer->name }}? User wajib reset password.')"
                                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                                        <i class="fas fa-ban mr-2"></i>
                                        Suspend
                                    </button>
                                </form>
                            @endif
                        @endif
                        <a href="{{ route('trainer.edit', $trainer->id) }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                            <i class="fas fa-edit mr-2"></i>
                            Edit Profil
                        </a>
                        <a href="{{ route('trainer.index') }}"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
