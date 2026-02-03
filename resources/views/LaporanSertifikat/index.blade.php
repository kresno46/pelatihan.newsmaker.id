@extends('layouts.app')

@section('namePage', 'Laporan Sertifikat')

@section('content')
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Laporan Sertifikat
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Halaman ini menampilkan daftar pengguna yang telah mengunduh sertifikat.
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

    {{-- Filter & Sort --}}
    <div class="mb-4 p-4 sm:p-5 bg-white dark:bg-gray-800 rounded-xl shadow">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3" id="filterForm">
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
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Kategori</label>
                <select name="kategori"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @php $kategori = request('kategori'); @endphp
                    <option value="">Semua Kategori</option>
                    <option value="PATD" {{ $kategori === 'PATD' ? 'selected' : '' }}>PATD</option>
                    <option value="PATL" {{ $kategori === 'PATL' ? 'selected' : '' }}>PATL</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Urutkan</label>
                <select name="sort"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @php $sort = request('sort', 'latest'); @endphp
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Terlama</option>
                    <option value="highest" {{ $sort === 'highest' ? 'selected' : '' }}>Nilai Tertinggi</option>
                    <option value="lowest" {{ $sort === 'lowest' ? 'selected' : '' }}>Nilai Terendah</option>
                    <option value="name_asc" {{ $sort === 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                    <option value="name_desc" {{ $sort === 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                    <option value="company_asc" {{ $sort === 'company_asc' ? 'selected' : '' }}>Perusahaan A-Z</option>
                    <option value="company_desc" {{ $sort === 'company_desc' ? 'selected' : '' }}>Perusahaan Z-A</option>
                    <option value="cabang_asc" {{ $sort === 'cabang_asc' ? 'selected' : '' }}>Cabang A-Z</option>
                    <option value="cabang_desc" {{ $sort === 'cabang_desc' ? 'selected' : '' }}>Cabang Z-A</option>
                    <option value="awarded_asc" {{ $sort === 'awarded_asc' ? 'selected' : '' }}>Tanggal Terlama</option>
                    <option value="awarded_desc" {{ $sort === 'awarded_desc' ? 'selected' : '' }}>Tanggal Terbaru</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Per Halaman</label>
                @php $per = (int)request('per_page', 12); @endphp
                <select name="per_page"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @foreach ([20, 30, 50, 100, 200] as $n)
                        <option value="{{ $n }}" {{ $per === $n ? 'selected' : '' }}>{{ $n }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2 md:col-span-6">
                <button type="submit"
                    class="w-full md:w-auto px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm transition">
                    Terapkan
                </button>
                <a href="{{ route('LaporanSertifikat.index') }}"
                    class="w-full md:w-auto px-4 py-2 rounded border bg-red-500 hover:bg-red-600 text-white border-gray-300 dark:border-gray-600 text-sm dark:text-gray-200 dark:hover:bg-gray-700 transition">
                    Reset
                </a>

                {{-- Download Button --}}
                <div class="relative">
                    <button type="button" id="downloadDropdown"
                        class="w-full md:w-auto px-4 py-2 rounded bg-green-600 hover:bg-green-700 text-white text-sm transition flex items-center gap-2">
                        <i class="fas fa-download"></i>
                        Download
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div id="downloadMenu"
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 hidden">
                        <div class="py-1">
                            <a href="{{ route('LaporanSertifikat.export', request()->query()) }}"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-file-excel mr-2 text-green-600"></i>
                                Download Excel
                            </a>
                            @if (request('cabang'))
                                <a href="{{ route('LaporanSertifikat.exportPerCabang', request()->query()) }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-file-excel mr-2 text-blue-600"></i>
                                    Download Excel ({{ request('cabang') }})
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        @php
            $agg = $aggregates ?? null;
            $total = (int) ($agg->total ?? 0);
            $avg = is_null($agg?->avg_score) ? null : number_format($agg->avg_score, 2);
            $max = is_null($agg?->max_score) ? null : $agg->max_score;
            $min = is_null($agg?->min_score) ? null : $agg->min_score;
        @endphp

        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Total Sertifikat</div>
            <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $total }}</div>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Rata-rata Nilai</div>
            <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $avg ?? '—' }}</div>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Nilai Tertinggi</div>
            <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $max ?? '—' }}</div>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">Nilai Terendah</div>
            <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $min ?? '—' }}</div>
        </div>
    </div>

    {{-- Tabel Hasil --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        @if ($sertifikats->isEmpty())
            <div class="text-center py-12 text-gray-600 dark:text-gray-300">
                Belum ada pengguna yang mengunduh sertifikat.
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
                                Kategori</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nilai</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Tanggal Sertifikat</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($sertifikats as $index => $item)
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
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ ($sertifikats->firstItem() ?? 1) + $index }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ optional($item->user)->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $perusahaan }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ optional($item->user)->cabang ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ optional(optional($item->postTestResult)->session)->tipe ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $item->average_score }}/100</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ optional($item->awarded_at)->format('d F Y - H:i') }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    <button type="button"
                                        onclick="showDetailModal(
                                            '{{ optional($item->user)->name ?? '-' }}',
                                            '{{ $perusahaan }}',
                                            '{{ $item->average_score }}',
                                            '{{ $item->certificate_uuid }}',
                                            '{{ optional($item->awarded_at)->format('Y-m-d H:i') }}'
                                        )"
                                        class="text-blue-600 dark:text-blue-400 hover:underline transition-all duration-300">
                                        Detail
                                    </button>
                                    <span class="text-gray-500 dark:text-gray-400">|</span>
                                    <button class="text-red-600 dark:text-red-400 hover:underline"
                                        onclick="showDeleteModal('{{ route('LaporanSertifikat.destroy', $item->id) }}')">
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
                {{ $sertifikats->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Detail (Large) -->
    <div id="modalDetail" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-5xl mx-auto transform transition-all duration-300 scale-95 opacity-0"
            id="modalContent">
            <!-- Header -->
            <div class="relative bg-gradient-to-r from-blue-600 to-purple-600 rounded-t-2xl px-8 py-6">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-certificate text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">Detail Sertifikat</h3>
                            <p class="text-blue-100 text-sm">Informasi lengkap sertifikat peserta</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModal('modalDetail')"
                        class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 text-white transition-all duration-200 flex items-center justify-center hover:rotate-90">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12">
                </div>
            </div>

            <!-- Content -->
            <div class="px-8 py-8">
                <!-- Certificate Preview Card -->
                <div
                    class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 rounded-xl p-6 mb-8 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center">
                            <i class="fas fa-award text-yellow-500 mr-2"></i>
                            Sertifikat Digital
                        </h4>
                        <span
                            class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>Valid
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-user text-blue-600 dark:text-blue-400 text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Peserta</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-star text-green-600 dark:text-green-400 text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Nilai</p>
                        </div>
                        <div class="text-center">
                            <div
                                class="w-16 h-16 bg-purple-100 dark:bg-purple-800 rounded-full flex items-center justify-center mx-auto mb-2">
                                <i class="fas fa-calendar text-purple-600 dark:text-purple-400 text-xl"></i>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Tanggal</p>
                        </div>
                    </div>
                </div>

                <!-- Detail Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Personal Information -->
                    <div class="space-y-4">
                        <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center mb-4">
                            <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                            Informasi Peserta
                        </h5>

                        <div
                            class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-5 border border-blue-200 dark:border-blue-800">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Nama Lengkap</p>
                                    <p class="font-semibold text-gray-900 dark:text-white" id="detailName">-</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-5 border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-building text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Perusahaan</p>
                                    <p class="font-semibold text-gray-900 dark:text-white" id="detailCompany">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate Information -->
                    <div class="space-y-4">
                        <h5 class="text-lg font-semibold text-gray-800 dark:text-white flex items-center mb-4">
                            <i class="fas fa-certificate text-green-500 mr-2"></i>
                            Detail Sertifikat
                        </h5>

                        <div
                            class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-5 border border-green-200 dark:border-green-800">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-star text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Nilai Akhir</p>
                                    <p class="font-semibold text-gray-900 dark:text-white" id="detailScore">-/100</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-gradient-to-r from-orange-50 to-yellow-50 dark:from-orange-900/20 dark:to-yellow-900/20 rounded-xl p-5 border border-orange-200 dark:border-orange-800">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-white"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Tanggal Diterbitkan</p>
                                    <p class="font-semibold text-gray-900 dark:text-white" id="detailDate">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Certificate UUID -->
                <div
                    class="mt-6 bg-gradient-to-r from-gray-50 to-slate-50 dark:from-gray-700 dark:to-slate-700 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-fingerprint text-white"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Certificate UUID</p>
                            <p class="font-mono text-sm font-semibold text-gray-900 dark:text-white break-all"
                                id="detailUuid">-</p>
                        </div>
                        <button onclick="copyToClipboard('detailUuid')" id="copyBtn"
                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 rounded-lg text-sm transition-colors duration-200">
                            <i class="fas fa-copy mr-1"></i>Salin
                        </button>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700 rounded-b-2xl border-t border-gray-200 dark:border-gray-600">
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal('modalDetail')"
                        class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white rounded-xl font-medium transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete (default modal) -->
    <div id="modalDelete" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full">
            <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
            <hr class="border-gray-300 dark:border-gray-600 my-6">
            <p class="text-gray-700 dark:text-gray-300">Apakah Anda yakin ingin menghapus sertifikat ini?</p>
            <hr class="border-gray-300 dark:border-gray-600 my-6">
            <form id="deleteForm" method="POST" class="mt-6 text-right">
                @csrf
                @method('DELETE')
                <button type="button" onclick="closeModal('modalDelete')"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded hover:bg-gray-400 dark:hover:bg-gray-600 mr-2">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    Hapus
                </button>
            </form>
        </div>
    </div>
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
            const modalContent = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Animate modal entrance
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function showDeleteModal(actionUrl) {
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = actionUrl;

            const modal = document.getElementById('modalDelete');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            const modalContent = document.getElementById('modalContent');

            if (id === 'modalDetail' && modalContent) {
                // Animate modal exit
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }, 300);
            } else {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            const text = element.textContent || element.innerText;
            const button = document.getElementById('copyBtn');

            // Fallback for older browsers
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopySuccess(button);
                }).catch(function(err) {
                    console.error('Failed to copy: ', err);
                    fallbackCopyTextToClipboard(text, button);
                });
            } else {
                fallbackCopyTextToClipboard(text, button);
            }
        }

        function fallbackCopyTextToClipboard(text, button) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-999999px";
            textArea.style.top = "-999999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                document.execCommand('copy');
                showCopySuccess(button);
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
                showCopyError(button);
            }

            textArea.remove();
        }

        function showCopySuccess(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check mr-1"></i>Tersalin!';
            button.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-600', 'dark:hover:bg-gray-500');
            button.classList.add('bg-green-500', 'hover:bg-green-600');

            // Show notification
            showNotification('UUID berhasil disalin ke clipboard!', 'success');

            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-green-500', 'hover:bg-green-600');
                button.classList.add('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-600',
                    'dark:hover:bg-gray-500');
            }, 2000);
        }

        function showCopyError(button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-times mr-1"></i>Gagal!';
            button.classList.remove('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-600', 'dark:hover:bg-gray-500');
            button.classList.add('bg-red-500', 'hover:bg-red-600');

            // Show notification
            showNotification('Gagal menyalin UUID. Coba lagi.', 'error');

            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('bg-red-500', 'hover:bg-red-600');
                button.classList.add('bg-gray-200', 'hover:bg-gray-300', 'dark:bg-gray-600',
                    'dark:hover:bg-gray-500');
            }, 2000);
        }

        function showNotification(message, type = 'info') {
            // Remove existing notification
            const existingNotification = document.getElementById('customNotification');
            if (existingNotification) {
                existingNotification.remove();
            }

            // Create notification element
            const notification = document.createElement('div');
            notification.id = 'customNotification';
            notification.className =
                `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

            let bgColor, textColor, icon;
            switch (type) {
                case 'success':
                    bgColor = 'bg-green-500';
                    textColor = 'text-white';
                    icon = 'fas fa-check-circle';
                    break;
                case 'error':
                    bgColor = 'bg-red-500';
                    textColor = 'text-white';
                    icon = 'fas fa-exclamation-circle';
                    break;
                default:
                    bgColor = 'bg-blue-500';
                    textColor = 'text-white';
                    icon = 'fas fa-info-circle';
            }

            notification.classList.add(bgColor, textColor);
            notification.innerHTML = `
                <div class="flex items-center space-x-3">
                    <i class="${icon}"></i>
                    <span>${message}</span>
                    <button onclick="closeNotification()" class="ml-4 hover:opacity-75">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Auto close after 3 seconds
            setTimeout(() => {
                closeNotification();
            }, 3000);
        }

        function closeNotification() {
            const notification = document.getElementById('customNotification');
            if (notification) {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('modalDetail');
            if (event.target === modal) {
                closeModal('modalDetail');
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal('modalDetail');
                closeModal('modalDelete');
            }
        });

        // Dynamic cabang options based on company selection
        const kantorCabang = {
            'Trainer (SGB)': [
                'Semarang',
                'Makassar',
                'Jakarta',
                'Jakarta - TCC Tower',
            ],

            'Trainer (RFB)': [
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

            'Trainer (EWF)': [
                'Surabaya Trillium',
                'Surabaya Trilium',
                'Manado',
                'Jakarta',
                'Semarang',
                'Surabaya Praxis',
                'Cirebon',
                'SCC Jakarta',
                'Cyber 2 Jakarta',
                'Jakarta Cyber 2',
            ],

            'Trainer (BPF)': [
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

            'Trainer (KPF)': [
                'Yogyakarta',
                'Bali',
                'Makassar',
                'Bandung',
                'Semarang',
                'Jakarta - Plaza Marein',
                'Jakarta',
            ]
        };

        function updateCabangOptions() {
            const companySelect = document.getElementById('companySelect');
            const cabangSelect = document.getElementById('cabangSelect');
            const selectedCompany = companySelect.value;

            // Reset cabang options
            cabangSelect.innerHTML = '<option value="">Semua Cabang</option>';

            if (selectedCompany && kantorCabang[selectedCompany]) {
                kantorCabang[selectedCompany].forEach(function(cabang) {
                    const option = document.createElement('option');
                    option.value = cabang;
                    option.textContent = cabang;
                    if (cabang === '{{ request('cabang') }}') {
                        option.selected = true;
                    }
                    cabangSelect.appendChild(option);
                });
            }
        }

        // Event listener for company change
        document.getElementById('companySelect').addEventListener('change', updateCabangOptions);

        // Initialize on page load
        updateCabangOptions();

        // Reset page when filters change
        document.getElementById('filterForm').addEventListener('submit', function() {
            document.querySelector('input[name="page"]').value = '1';
        });

        // Download dropdown toggle
        document.getElementById('downloadDropdown').addEventListener('click', function() {
            const menu = document.getElementById('downloadMenu');
            menu.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('downloadDropdown');
            const menu = document.getElementById('downloadMenu');
            if (!dropdown.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
@endsection
