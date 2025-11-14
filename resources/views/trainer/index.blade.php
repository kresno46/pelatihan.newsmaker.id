@extends('layouts.app')

@section('namePage', 'Trainer')

@section('content')
    <header class="w-full bg-white dark:bg-gray-800 shadow-lg rounded-lg mb-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Daftar User
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Halaman ini menampilkan daftar pengguna.
                </p>
            </div>

            <div class="flex items-center gap-2">
                @if (session('Alert'))
                    <div class="text-green-600 dark:text-green-400 font-semibold">{{ session('Alert') }}</div>
                @endif

                @if (session('error'))
                    <div class="text-red-600 dark:text-red-400 font-semibold">{{ session('error') }}</div>
                @endif
            </div>
        </div>
    </header>

    <form method="GET" action="{{ route('trainer.index') }}" class="mb-5 bg-white p-4 sm:p-5 rounded-xl shadow-lg">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Filter Perusahaan --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Perusahaan</label>
                <select name="filter_perusahaan" id="filter_perusahaan"
                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Semua Perusahaan --</option>
                    @foreach ($dropdownPerusahaanList as $perusahaan)
                        <option value="{{ $perusahaan }}"
                            {{ request('filter_perusahaan') == $perusahaan ? 'selected' : '' }}>
                            {{ $perusahaan }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Cabang --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Cabang</label>
                <select name="filter_cabang" id="filter_cabang"
                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Semua Cabang --</option>
                    @if (request('filter_perusahaan'))
                        @php
                            $roleKey = array_search(request('filter_perusahaan'), $namaPerusahaan) ?? null;
                            $cabangs = $roleKey ? $kantorCabang[$roleKey] ?? [] : [];
                        @endphp
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang }}" {{ request('filter_cabang') == $cabang ? 'selected' : '' }}>
                                {{ $cabang }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            {{-- Search --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Cari Trainer</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email..."
                    class="w-full border-gray-300 rounded-md shadow-sm text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Sort --}}
            <div>
                <label class="block text-sm font-semibold mb-1">Urutkan</label>
                <select name="sort_by"
                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="latest" {{ request('sort_by') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort_by') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="name_asc" {{ request('sort_by') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name_desc" {{ request('sort_by') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                </select>
            </div>
        </div>

        {{-- Tombol --}}
        <div class="flex flex-col sm:flex-row justify-end gap-3 mt-4">
            <a href="{{ route('trainer.index') }}"
                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium transition duration-200 text-center">
                Reset
            </a>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium transition duration-200">
                Filter
            </button>
        </div>
    </form>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-300">
        <!-- Mobile Card View -->
        <div class="block md:hidden p-4 space-y-4">
            @forelse ($trainer as $item)
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $item->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->email }}</p>
                        </div>
                        <span
                            class="text-xs font-medium px-2 py-1 rounded-full {{ $item->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $item->email_verified_at ? 'Terverifikasi' : 'Belum' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Perusahaan:</span>
                            <p class="font-medium">{{ $namaPerusahaan[$item->role] ?? ($item->role ?? '-') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Cabang:</span>
                            <p class="font-medium">{{ $item->cabang ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('trainer.show', $item->id) }}"
                            class="flex-1 px-3 py-2 bg-yellow-500 text-white text-xs rounded-md hover:bg-yellow-600 text-center font-medium">
                            Lihat
                        </a>
                        <a href="{{ route('trainer.edit', $item->id) }}"
                            class="flex-1 px-3 py-2 bg-blue-500 text-white text-xs rounded-md hover:bg-blue-600 text-center font-medium">
                            Edit
                        </a>
                        @if (auth()->user()->id !== $item->id)
                            <button type="button" onclick="openDeleteModal({{ $item->id }}, '{{ $item->name }}')"
                                class="flex-1 px-3 py-2 bg-red-500 text-white text-xs rounded-md hover:bg-red-600 font-medium">
                                Hapus
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                    Belum ada trainer
                </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-200 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-200">
                            #</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-left text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-200">
                            Nama</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-center text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-200">
                            Email</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-center text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-200">
                            Perusahaan</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-center text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-200">
                            Cabang</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-center text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-200">
                            Status</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-center text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-200">
                            Tanggal Dibuat</th>
                        <th
                            class="px-4 lg:px-6 py-3 text-center text-xs lg:text-sm font-medium text-gray-700 dark:text-gray-200">
                            Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($trainer as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $loop->iteration }}</td>
                            <td class="px-4 lg:px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $item->name }}</td>
                            <td class="px-4 lg:px-6 py-4 text-sm text-center text-gray-900 dark:text-white">
                                {{ $item->email }}</td>
                            <td class="px-4 lg:px-6 py-4 text-sm text-center text-gray-900 dark:text-white">
                                {{ $namaPerusahaan[$item->role] ?? ($item->role ?? '-') }}
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-sm text-center text-gray-900 dark:text-white">
                                {{ $item->cabang ?? '-' }}</td>
                            <td class="px-4 lg:px-6 py-4 text-sm text-center">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $item->email_verified_at ? 'Terverifikasi' : 'Belum' }}
                                </span>
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-sm text-center text-gray-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 lg:px-6 py-4 text-sm text-center">
                                <div class="flex justify-center gap-1 lg:gap-2">
                                    <a href="{{ route('trainer.show', $item->id) }}"
                                        class="px-2 lg:px-3 py-1 bg-yellow-500 text-white text-xs rounded-md hover:bg-yellow-600 font-medium transition duration-200">
                                        Lihat
                                    </a>
                                    <a href="{{ route('trainer.edit', $item->id) }}"
                                        class="px-2 lg:px-3 py-1 bg-blue-500 text-white text-xs rounded-md hover:bg-blue-600 font-medium transition duration-200">
                                        Edit
                                    </a>
                                    @if (auth()->user()->id !== $item->id)
                                        <button type="button"
                                            onclick="openDeleteModal({{ $item->id }}, '{{ $item->name }}')"
                                            class="px-2 lg:px-3 py-1 bg-red-500 text-white text-xs rounded-md hover:bg-red-600 font-medium transition duration-200">
                                            Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 lg:px-6 py-8 text-sm text-gray-500 dark:text-gray-400 text-center"
                                colspan="8">
                                Belum ada trainer
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 lg:px-6 py-4 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
            {{ $trainer->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="modalDelete"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm transition-opacity duration-200 hidden p-4"
        onclick="event.target === this && closeDeleteModal()">
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm sm:max-w-md transform transition-all scale-95">
            <div class="px-4 sm:px-6 py-5">
                <h3 class="text-lg sm:text-xl font-bold text-red-500 flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konfirmasi Hapus</span>
                </h3>
            </div>
            <div class="px-4 sm:px-6 py-4 border-t border-b border-gray-200 dark:border-gray-600">
                <p class="text-gray-700 dark:text-gray-300 text-sm sm:text-base">
                    Apakah Anda yakin ingin menghapus trainer <strong id="adminNameToDelete"
                        class="font-semibold"></strong>?
                </p>
            </div>
            <div class="flex flex-col sm:flex-row justify-end gap-3 px-4 sm:px-6 py-4">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-md text-gray-800 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500 transition text-sm font-medium">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition text-sm font-medium">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const kantorCabang = @json($kantorCabang);
        const namaPerusahaan = @json($namaPerusahaan);

        const perusahaanSelect = document.getElementById('filter_perusahaan');
        const cabangSelect = document.getElementById('filter_cabang');

        perusahaanSelect.addEventListener('change', function() {
            const selected = Object.keys(namaPerusahaan).find(key => namaPerusahaan[key] === this.value);
            const cabangs = selected ? (kantorCabang[selected] || []) : [];
            cabangSelect.innerHTML = '<option value="">-- Semua Cabang --</option>';
            cabangs.forEach(cabang => {
                const opt = document.createElement('option');
                opt.value = cabang;
                opt.textContent = cabang;
                cabangSelect.appendChild(opt);
            });
        });

        function openDeleteModal(id, name) {
            document.getElementById('modalDelete').classList.remove('hidden');
            document.getElementById('adminNameToDelete').textContent = name || 'trainer ini';
            document.getElementById('deleteForm').action = '{{ url('trainer') }}/' + id;
        }

        function closeDeleteModal() {
            document.getElementById('modalDelete').classList.add('hidden');
        }
    </script>
@endsection
