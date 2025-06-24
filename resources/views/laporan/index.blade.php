@extends('layouts.app')

@section('namePage', 'Laporan Post Test')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between mb-5">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Laporan Post Test</h1>

            <div class="flex items-center gap-3">
                @if (session('Alert'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-200 dark:bg-green-900 text-green-800 dark:text-green-200 py-1 px-3 rounded-lg">
                        {{ session('Alert') }}
                    </div>
                @endif

                {{-- Search Bar --}}
                <form method="GET" action="{{ route('laporan.index') }}" class="relative w-full max-w-sm">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full pl-10 pr-4 py-2 text-sm border rounded-lg bg-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Cari berdasarkan nama, ebook, sesi...">
                </form>

                <a href="{{ route('laporan.index') }}"
                    class="bg-red-500 py-2 px-4 rounded-lg hover:bg-red-600 font-medium text-gray-100">Reset</a>
            </div>
        </div>

        <hr class="border-gray-300 dark:border-gray-600">

        <div
            class="overflow-x-auto bg-white dark:bg-gray-700 rounded-lg mt-5 shadow-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">#</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Nama</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">eBook</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Post Test
                        </th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Score</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                            Dibuat</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($laporans as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $item->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-center text-gray-800 dark:text-gray-200">
                                {{ $item->ebook->title }}</td>
                            <td class="px-6 py-4 text-sm text-center text-gray-800 dark:text-gray-200">
                                {{ $item->session->title }}</td>
                            <td class="px-6 py-4 text-sm text-center">
                                @php
                                    $score = $item->score;
                                    if ($score >= 85) {
                                        $badgeColor =
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                    } elseif ($score >= 70) {
                                        $badgeColor = 'bg-lime-100 text-lime-800 dark:bg-lime-900 dark:text-lime-200';
                                    } elseif ($score >= 50) {
                                        $badgeColor =
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                                    } elseif ($score >= 25) {
                                        $badgeColor =
                                            'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200';
                                    } else {
                                        $badgeColor = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                    }
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                    {{ $score }}/100
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-800 dark:text-gray-200">
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center flex gap-2 justify-center">
                                <a href="{{ route('laporan.show', $item->id) }}"
                                    class="w-full px-3 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Lihat
                                    Detail</a>

                                <button type="button"
                                    onclick="openDeleteModal({{ $item->id }}, '{{ $item->user->name }}', '{{ $item->session->title }}')"
                                    class="w-full px-3 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 text-center" colspan="7">
                                Belum ada laporan post test
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 gap-2">
            @if ($laporans->total() > 8)
                <div class="w-full">
                    {{ $laporans->appends(['search' => request('search')])->links() }}
                </div>
            @else
                <div class="text-sm text-gray-600 dark:text-gray-300 p-2">
                    Menampilkan
                    @if ($laporans->total() > 0)
                        {{ $laporans->firstItem() }} sampai {{ $laporans->lastItem() }} dari total
                        {{ $laporans->total() }} hasil
                    @else
                        0 sampai 0 dari total 0 hasil
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modalDelete"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            <!-- Header -->
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-red-500 dark:text-red-400 flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konfirmasi Hapus</span>
                </h3>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 border-t border-b border-gray-200 dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus laporan post test
                    <strong id="PostTestNameToDelete" class="font-semibold"></strong> milik
                    <strong id="adminNameToDelete" class="font-semibold"></strong>?
                </p>
            </div>

            <!-- Footer / Actions -->
            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openDeleteModal(id, name, title) {
            document.getElementById('modalDelete').classList.remove('hidden');
            document.getElementById('adminNameToDelete').textContent = name;
            document.getElementById('PostTestNameToDelete').textContent = title;
            document.getElementById('deleteForm').action = '/laporan/' + id;
        }

        function closeDeleteModal() {
            document.getElementById('modalDelete').classList.add('hidden');
        }
    </script>
@endsection
