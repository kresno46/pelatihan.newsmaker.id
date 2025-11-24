@extends('layouts.app')

@section('namePage', 'Absensi')

@section('content')
    <div>
        <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow-lg space-y-5">
            {{-- Header --}}
            <div class="flex items-center justify-between w-full">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Absensi</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Kelola sesi absensi dan post test. Setiap sesi post test memiliki durasi 30 menit untuk
                        menyelesaikan 20 pertanyaan.
                    </p>
                </div>

                <a href="{{ route('absensi.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200">
                    <i class="fa-solid fa-plus mr-2"></i>Tambah Sesi
                </a>
            </div>

            @if (session('Alert'))
                <div class="text-green-700 text-sm">
                    {{ session('Alert') }}
                </div>
            @endif

            {{-- Pemisah --}}
            <hr class="border bg-gray-500 dark:bg-gray-700">

            {{-- Search Bar --}}
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <form method="GET" action="{{ route('absensi.index') }}" class="flex items-center">
                        <div class="relative flex-1">
                            <input type="text" name="search" value="{{ $search ?? '' }}"
                                placeholder="Cari jadwal absensi..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fa-solid fa-search text-gray-400"></i>
                            </div>
                        </div>
                        <button type="submit"
                            class="ml-2 px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200">
                            Cari
                        </button>
                        @if ($search ?? false)
                            <a href="{{ route('absensi.index') }}"
                                class="ml-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            {{-- Body --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                {{-- Loop Jadwal --}}
                @foreach ($jadwals as $jadwal)
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex flex-col justify-between mb-2 h-full">
                            <div>
                                <h2 class="font-semibold text-sm text-gray-900 dark:text-white">{{ $jadwal->title }}</h2>
                                <div class="text-gray-600 dark:text-gray-300 text-xs mt-1">
                                    <strong class="text-blue-500">Sesi Post Test:</strong>
                                    {{ $jadwal->postTestSession->title }}<br>
                                    <strong class="text-blue-500">Durasi Test:</strong>
                                    {{ $jadwal->postTestSession->duration ?? '30 menit' }} menit
                                    ({{ $jadwal->postTestSession->questions_count ?? '20' }} pertanyaan)
                                </div>
                            </div>
                            <div class="text-gray-500 dark:text-gray-400 text-xs">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-3">
                            <form action="{{ route('absensi.toggle', $jadwal->id) }}" method="POST" class="h-fit">
                                @csrf
                                <label class="relative inline-block w-12 h-6 cursor-pointer">
                                    <input type="checkbox" name="is_open" onchange="this.form.submit()" class="sr-only peer"
                                        {{ $jadwal->is_open ? 'checked' : '' }}>
                                    <div
                                        class="w-full h-full bg-gray-300 dark:bg-gray-600 rounded-full peer-checked:bg-green-500 transition-colors duration-300">
                                    </div>
                                    <div
                                        class="absolute top-0.5 left-0.5 w-5 h-5 bg-white dark:bg-gray-100 rounded-full transition-transform duration-300 transform peer-checked:translate-x-6">
                                    </div>
                                </label>
                            </form>

                            <div class="flex items-center gap-4">
                                <a href="{{ route('absensiAdmin.index', $jadwal->id) }}" class="text-blue-500"
                                    title="Lihat List Absen">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                <a href="{{ route('absensi.edit', $jadwal->id) }}" class="text-yellow-500">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <button type="button" class="text-red-500"
                                    onclick="document.getElementById('modalDelete-{{ $jadwal->id }}').classList.remove('hidden')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="modalDelete-{{ $jadwal->id }}"
                        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center px-4">
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
                            <div class="px-6 py-5">
                                <h3 class="text-xl font-bold text-red-500">
                                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>Konfirmasi Hapus
                                </h3>
                            </div>

                            <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                                <p class="text-gray-700 dark:text-gray-300">
                                    Apakah Anda yakin ingin menghapus sesi
                                    "<strong>{{ $jadwal->title }}</strong>"?
                                    Tindakan ini tidak dapat dibatalkan.
                                </p>
                            </div>

                            <div class="flex justify-end gap-3 px-6 py-4">
                                <button
                                    onclick="document.getElementById('modalDelete-{{ $jadwal->id }}').classList.add('hidden')"
                                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md dark:bg-gray-600 dark:text-white">
                                    Batal
                                </button>
                                <form action="{{ route('absensi.destroy', $jadwal->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
