@extends('layouts.app')

@section('namePage', 'Laporan Post Test')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between mb-5">
            <h1 class="text-2xl font-bold">Daftar Laporan Post Test</h1>

            @if (session('Alert'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                    class="text-xs bg-green-200 text-green-800 py-1 px-3 rounded-lg">
                    {{ session('Alert') }}
                </div>
            @endif
        </div>

        <hr>

        <div class="overflow-x-auto bg-white rounded-lg mt-5 shadow-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">#</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">eBook</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Post Test</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Score</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Tanggal Dibuat</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($laporans as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $item->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $item->ebook->title }}</td>
                            <td class="px-6 py-4 text-sm text-center text-gray-800">{{ $item->session->title }}</td>
                            <td class="px-6 py-4 text-sm text-center">
                                @php
                                    $score = $item->score;
                                    if ($score >= 85) {
                                        $badgeColor = 'bg-green-100 text-green-800';
                                    } elseif ($score >= 70) {
                                        $badgeColor = 'bg-lime-100 text-lime-800';
                                    } elseif ($score >= 50) {
                                        $badgeColor = 'bg-yellow-100 text-yellow-800';
                                    } elseif ($score >= 25) {
                                        $badgeColor = 'bg-orange-100 text-orange-800';
                                    } else {
                                        $badgeColor = 'bg-red-100 text-red-800';
                                    }
                                @endphp

                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                    {{ $score }}/100
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-800">
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
                            <td class="px-6 py-4 text-sm text-gray-800 text-center" colspan="7">
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
                <h3 class="text-xl font-bold text-red-500 dark:text-red-500 flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konfirmasi Hapus</span>
                </h3>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 border-t border-b dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus laporan post test <strong id="PostTestNameToDelete"
                        class="font-semibold"></strong> milik
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
