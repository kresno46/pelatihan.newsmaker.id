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
                                        '{{ \Carbon\Carbon::parse($item->awarded_at)->translatedFormat('d F Y, H:i') }}'
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

    <!-- Modal Detail and Delete Modal as before -->
@endsection

@section('scripts')
    <script>
        function showDetailModal(name, company, score, uuid, date) {
            document.getElementById('detailName').textContent = name;
            document.getElementById('detailCompany').textContent = company;
            document.getElementById('detailScore').textContent = score + '/100';
            document.getElementById('detailUuid').textContent = uuid;
            document.getElementById('detailDate').textContent = date;

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
