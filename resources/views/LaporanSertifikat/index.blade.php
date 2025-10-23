@extends('layouts.app')

@section('namePage', 'Laporan Sertifikat')

@section('content')
    <div class="p-6 bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Laporan Sertifikat</h2>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    Halaman ini menampilkan daftar pengguna yang telah mengunduh sertifikat.
                </p>
            </div>

            @if (session('Alert'))
                <div class="text-center text-green-600 dark:text-green-400 font-semibold">{{ session('Alert') }}</div>
            @endif

            @if (session('error'))
                <div class="text-center text-red-600 dark:text-red-400 font-semibold">{{ session('error') }}</div>
            @endif
        </div>

        <hr class="border-gray-300 dark:border-gray-600 my-6">

        <!-- Search Form -->
        <div class="mb-6">
            <form method="GET" action="{{ route('LaporanSertifikat.index') }}">
                <input type="text" name="search" value="{{ old('search', $search) }}"
                    placeholder="Cari nama pengguna..."
                    class="p-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Cari
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-sm text-center table-auto">
                <thead class="bg-gray-600 text-white dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 rounded-l-lg">#</th>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Perusahaan</th>
                        <th class="px-4 py-3">Nilai</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3 rounded-r-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sertifikats as $index => $item)
                        @php
                            switch ($item->user->role) {
                                case 'Trainer (SGB)':
                                    $perusahaan = 'PT Solid Gold Berjangka';
                                    break;
                                case 'Trainer (RFB)':
                                    $perusahaan = 'PT Rifan Financindo Berjangka';
                                    break;
                                case 'Trainer (EWF)':
                                    $perusahaan = 'PT Equity World Futures';
                                    break;
                                case 'Trainer (BPF)':
                                    $perusahaan = 'PT Best Profit Futures';
                                    break;
                                case 'Trainer (KPF)':
                                    $perusahaan = 'PT Kontak Perkasa Futures';
                                    break;
                                default:
                                    $perusahaan = '-';
                                    break;
                            }
                        @endphp
                        <tr
                            class="{{ $loop->odd ? 'bg-white dark:bg-gray-800' : 'bg-gray-100 dark:bg-gray-900' }} border-b border-gray-300 dark:border-gray-700">
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-semibold">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->user->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $perusahaan }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $item->average_score }}/100</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($item->awarded_at)->translatedFormat('d F Y, H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                <button type="button"
                                    onclick="showDetailModal(
                                        '{{ $item->user->name }}',
                                        '{{ $perusahaan }}',
                                        '{{ $item->average_score }}',
                                        '{{ $item->certificate_uuid }}',
                                        '{{ \Carbon\Carbon::parse($item->awarded_at)->translatedFormat('d F Y, H:i') }}',
                                        '{{ $item->user->email }}',
                                        '{{ $item->user->cabang }}'
                                    )"
                                    class="text-blue-600 dark:text-blue-400 hover:underline transition-all duration-300"
                                    data-hs-overlay="#modalDetail">
                                    Detail
                                </button>
                                <span class="text-gray-500 dark:text-gray-400">|</span>
                                <button class="text-red-600 dark:text-red-400 hover:underline"
                                    onclick="showDeleteModal('{{ route('LaporanSertifikat.destroy', $item->id) }}')">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                Belum ada pengguna yang mengunduh sertifikat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination Links -->
            <div class="mt-6">
                {{ $sertifikats->links() }}
            </div>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="modalDetail"
        class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden bg-black bg-opacity-50 backdrop-blur-sm">
        <div class="relative p-4 w-full max-w-4xl mx-auto mt-10">
            <div
                class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t-xl border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.707 8.293-4-4a1 1 0 0 0-1.414 1.414L10.586 8H7a1 1 0 0 0 0 2h3.586l-1.293 1.293a1 1 0 1 0 1.414 1.414l4-4a1 1 0 0 0 0-1.414Z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Detail Sertifikat</h3>
                    </div>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition-colors"
                        onclick="closeModal('modalDetail')">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-6 md:p-8 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div
                            class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center space-x-4">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</p>
                                    <p class="text-base font-bold text-gray-900 dark:text-white" id="detailName"></p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center space-x-4">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                    <p class="text-base font-bold text-gray-900 dark:text-white" id="detailEmail"></p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center space-x-4">
                                <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2V6h10a2 2 0 0 0 2-2H4Zm2 6a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H6Zm10-2V6a4 4 0 0 0-4-4H4a4 4 0 0 0-4 4v4a4 4 0 0 0 4 4h8a4 4 0 0 0 4-4Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Perusahaan</p>
                                    <p class="text-base font-bold text-gray-900 dark:text-white" id="detailCompany"></p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center space-x-4">
                                <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Cabang</p>
                                    <p class="text-base font-bold text-gray-900 dark:text-white" id="detailCabang"></p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center space-x-4">
                                <svg class="w-8 h-8 text-red-600 dark:text-red-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nilai</p>
                                    <p class="text-base font-bold text-gray-900 dark:text-white" id="detailScore"></p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-xl shadow-lg">
                            <div class="flex items-center space-x-4">
                                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a1 1 0 0 0-1 1v1H6a1 1 0 0 0-1 1v1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-1V5a1 1 0 0 0-1-1h-2V3a1 1 0 0 0-1-1h-2Zm0 3V3h2v2H4V5a1 1 0 0 1 1-1h5Zm-1 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V9Z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</p>
                                    <p class="text-base font-bold text-gray-900 dark:text-white" id="detailDate"></p>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 p-6 rounded-xl shadow-lg col-span-2">
                            <div class="flex items-center space-x-4">
                                <svg class="w-8 h-8 text-pink-600 dark:text-pink-400" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 2a1 1 0 0 0-1 1v1H6a1 1 0 0 0-1 1v1H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1h-1V5a1 1 0 0 0-1-1h-2V3a1 1 0 0 0-1-1h-2Zm0 3V3h2v2H4V5a1 1 0 0 1 1-1h5Zm-1 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1V9Z" />
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">UUID</p>
                                    <p class="text-base font-mono font-bold text-gray-900 dark:text-white"
                                        id="detailUuid">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors"
                            onclick="closeModal('modalDetail')">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div id="modalDelete" class="fixed inset-0 z-50 hidden overflow-y-auto overflow-x-hidden bg-black bg-opacity-50">
        <div class="relative p-4 w-full max-w-md max-h-full mx-auto mt-20">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    onclick="closeModal('modalDelete')">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus
                        sertifikat ini?</h3>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600"
                            onclick="closeModal('modalDelete')">Batal</button>
                        <button type="submit"
                            class="ms-3 text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function showDetailModal(name, company, score, uuid, date, email, cabang) {
            document.getElementById('detailName').textContent = name;
            document.getElementById('detailCompany').textContent = company;
            document.getElementById('detailScore').textContent = score + '/100';
            document.getElementById('detailUuid').textContent = uuid;
            document.getElementById('detailDate').textContent = date;
            document.getElementById('detailEmail').textContent = email;
            document.getElementById('detailCabang').textContent = cabang;

            const modal = document.getElementById('modalDetail');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function showDeleteModal(actionUrl) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = actionUrl;

            const modal = document.getElementById('modalDelete');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('flex');
            document.getElementById(id).classList.add('hidden');
        }
    </script>
@endsection
