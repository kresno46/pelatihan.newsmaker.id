@extends('layouts.app')

@section('namePage', 'Daftar User APUPPT')

@section('content')
    <header class="w-full bg-white dark:bg-gray-800 shadow rounded-lg mb-5 p-4 sm:p-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Daftar User
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    @if ($forcedRole)
                        Menampilkan trainer dari {{ $namaPerusahaan[$forcedRole] ?? $forcedRole }}.
                    @else
                        Menampilkan seluruh trainer APUPPT dari semua PT.
                    @endif
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
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-3" id="filterForm">
            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Cari User</label>
                <input type="text" name="q" value="{{ request('q') }}"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                    placeholder="Nama atau email...">
            </div>

            @if (! $forcedRole)
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Perusahaan</label>
                    <select name="company" id="companySelect"
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">Semua Perusahaan</option>
                        @foreach ($ptOptions as $pt)
                            <option value="{{ $pt }}" {{ $selectedRole === $pt ? 'selected' : '' }}>
                                {{ $namaPerusahaan[$pt] ?? $pt }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Perusahaan</label>
                    <input type="text" value="{{ $namaPerusahaan[$forcedRole] ?? $forcedRole }}" disabled
                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400">
                </div>
            @endif

            <div>
                <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Cabang</label>
                <select name="cabang" id="cabangSelect"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                    <option value="">Semua Cabang</option>
                    @foreach ($cabangOptions as $cabang)
                        <option value="{{ $cabang }}" {{ request('cabang') === $cabang ? 'selected' : '' }}>
                            {{ $cabang }}
                        </option>
                    @endforeach
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
                    <option value="cabang_asc" {{ $sort === 'cabang_asc' ? 'selected' : '' }}>Cabang A-Z</option>
                    <option value="cabang_desc" {{ $sort === 'cabang_desc' ? 'selected' : '' }}>Cabang Z-A</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                    class="w-full md:w-auto px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white text-sm transition">
                    Terapkan
                </button>
                <a href="{{ route('apuppt.user.index') }}"
                    class="w-full md:w-auto px-4 py-2 rounded border bg-red-500 hover:bg-red-600 text-white border-gray-300 dark:border-gray-600 text-sm dark:text-gray-200 dark:hover:bg-gray-700 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Tabel User --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
        @if ($users->isEmpty())
            <div class="text-center py-12 text-gray-600 dark:text-gray-300">
                Belum ada user untuk PT ini.
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
                                Email</th>
                            @if (! $forcedRole)
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Perusahaan</th>
                            @endif
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Cabang</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Bergabung</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($users as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30">
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ ($users->firstItem() ?? 1) + $index }}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $item->email }}</td>
                                @if (! $forcedRole)
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                        {{ $namaPerusahaan[$item->role] ?? $item->role }}</td>
                                @endif
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $item->cabang ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->suspended_at ? 'bg-red-100 text-red-800' : ($item->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $item->suspended_at ? 'Suspended' : ($item->email_verified_at ? 'Terverifikasi' : 'Belum') }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ optional($item->created_at)->translatedFormat('d F Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        const kantorCabang = @json(\App\Http\Controllers\ApupptUserController::KANTOR_CABANG);
        const forcedRole = @json($forcedRole);

        @if (! $forcedRole)
            const companySelect = document.getElementById('companySelect');
            const cabangSelect = document.getElementById('cabangSelect');

            companySelect.addEventListener('change', function() {
                const cabangs = kantorCabang[this.value] || [];
                cabangSelect.innerHTML = '<option value="">Semua Cabang</option>';
                cabangs.forEach(function(cabang) {
                    const opt = document.createElement('option');
                    opt.value = cabang;
                    opt.textContent = cabang;
                    cabangSelect.appendChild(opt);
                });
            });
        @endif
    </script>
@endsection
