@extends('layouts.app')

@section('namePage', $jadwal->title)

@section('content')
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Laporan Absensi
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $jadwal->title ?? 'Tanpa Nama' }}
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('apuppt.absensi.index') }}"
                    class="px-3 py-2 text-sm rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Kembali
                </a>

                {{-- Export bawa filter q & sort & company & cabang agar konsisten --}}
                <a href="{{ route('apuppt.absensi.downloadExcel', $jadwal->id) }}?q={{ request('q') }}&sort={{ request('sort') }}&company={{ request('company') }}&cabang={{ request('cabang') }}"
                    class="px-3 py-2 text-sm rounded bg-green-500 hover:bg-green-600 text-white transition">
                    <i class="fa-solid fa-file-excel"></i> Excel
                </a>

                <a href="{{ route('apuppt.absensi.downloadPdf', $jadwal->id) }}?q={{ request('q') }}&sort={{ request('sort') }}&company={{ request('company') }}&cabang={{ request('cabang') }}"
                    class="px-3 py-2 text-sm rounded bg-red-500 hover:bg-red-600 text-white transition">
                    <i class="fa-solid fa-file-pdf"></i> PDF
                </a>

                {{-- Download per cabang --}}
                @if ($absensiList->isNotEmpty())
                    <div class="relative">
                        <button type="button" id="downloadDropdown"
                            class="px-3 py-2 text-sm rounded bg-blue-500 hover:bg-blue-600 text-white transition flex items-center gap-1">
                            {{ __('Download per Cabang') }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                                </path>
                            </svg>
                        </button>
                        <div id="downloadMenu"
                            class="absolute right-0 mt-1 w-64 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 hidden">
                            @php
                                $kantorCabang = [
                                    'Trainer (SGB)' => ['Semarang', 'Makassar', 'Jakarta', 'Jakarta – TCC Tower'],

                                    'Trainer (RFB)' => [
                                        'Medan',
                                        'Palembang',
                                        'Semarang',
                                        'Pekanbaru',
                                        'Bandung',
                                        'Solo',
                                        'Yogyakarta',
                                        'Balikpapan',
                                        'Jakarta AXA 1',
                                        'Jakarta AXA 2',
                                        'Jakarta AXA 3',
                                        'Jakarta DBS Tower',
                                        'Surabaya Pakuwon',
                                        'Jakarta - AXA Tower 1',
                                        'Jakarta - AXA Tower 2',
                                        'Jakarta - AXA Tower 3',
                                        'Jakarta - DBS Bank Tower',
                                        'Surabaya - Ciputra World Office Tower',
                                        'Surabaya - Pakuwon Tower',
                                    ],

                                    'Trainer (EWF)' => [
                                        'Surabaya Trillium',
                                        'Surabaya Trilium',
                                        'Manado',
                                        'Jakarta',
                                        'Semarang',
                                        'Surabaya Praxis',
                                        'Cirebon',
                                        'SSC Jakarta',
                                        'Cyber 2 Jakarta',
                                        'Jakarta Cyber 2',
                                    ],

                                    'Trainer (BPF)' => [
                                        'Jambi',
                                        'Jakarta - Pacific Place Mall',
                                        'Pontianak',
                                        'Malang',
                                        'Surabaya',
                                        'Medan',
                                        'Bandung',
                                        'Pekanbaru',
                                        'Banjarmasin',
                                        'Bandar Lampung',
                                        'Semarang',
                                        'Jakarta - Equity Tower',
                                        'Equity Tower Jakarta',
                                    ],

                                    'Trainer (KPF)' => [
                                        'Yogyakarta',
                                        'Bali',
                                        'Makassar',
                                        'Bandung',
                                        'Semarang',
                                        'Jakarta - Plaza Marein',
                                        'Jakarta',
                                    ],
                                ];

                                // ✅ tambahkan baris ini:
                                $selectedCompany = request('company');

                                if ($selectedCompany && isset($kantorCabang[$selectedCompany])) {
                                    $uniqueCabangs = collect($kantorCabang[$selectedCompany])->sort();
                                } else {
                                    $cabangs = collect();
                                    foreach ($absensiList as $absensi) {
                                        if ($absensi->user && $absensi->user->cabang) {
                                            $cabangs->push($absensi->user->cabang);
                                        }
                                    }
                                    $uniqueCabangs = $cabangs->unique()->sort();
                                }
                            @endphp

                            {{-- area yang bisa discroll --}}
                            <div class="py-1 max-h-80 overflow-y-auto pr-1 custom-scroll">
                                @foreach ($uniqueCabangs as $cabang)
                                    <a href="{{ route('apuppt.absensi.downloadExcelPerCabang', $jadwal->id) }}?q={{ request('q') }}&sort={{ request('sort') }}&company={{ request('company') }}&cabang={{ $cabang }}"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <span class="font-medium text-green-500">Excel</span> - {{ $cabang }}
                                    </a>
                                    <a href="{{ route('apuppt.absensi.downloadPdfPerCabang', $jadwal->id) }}?q={{ request('q') }}&sort={{ request('sort') }}&company={{ request('company') }}&cabang={{ $cabang }}"
                                        class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <span class="font-medium text-red-500">PDF</span> - {{ $cabang }}
                                    </a>
                                    <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </header>

    {{-- Filter & Sort --}}
    <div class="mb-4 p-4 sm:p-5 bg-white dark:bg-gray-800 rounded-xl shadow">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3" id="filterForm">
            <input type="hidden" name="page" value="1">
            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Cari Peserta</label>
                <input type="text" name="q" value="{{ request('q') }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                    placeholder="Nama...">
            </div>

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Perusahaan</label>
                <select name="company" id="companySelect"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @php $company = request('company'); @endphp
                    <option value="">Semua Perusahaan</option>
                    <option value="Trainer (SGB)" {{ $company === 'Trainer (SGB)' ? 'selected' : '' }}>PT Solid Gold
                        Berjangka</option>
                    <option value="Trainer (RFB)" {{ $company === 'Trainer (RFB)' ? 'selected' : '' }}>PT Rifan Financindo
                        Berjangka</option>
                    <option value="Trainer (EWF)" {{ $company === 'Trainer (EWF)' ? 'selected' : '' }}>PT Equity World
                        Futures</option>
                    <option value="Trainer (BPF)" {{ $company === 'Trainer (BPF)' ? 'selected' : '' }}>PT Best Profit
                        Futures</option>
                    <option value="Trainer (KPF)" {{ $company === 'Trainer (KPF)' ? 'selected' : '' }}>PT Kontak Perkasa
                        Futures</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Cabang</label>
                <select name="cabang" id="cabangSelect"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @php $cabang = request('cabang'); @endphp
                    <option value="">Semua Cabang</option>
                    @if ($cabang)
                        <option value="{{ $cabang }}" selected>{{ $cabang }}</option>
                    @endif
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Urutkan</label>
                <select name="sort"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @php $sort = request('sort', 'latest'); @endphp
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                    <option value="company_asc" {{ $sort === 'company_asc' ? 'selected' : '' }}>Perusahaan A-Z</option>
                    <option value="company_desc" {{ $sort === 'company_desc' ? 'selected' : '' }}>Perusahaan Z-A</option>
                    <option value="cabang_asc" {{ $sort === 'cabang_asc' ? 'selected' : '' }}>Cabang A-Z</option>
                    <option value="cabang_desc" {{ $sort === 'cabang_desc' ? 'selected' : '' }}>Cabang Z-A</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Per Halaman</label>
                @php $per = (int)request('per_page', 20); @endphp
                <select name="per_page"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @foreach ([20, 30, 50, 100, 200] as $n)
                        <option value="{{ $n }}" {{ $per === $n ? 'selected' : '' }}>{{ $n }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2 md:col-span-5">
                <button type="submit"
                    class="w-full md:w-auto px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm transition">
                    Terapkan
                </button>
                <a href="{{ route('apuppt.absensiAdmin.index', $jadwal->id) }}"
                    class="w-full md:w-auto px-4 py-2 rounded border bg-red-500 hover:bg-red-600 text-white border-gray-300 dark:border-gray-600 text-sm dark:text-gray-200 dark:hover:bg-gray-700 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        @php
            $agg = $aggregates ?? null;
            $total = (int) ($agg->total ?? 0);
        @endphp

        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Absensi</div>
            <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $total }}</div>
        </div>
    </div>

    {{-- Tabel Hasil --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        @if ($absensiList->isEmpty())
            <div class="text-center py-12 text-gray-600 dark:text-gray-300">
                Tidak ada data absensi.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                #</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nama</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Perusahaan</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Cabang</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Waktu Absensi</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($absensiList as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ ($absensiList->firstItem() ?? 1) + $index }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ optional($item->user)->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ optional($item->user)->nama_perusahaan ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ optional($item->user)->cabang ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ optional($item->waktu_absen)->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    <button onclick="openModalDelete('{{ $item->id }}')"
                                        class="text-red-600 dark:text-red-400 hover:underline">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $absensiList->links() }}
            </div>
        @endif
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
    @php
        $kantorCabang = [
            'Trainer (SGB)' => ['Semarang', 'Makassar', 'Jakarta', 'Jakarta – TCC Tower'],

            'Trainer (RFB)' => [
                'Medan',
                'Palembang',
                'Semarang',
                'Pekanbaru',
                'Bandung',
                'Solo',
                'Yogyakarta',
                'Balikpapan',
                'Jakarta AXA 1',
                'Jakarta AXA 2',
                'Jakarta AXA 3',
                'Jakarta DBS Tower',
                'Surabaya Pakuwon',
                'Jakarta - AXA Tower 1',
                'Jakarta - AXA Tower 2',
                'Jakarta - AXA Tower 3',
                'Jakarta - DBS Bank Tower',
                'Surabaya - Ciputra World Office Tower',
                'Surabaya - Pakuwon Tower',
            ],

            'Trainer (EWF)' => [
                'Surabaya Trillium',
                'Surabaya Trilium',
                'Manado',
                'Jakarta',
                'Semarang',
                'Surabaya Praxis',
                'Cirebon',
                'SSC Jakarta',
                'Cyber 2 Jakarta',
                'Jakarta Cyber 2',
            ],

            'Trainer (BPF)' => [
                'Jambi',
                'Jakarta – Pacific Place Mall',
                'Pontianak',
                'Malang',
                'Surabaya',
                'Medan',
                'Bandung',
                'Pekanbaru',
                'Banjarmasin',
                'Bandar Lampung',
                'Semarang',
                'Jakarta - Equity Tower',
                'Equity Tower Jakarta',
            ],

            'Trainer (KPF)' => [
                'Yogyakarta',
                'Bali',
                'Makassar',
                'Bandung',
                'Semarang',
                'Jakarta - Plaza Marein',
                'Jakarta',
            ],
        ];
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataCabang = @json($kantorCabang);
            const companySelect = document.getElementById('companySelect');
            const cabangSelect = document.getElementById('cabangSelect');

            function updateCabangOptions() {
                const selectedCompany = companySelect.value;
                cabangSelect.innerHTML = '<option value="">-- Pilih Cabang --</option>';

                if (selectedCompany && dataCabang[selectedCompany]) {
                    dataCabang[selectedCompany].forEach(cabang => {
                        const option = document.createElement('option');
                        option.value = cabang;
                        option.textContent = cabang;
                        cabangSelect.appendChild(option);
                    });
                }
            }

            companySelect.addEventListener('change', updateCabangOptions);
            updateCabangOptions(); // Initialize on page load

            // Dropdown toggle for download per cabang
            const downloadDropdown = document.getElementById('downloadDropdown');
            const downloadMenu = document.getElementById('downloadMenu');

            if (downloadDropdown && downloadMenu) {
                downloadDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                    downloadMenu.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function() {
                    downloadMenu.classList.add('hidden');
                });
            }
        });

        function openModalDelete(idAbsensi) {
            const idJadwal = "{{ request()->route('idJadwal') }}";
            const form = document.getElementById('deleteForm');
            form.action = `/apuppt/absensi/${idJadwal}/${idAbsensi}/delete`;
            document.getElementById('modalDelete').classList.remove('hidden');
        }

        function closeModalDelete() {
            document.getElementById('modalDelete').classList.add('hidden');
        }
    </script>
@endsection
