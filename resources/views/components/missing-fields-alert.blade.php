@props(['user'])

@php
    $requiredFields = [
        'name' => 'Nama Lengkap',
        'email' => 'Email',
        'jenis_kelamin' => 'Jenis Kelamin',
        'tempat_lahir' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        // 'warga_negara' => 'Warga Negara',
        'alamat' => 'Alamat',
        'no_tlp' => 'Nomor Telepon',
        // 'pekerjaan' => 'Pekerjaan',
        'role' => 'Role',
        'cabang' => 'cabang',
    ];

    $missingFields = [];
    foreach ($requiredFields as $field => $label) {
        if (empty($user->$field)) {
            $missingFields[] = "Data $label belum diisi";
        }
    }
@endphp

@if (count($missingFields) > 0)
    <div
        {{ $attributes->merge(['class' => 'mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900 shadow-sm dark:border-amber-900/40 dark:bg-amber-900/20 dark:text-amber-100']) }}>
        <div class="flex items-start gap-3">
            <div
                class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-lg bg-amber-200 text-amber-900 dark:bg-amber-800 dark:text-amber-100">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="flex-1">
                <div class="text-sm font-semibold">Data belum lengkap</div>
                <p class="mt-1 text-xs text-amber-800/90 dark:text-amber-100/80">
                    Lengkapi data berikut agar akses fitur berjalan lancar.
                </p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($missingFields as $message)
                        <span
                            class="inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-medium text-amber-800 ring-1 ring-amber-200 dark:bg-amber-950/40 dark:text-amber-100 dark:ring-amber-800">
                            {{ $message }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
