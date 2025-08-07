@extends('layouts.app')

@section('namePage', $jadwal->title)

@section('content')
    <div class="bg-white dark:bg-gray-800 p-5 rounded-lg shadow-lg">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('absensi.index') }}" class="text-lg text-gray-800 dark:text-gray-200">
                    <i class="fa-solid fa-circle-xmark"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ $jadwal->title ?? 'Tanpa Nama' }}
                </h1>
            </div>

            <div class="flex justify-end gap-4 text-sm">
                <button onclick="openFilterModal()"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-300">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>

                <a href="{{ route('absensi.downloadExcel', $jadwal->id) }}"
                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all duration-300">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </a>

                <a href="{{ route('absensi.downloadPdf', $jadwal->id) }}"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-300">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>
            </div>
        </div>

        <!-- Modal Filter -->
        <div id="filterModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 z-50">
            <div class="flex justify-center items-center h-full">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-lg w-1/3 shadow-lg">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Filter Absensi</h2>

                    <form method="GET" action="{{ route('absensiAdmin.index', $jadwal->id) }}">
                        @csrf

                        <div class="mb-4">
                            <label for="search"
                                class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-900 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                                placeholder="Search by name...">
                        </div>

                        <div class="mb-4">
                            <label for="sort_by" class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Sort
                                By</label>
                            <select name="sort_by" id="sort_by"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-900 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Name</option>
                                <option value="role" {{ request('sort_by') == 'role' ? 'selected' : '' }}>Role</option>
                                <option value="time" {{ request('sort_by') == 'time' ? 'selected' : '' }}>Time</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="role"
                                class="block text-sm font-medium text-gray-700 dark:text-neutral-300">Role</label>
                            <select name="role" id="role"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-neutral-600 bg-white dark:bg-neutral-900 text-gray-900 dark:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                                <option value="">All Roles</option>
                                @foreach ($rolesPT as $role)
                                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                        {{ $role }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end gap-4 text-sm">
                            <a href="{{ route('absensiAdmin.index', $jadwal->id) }}"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-all duration-300">Reset</a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-300">Apply
                                Filters</button>
                            <button type="button" onclick="closeFilterModal()"
                                class="bg-gray-300 hover:bg-gray-400 dark:bg-neutral-700 dark:hover:bg-neutral-600 text-gray-800 dark:text-white px-4 py-2 rounded-lg transition-all duration-300">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex flex-col shadow-lg">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="border border-gray-200 rounded-lg overflow-hidden dark:border-neutral-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                            <thead class="bg-gray-200 dark:bg-neutral-700">
                                <tr class="divide-x divide-gray-300 dark:divide-neutral-600">
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-700 dark:text-neutral-300 uppercase">
                                        Nama</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-700 dark:text-neutral-300 uppercase">
                                        Role</th>
                                    <th
                                        class="px-6 py-3 text-start text-xs font-medium text-gray-700 dark:text-neutral-300 uppercase">
                                        Waktu Absensi</th>
                                    <th
                                        class="px-6 py-3 text-end text-xs font-medium text-gray-700 dark:text-neutral-300 uppercase">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($absensiList as $item)
                                    <tr
                                        class="odd:bg-white even:bg-gray-100 hover:bg-gray-100 dark:odd:bg-neutral-800 dark:even:bg-neutral-700 dark:hover:bg-neutral-600 divide-x divide-gray-200 dark:divide-neutral-700 transition-all duration-100">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                            {{ $item->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                            {{ $item->user->nama_perusahaan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                            {{ \Carbon\Carbon::parse($item->waktu_absen)->locale('id')->translatedFormat('l, d F Y - H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                            <button onclick="openModalDelete('{{ $item->id }}')"
                                                class="text-red-500 hover:underline">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-neutral-400">
                                            Tidak ada data absensi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete (hanya satu) -->
    <div id="modalDelete"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center px-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-md">
            <div class="px-6 py-5">
                <h3 class="text-xl font-bold text-red-500">
                    <i class="fa-solid fa-triangle-exclamation mr-2"></i>Konfirmasi Hapus
                </h3>
            </div>
            <div class="px-6 py-4 border-t border-b border-gray-200 dark:border-gray-700">
                <p class="text-gray-700 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus data absensi ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4">
                <button onclick="closeModalDelete()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:text-white rounded-md">
                    Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openFilterModal() {
            document.getElementById('filterModal').classList.remove('hidden');
        }

        function closeFilterModal() {
            document.getElementById('filterModal').classList.add('hidden');
        }

        function openModalDelete(idAbsensi) {
            const idJadwal = "{{ request()->route('idJadwal') }}";
            const form = document.getElementById('deleteForm');
            form.action = `/laporan/absensi/${idJadwal}/${idAbsensi}/delete`;
            document.getElementById('modalDelete').classList.remove('hidden');
        }

        function closeModalDelete() {
            document.getElementById('modalDelete').classList.add('hidden');
        }
    </script>
@endsection
