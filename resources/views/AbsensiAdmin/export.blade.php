@php
    function getNamaPerusahaan($role)
    {
        return match ($role) {
            'Trainer (SGB)' => 'PT SGB',
            'Trainer (KPF)' => 'KPF Makassar',
            'Trainer (RFB)' => 'RFB Jakarta',
            'Trainer (BPF)' => 'BPF Medan',
            'Trainer (EWF)' => 'EWF Bandung',
            default => 'Lainnya',
        };
    }
@endphp

<div style="padding: 24px; font-family: Arial, sans-serif;">
    <h2 style="font-size: 24px; font-weight: 600; color: #333; margin-bottom: 4px;">
        Daftar Kehadiran - {{ $jadwal->title }}
    </h2>
    <p style="font-size: 14px; color: #666; margin-bottom: 20px;">
        Berikut adalah data absensi peserta dari <strong>{{ $jadwal->title }}</strong>.
        Tabel ini mencakup informasi nama peserta, asal perusahaan, serta waktu absensi yang tercatat.
    </p>

    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead>
            <tr style="background-color: #00b7ff; color: #fff; text-align: left;">
                <th style="padding: 12px; border: 1px solid #ddd;">Nama</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Perusahaan</th>
                <th style="padding: 12px; border: 1px solid #ddd;">Waktu Absen</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($absensiList as $absen)
                <tr style="background-color: #fff;">
                    <td style="padding: 10px 12px; border: 1px solid #ddd;">
                        {{ optional($absen->user)->name ?? '-' }}
                    </td>
                    <td style="padding: 10px 12px; border: 1px solid #ddd;">
                        {{ getNamaPerusahaan(optional($absen->user)->role ?? '-') }}
                    </td>
                    <td style="padding: 10px 12px; border: 1px solid #ddd;">
                        {{ $absen->waktu_absen ? \Carbon\Carbon::parse($absen->waktu_absen)->format('d F Y, H:i') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center; padding: 12px; border: 1px solid #ddd; color: #999;">
                        Tidak ada data absensi.
                    </td>
                </tr>
            @endforelse
            </
