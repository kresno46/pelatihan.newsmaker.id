@extends('layouts.app')

@section('namePage', 'Trainer')

@section('content')
    <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-lg">
        <div class="flex items-center justify-between mb-5">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Daftar Admin</h1>

            <div class="flex items-center gap-4">
                @if (session('Alert'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                        class="text-xs bg-green-200 dark:bg-green-700 text-green-800 dark:text-green-100 py-1 px-3 rounded-lg">
                        {{ session('Alert') }}
                    </div>
                @endif

                <a href="{{ route('trainer.create') }}"
                    class="inline-block px-4 py-2 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                    Tambah Admin
                </a>
            </div>
        </div>

        <hr class="border-gray-300 dark:border-gray-600">

        <div
            class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg mt-5 shadow-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">#</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Nama</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-200">Email</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-200">Akun
                            Terverifikasi</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-200">Tanggal
                            Dibuat</th>
                        <th class="px-6 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-200">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($trainer as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100">
                                {{ $item->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-800 dark:text-gray-100">
                                {{ $item->email }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                @if ($item->email_verified_at == null)
                                    <div class="py-1 px-5 bg-yellow-200 rounded-full inline-block">
                                        <p class="text-yellow-700 text-xs font-medium">Belum Terverifikasi</p>
                                    </div>
                                @else
                                    <div class="py-1 px-5 bg-green-200 rounded-full inline-block">
                                        <p class="text-green-800 text-xs font-medium">Terverifikasi</p>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-center text-gray-800 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('trainer.show', $item->id) }}"
                                        class="w-full px-3 py-1 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600">Lihat</a>

                                    <a href="{{ route('trainer.edit', $item->id) }}"
                                        class="w-full px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">Edit</a>

                                    @if (auth()->user()->id !== $item->id)
                                        <button type="button"
                                            onclick="openDeleteModal({{ $item->id }}, '{{ $item->name }}')"
                                            class="w-full px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-100 text-center" colspan="6">
                                Belum ada admin
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mt-6 gap-2">
            @if ($trainer->total() > 8)
                <div class="w-full">
                    {{ $trainer->appends(['search' => request('search')])->links() }}
                </div>
            @else
                <div class="text-sm text-gray-600 dark:text-gray-300 p-2">
                    Menampilkan
                    @if ($trainer->total() > 0)
                        {{ $trainer->firstItem() }} sampai {{ $trainer->lastItem() }} dari total {{ $trainer->total() }}
                        hasil
                    @else
                        0 sampai 0 dari total 0 hasil
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modalDelete"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-200 hidden"
        onclick="event.target === this && closeDeleteModal()">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md transform transition-all scale-95">
            <!-- Header -->
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-red-500 flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konfirmasi Hapus</span>
                </h3>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 border-t border-b border-gray-200 dark:border-gray-600">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus admin <strong id="adminNameToDelete" class="font-semibold"></strong>?
                </p>
            </div>

            <!-- Footer -->
            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="">
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
        function openDeleteModal(id, name) {
            document.getElementById('modalDelete').classList.remove('hidden');
            document.getElementById('adminNameToDelete').textContent = name || 'admin ini';
            document.getElementById('deleteForm').action = '{{ url('admin') }}/' + id;
        }

        function closeDeleteModal() {
            document.getElementById('modalDelete').classList.add('hidden');
        }
    </script>
@endsection
