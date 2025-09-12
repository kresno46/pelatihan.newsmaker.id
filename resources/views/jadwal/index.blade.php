@extends('layouts.app')

@section('namePage', 'Absensi')

@section('content')
    <div>
        <div class="p-5 bg-white dark:bg-gray-800 rounded-lg shadow-lg space-y-5">
            <div class="flex items-center justify-between w-full">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Daftar Absensi</h3>

                @if (session('Alert'))
                    <div class="text-green-700 text-sm">
                        {{ session('Alert') }}
                    </div>
                @endif
            </div>

            <hr class="border bg-gray-500 dark:bg-gray-700">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                {{-- Tombol Tambah Sesi --}}
                <button onclick="openAddModal()"
                    class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 flex flex-col items-center justify-center">
                    <i class="fa-solid fa-plus text-2xl text-gray-600 dark:text-gray-300"></i>
                    <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">Tambah Sesi</div>
                </button>

                {{-- Loop Jadwal --}}
                @foreach ($jadwals as $jadwal)
                    <div
                        class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <div class="flex flex-col justify-between mb-2 h-full">
                            <h2 class="font-semibold text-sm text-gray-900 dark:text-white">{{ $jadwal->title }}</h2>
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

                                <button
                                    onclick="openEditModal({{ $jadwal->id }}, '{{ $jadwal->title }}', '{{ $jadwal->tanggal }}')"
                                    class="text-yellow-500">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

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

        {{-- Modal Tambah --}}
        <div id="addModal"
            class="modal hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 px-3 transition duration-300 ease-out">
            <div id="addModalContent"
                class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-md p-6 transform transition-all duration-300 scale-95 opacity-0">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Tambah Sesi Baru</h2>
                <form action="{{ route('absensi.store') }}" method="POST">
                    @csrf
                    {{-- Judul Sesi --}}
                    <div class="mb-4">
                        <label for="title" class="block text-sm text-gray-700 dark:text-gray-300">Judul Sesi</label>
                        <input type="text" name="title" id="title"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-4">
                        <label for="tanggal" class="block text-sm text-gray-700 dark:text-gray-300">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                    </div>

                    {{-- Post Test Session --}}
                    <div class="mb-4">
                        <label for="post_test_session_id" class="block text-sm text-gray-700 dark:text-gray-300">Pilih Sesi
                            Post-Test</label>
                        <select name="post_test_session_id" id="post_test_session_id"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                            <option value="">-- Pilih Sesi Post-Test --</option>
                            @foreach ($postTestSessions as $session)
                                <option value="{{ $session->id }}">{{ $session->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeAddModal()"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-500">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>

            </div>
        </div>

        {{-- Modal Edit --}}
        <div id="editModal"
            class="modal hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 px-3 transition duration-300 ease-out">
            <div id="editModalContent"
                class="bg-white dark:bg-gray-800 rounded-lg w-full max-w-md p-6 transform transition-all duration-300 scale-95 opacity-0">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Edit Sesi</h2>
                <form id="editForm" action="{{ route('absensi.update', $jadwal->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Judul Sesi --}}
                    <div class="mb-4">
                        <label for="title" class="block text-sm text-gray-700 dark:text-gray-300">Judul Sesi</label>
                        <input type="text" name="title" id="editTitle"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            value="{{ old('title', $jadwal->title) }}" required>
                    </div>

                    {{-- Tanggal --}}
                    <div class="mb-4">
                        <label for="tanggal" class="block text-sm text-gray-700 dark:text-gray-300">Tanggal</label>
                        <input type="date" name="tanggal" id="editTanggal"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            value="{{ old('tanggal', $jadwal->tanggal) }}" required>
                    </div>

                    {{-- Post Test Session --}}
                    <div class="mb-4">
                        <label for="post_test_session_id" class="block text-sm text-gray-700 dark:text-gray-300">Pilih
                            Sesi Post-Test</label>
                        <select name="post_test_session_id" id="post_test_session_id"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded px-3 py-2 mt-1 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                            required>
                            <option value="">-- Pilih Sesi Post-Test --</option>
                            @foreach ($postTestSessions as $session)
                                <option value="{{ $session->id }}"
                                    {{ old('post_test_session_id', $jadwal->post_test_session_id) == $session->id ? 'selected' : '' }}>
                                    {{ $session->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-800 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-500">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openAddModal() {
            const modal = document.getElementById('addModal');
            const content = document.getElementById('addModalContent');
            modal.classList.remove('hidden');
            // Trigger animation
            requestAnimationFrame(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeAddModal() {
            const modal = document.getElementById('addModal');
            const content = document.getElementById('addModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }

        function openEditModal(id, title, tanggal) {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('editModalContent');
            modal.classList.remove('hidden');
            document.getElementById('editTitle').value = title;
            document.getElementById('editTanggal').value = tanggal;
            document.getElementById('editForm').action = `/laporan/absensi/${id}/update`;
            requestAnimationFrame(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('editModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }

        function openDeleteModal(id, title) {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');
            const form = document.getElementById('deleteForm');
            const titleSpan = document.getElementById('deleteTitle');

            form.action = `/absensi/${id}/hapus`; // Sesuaikan dengan route delete
            titleSpan.innerText = title;

            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            });
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = document.getElementById('deleteModalContent');

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }

        document.getElementById('deleteModal').addEventListener('click', closeDeleteModal);
        document.getElementById('deleteModalContent').addEventListener('click', function(e) {
            e.stopPropagation(); // Mencegah modal tertutup saat klik di dalam konten
        });

        document.getElementById('addModal').addEventListener('click', closeAddModal);
        document.getElementById('editModal').addEventListener('click', closeEditModal);
    </script>
@endsection
