@extends('layouts.app')

@section('namePage', 'Profil Saya')

@section('content')
    @php
        $jabatanMap = [
            'Admin' => 'Administrator',
            'Owner' => 'Owner',
            'CEO' => 'Chief Executive Officer',
            'CBO' => 'Chief Business Officer',
            'BrM' => 'Branch Manager',
            'VBM' => 'Vice Business Manager',
            'SVBM' => 'Senior Vice Business Manager',
            'SEM' => 'Senior Executive Manager',
            'EM' => 'Executive Manager',
            'SBM' => 'Senior Business Manager',
            'BsM' => 'Business Manager',
            'SBC' => 'Senior Business Consultant',
            'BC' => 'Business Consultant',
        ];

        $profileFields = [
            ['label' => 'Nama Lengkap', 'value' => $user->name],
            ['label' => 'Email', 'value' => $user->email],
            ['label' => 'Jabatan', 'value' => $jabatanMap[$user->jabatan] ?? $user->jabatan],
            ['label' => 'Jenis Kelamin', 'value' => $user->jenis_kelamin],
            ['label' => 'Tempat Lahir', 'value' => $user->tempat_lahir],
            [
                'label' => 'Tanggal Lahir',
                'value' => $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') : null,
            ],
            ['label' => 'Alamat', 'value' => $user->alamat],
            ['label' => 'Nomor Telepon', 'value' => $user->no_tlp],
            ['label' => 'Perusahaan', 'value' => $user->role],
            ['label' => 'Cabang', 'value' => $user->cabang],
        ];
    @endphp

    <div class="space-y-8">
        <div
            class="relative overflow-hidden rounded-3xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="absolute -right-20 -top-20 h-48 w-48 rounded-full bg-amber-100/70 blur-3xl dark:bg-amber-400/20">
            </div>
            <div class="absolute -left-24 -bottom-24 h-56 w-56 rounded-full bg-sky-100/70 blur-3xl dark:bg-sky-400/20"></div>

            <div class="relative flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-900 text-white dark:bg-white dark:text-slate-900">
                        <i class="fa-regular fa-circle-user text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Profil Saya</h1>
                        <div
                            class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-3 py-1 text-xs text-white dark:bg-white dark:text-slate-900">
                            <i class="fa-solid fa-shield-halved text-[10px]"></i>
                            Data aman &amp; terenkripsi
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">
            <div
                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 lg:col-span-2">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Detail Profil</h2>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Terakhir diperbarui:
                        {{ optional($user->updated_at)->format('d M Y') }}</span>
                </div>
                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    @foreach ($profileFields as $field)
                        <div
                            class="rounded-xl border border-gray-200/80 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                            <div class="text-[11px] uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                {{ $field['label'] }}
                            </div>
                            <div class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $field['value'] ?: '-' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-5">
                <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Status Kelengkapan</h3>
                    @php
                        $filled = collect($profileFields)->filter(fn($f) => !empty($f['value']))->count();
                        $total = count($profileFields);
                        $percent = $total > 0 ? round(($filled / $total) * 100) : 0;
                    @endphp
                    <div class="mt-3 text-2xl font-semibold text-gray-900 dark:text-white">{{ $percent }}%</div>
                    <div class="mt-3 h-2 rounded-full bg-gray-200 dark:bg-gray-700">
                        <div class="h-2 rounded-full bg-emerald-500" style="width: {{ $percent }}%"></div>
                    </div>
                    <p class="mt-3 text-sm text-gray-600 dark:text-gray-300">
                        Lengkapi profil untuk akses fitur pelatihan dan sertifikat.
                    </p>
                </div>

                <div
                    class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Aksi Cepat</h3>
                    <div class="mt-4 space-y-3">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-900/40">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-pen-to-square text-blue-600 dark:text-blue-400"></i>
                                Edit data profil
                            </span>
                            <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                        </a>
                        <a href="{{ route('profile.password') }}"
                            class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-200 dark:hover:bg-gray-900/40">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid fa-key text-amber-600 dark:text-amber-400"></i>
                                Ganti kata sandi
                            </span>
                            <i class="fa-solid fa-chevron-right text-xs text-gray-400"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
