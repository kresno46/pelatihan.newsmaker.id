<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $absensiList;

    public function __construct($absensiList)
    {
        $this->absensiList = $absensiList;
    }

    public function collection()
    {
        return $this->absensiList;
    }

    public function headings(): array
    {
        return ['Nama', 'Perusahaan', 'Cabang', 'Waktu Absen'];
    }

    public function map($absensi): array
    {
        return [
            $absensi->user->name ?? '-',
            $this->getNamaPerusahaan($absensi->user->role ?? '-'),
            $absensi->user->cabang ?? '-',
            $absensi->waktu_absen
                ? \Carbon\Carbon::parse($absensi->waktu_absen)->format('d F Y, H:i')
                : '-',
        ];
    }

    protected function getNamaPerusahaan($role)
    {
        return match ($role) {
            'Trainer (SGB)' => 'PT Solid Gold Berjangka',
            'Trainer (KPF)' => 'PT Kontak Perkasa Futures',
            'Trainer (RFB)' => 'PT Rifan FInancindo Berjangka',
            'Trainer (BPF)' => 'PT Best Profit Futures',
            'Trainer (EWF)' => 'PT Equity World Futures',
            default => 'Lainnya',
        };
    }
}
