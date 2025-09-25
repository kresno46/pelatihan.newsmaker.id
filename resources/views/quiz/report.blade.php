@extends('layouts.app')

@section('namePage', 'Laporan ' . $session->title)

@section('content')
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ __('Laporan Post Test') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ $session->title ?? '—' }}
                    @if (!empty($session->duration))
                        • {{ __('Durasi') }} {{ $session->duration }} {{ __('menit') }}
                    @endif
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('posttest.index') }}"
                    class="px-3 py-2 text-sm rounded border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    {{ __('Kembali') }}
                </a>

                {{-- Export bawa filter q & sort & company & branch agar konsisten --}}
                <a href="{{ route('posttest.report.export', $session->slug) }}?q={{ request('q') }}&sort={{ request('sort') }}&company={{ request('company') }}&branch={{ request('branch') }}"
                    class="px-3 py-2 text-sm rounded bg-green-500 hover:bg-green-600 text-white transition">
                    {{ __('Export CSV') }}
                </a>
            </div>
        </div>
    </header>

    {{-- Filter & Sort --}}
    <div class="mb-4 p-4 sm:p-5 bg-white dark:bg-gray-800 rounded-xl shadow">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Cari Peserta') }}</label>
                <input type="text" name="q" value="{{ $filters['q'] ?? request('q') }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                    placeholder="Nama atau email...">
            </div>

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Filter Perusahaan') }}</label>
                <select name="company" id="company"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @php $company = $filters['company'] ?? request('company', ''); @endphp
                    <option value="" {{ $company === '' ? 'selected' : '' }}>Semua Perusahaan</option>
                    @foreach ($companies as $companyName)
                        <option value="{{ $companyName }}" {{ $company === $companyName ? 'selected' : '' }}>{{ $companyName }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Filter Cabang') }}</label>
                <select name="branch" id="branch"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @php $branch = $filters['branch'] ?? request('branch', ''); @endphp
                    <option value="" {{ $branch === '' ? 'selected' : '' }}>Semua Cabang</option>
                    @if($branches)
                        @foreach ($branches as $b)
                            <option value="{{ $b }}" {{ $branch === $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Urutkan') }}</label>
                <select name="sort" id="sort"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @php $sort = $filters['sort'] ?? request('sort', 'latest'); @endphp
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>{{ __('Terbaru') }}</option>
                </select>
            </div>

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">{{ __('Per Halaman') }}</label>
                @php $per = (int)($filters['per_page'] ?? request('per_page', 12)); @endphp
                <select name="per_page"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    @foreach ([10, 12, 15, 20, 30, 50, 100, 200] as $n)
                        <option value="{{ $n }}" {{ $per === $n ? 'selected' : '' }}>{{ $n }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="w-full md:w-auto px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm transition">
                    {{ __('Terapkan') }}
                </button>
                <a href="{{ route('posttest.report', $session->slug) }}"
                    class="w-full md:w-auto px-4 py-2 rounded border bg-red-500 hover:bg-red-600 text-white border-gray-300 dark:border-gray-600 text-sm dark:text-gray-200 dark:hover:bg-gray-700 transition">
                    {{ __('Reset') }}
                </a>
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
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Total Respons') }}</div>
            <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $total }}</div>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Rata-rata Skor') }}</div>
            <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $avg ?? '—' }}</div>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Skor Tertinggi') }}</div>
            <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $max ?? '—' }}</div>
        </div>
        <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">
            <div class="text-xs text-gray-500 dark:text-gray-400">{{ __('Skor Terendah') }}</div>
            <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $min ?? '—' }}</div>
        </div>
    </div>

    {{-- Tombol Hapus Semua Tidak Lulus --}}
    <div class="mb-4">
        <form action="{{ route('posttest.report.deleteAllFailed', ['session' => $session->slug]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus semua hasil post test yang tidak lulus?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded">
                Hapus Semua Tidak Lulus
            </button>
        </form>
    </div>

    {{-- Tabel Hasil --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        @if ($results->isEmpty())
            <div class="text-center py-12 text-gray-600 dark:text-gray-300">
                {{ __('Belum ada hasil untuk sesi ini.') }}
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
                                {{ __('Nama') }}</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Perusahaan</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Cabang</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Jabatan</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Skor') }}</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Aksi</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                {{ __('Tanggal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($results as $i => $r)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ ($results->firstItem() ?? 1) + $i }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ optional($r->user)->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $r->user->nama_perusahaan }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $r->user->cabang }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $r->user->jabatan }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $r->score }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                @if($r->score >= 60)
                                    <span class="text-green-600 font-semibold">{{ 'Lulus' }}</span>
                                @else
                                    <span class="text-red-600 font-semibold">{{ 'Tidak Lulus' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                @if($r->score < 60)
                                <form action="{{ route('posttest.report.delete', ['session' => $session->slug, 'result' => $r->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus hasil post test user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">Hapus</button>
                                </form>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                {{ optional($r->created_at)->format('Y-m-d H:i') }}
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $results->links() }}
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    @php
        $kantorCabang = [
            'RFB' => [
                'Palembang',
                'Balikpapan',
                'Solo',
                'Jakarta DBS Tower',
                'Jakarta AXA 1',
                'Jakarta AXA 2',
                'Jakarta AXA 3',
                'Medan',
                'Semarang',
                'Surabaya Pakuwon',
                'Surabaya Ciputra',
                'Pekanbaru',
                'Bandung',
                'Yogyakarta',
            ],
            'SGB' => ['Jakarta', 'Semarang', 'Makassar'],
            'KPF' => ['Jakarta', 'Yogyakarta', 'Bali', 'Makassar', 'Bandung', 'Semarang'],
            'EWF' => [
                'SCC Jakarta',
                'Cyber 2 Jakarta',
                'Surabaya Trilium',
                'Manado',
                'Semarang',
                'Surabaya Praxis',
                'Cirebon',
            ],
            'BPF' => [
                'Equity Tower Jakarta',
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
            ],
        ];
        $companyToRole = [
            'PT Rifan Financindo Berjangka' => 'RFB',
            'PT Solid Gold Berjangka' => 'SGB',
            'PT Kontak Perkasa Futures' => 'KPF',
            'PT Best Profit Futures' => 'BPF',
            'PT Equity World Futures' => 'EWF',
        ];
        $selectedCabang = $filters['branch'] ?? request('branch', '');
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataCabang = @json($kantorCabang);
            const companyToRole = @json($companyToRole);
            const selectedCabang = @json($selectedCabang);
            const companySelect = document.getElementById('company');
            const branchSelect = document.getElementById('branch');

            function updateBranchOptions(companyName) {
                branchSelect.innerHTML = '<option value="">Semua Cabang</option>';
                if (companyName && companyToRole[companyName]) {
                    const roleKey = companyToRole[companyName];
                    if (dataCabang[roleKey]) {
                        dataCabang[roleKey].forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = c;
                            opt.textContent = c;
                            if (c === selectedCabang) opt.selected = true;
                            branchSelect.appendChild(opt);
                        });
                    }
                }
            }

            if (companySelect && companySelect.value) {
                updateBranchOptions(companySelect.value);
            }

            companySelect?.addEventListener('change', function() {
                updateBranchOptions(this.value);
            });
        });
    </script>
@endsection
