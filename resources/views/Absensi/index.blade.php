@extends('layouts.app')

@section('namePage', 'Absensi')

@section('content')
    <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow-lg space-y-5">
        <div class="flex items-center justify-between">
            <!-- Tombol Kembali (khusus mobile) -->
            <a href="{{ route('dashboard') }}"
                class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-600 transition cursor-pointer sm:w-auto">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Sesi Absensi</h3>
            @if (session('Alert'))
                <div class="rounded-full bg-green-300/50 dark:bg-green-300 px-4 py-1" x-data="{ show: true }" x-show="show"
                    x-transition x-init="setTimeout(() => show = false, 5000)">
                    <span class="text-sm italic text-green-700">{{ session('Alert') }}</span>
                </div>
            @endif
        </div>

        <hr class="border bg-gray-500 dark:bg-gray-600">

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse ($jadwals as $jadwal)
                <div
                    class="bg-white dark:bg-gray-900 p-5 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 flex flex-col justify-between h-full">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-semibold text-sm text-gray-800 dark:text-white">{{ $jadwal->title }}</h2>
                            <div class="text-gray-500 dark:text-gray-400 text-xs">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                            </div>
                        </div>
                        @if (in_array($jadwal->id, $absensiUser))
                            <p class="text-green-500 text-sm italic">Sudah Absen</p>
                        @else
                            <p class="text-red-500 text-sm italic">Belum Absen</p>
                        @endif
                    </div>

                    <div class="mt-4">
                        @if ($jadwal->is_open)
                            @if (in_array($jadwal->id, $absensiUser))
                                <div
                                    class="block w-full text-center py-2 text-sm bg-gray-500 text-white rounded cursor-not-allowed opacity-70">
                                    ✅ <span class="italic">Absen Terisi</span>
                                </div>
                            @else
                                <button data-jadwal-id="{{ $jadwal->id }}" data-jadwal-title="{{ $jadwal->title }}"
                                    onclick="bukaModalAbsensi(this)"
                                    class="block w-full text-center py-2 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-300">
                                    Isi Absensi
                                </button>
                            @endif
                        @else
                            <div
                                class="block w-full text-center py-2 text-sm bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-300 rounded cursor-not-allowed">
                                Sesi Ditutup
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center text-gray-500 dark:text-gray-400">Belum ada sesi absensi tersedia.</div>
            @endforelse

            <!-- Modal Form Absensi -->
            <div id="modalAbsensi"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white dark:bg-gray-900 p-6 rounded shadow-lg w-full max-w-md">
                    <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Isi Absensi</h2>

                    <form method="POST" action="{{ route('AbsensiUser.store') }}">
                        @csrf

                        <input type="hidden" name="jadwal_id" id="modalJadwalId">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <input type="hidden" name="waktu_absen" value="{{ now() }}">

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama</label>
                            <input type="text"
                                class="w-full px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                                value="{{ auth()->user()->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Sesi</label>
                            <input type="text" id="modalJadwalTitle"
                                class="w-full px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                                readonly>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Waktu Absen</label>
                            <input type="text"
                                class="w-full px-3 py-2 border rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                                value="{{ now()->format('d M Y H:i:s') }}" readonly>
                        </div>

                        <div class="flex justify-end space-x-2">
                            <button type="button" onclick="tutupModalAbsensi()"
                                class="px-4 py-2 text-sm bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 text-sm bg-green-600 text-white rounded hover:bg-green-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function bukaModalAbsensi(button) {
            const modal = document.getElementById('modalAbsensi');
            document.getElementById('modalJadwalId').value = button.dataset.jadwalId;
            document.getElementById('modalJadwalTitle').value = button.dataset.jadwalTitle;
            modal.classList.remove('hidden');
        }

        function tutupModalAbsensi() {
            document.getElementById('modalAbsensi').classList.add('hidden');
        }
    </script>
@endsection
