@props(['user'])

@php
    $requiredFields = [
        'name' => 'Nama Lengkap',
        'email' => 'Email',
        'jenis_kelamin' => 'Jenis Kelamin',
        'tempat_lahir' => 'Tempat Lahir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'warga_negara' => 'Warga Negara',
        'alamat' => 'Alamat',
        'no_tlp' => 'Nomor Telepon',
        'pekerjaan' => 'Pekerjaan',
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
    <ul {{ $attributes->merge(['class' => 'mt-4 list-disc list-inside text-red-500']) }}>
        @foreach ($missingFields as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
