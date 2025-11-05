<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SertifikatExport implements FromQuery, WithHeadings, WithMapping
{
    protected $query;

    public function __construct(Builder $query)
    {
        $this->query = $query;
    }

    public function query()
    {
        return $this->query;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Perusahaan',
            'Cabang',
            'Nilai',
            'Tanggal Sertifikat',
            'UUID Sertifikat',
        ];
    }

    public function map($sertifikat): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        // Map perusahaan role to display name
        $perusahaan = '';
        switch ($sertifikat->user->role ?? '') {
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
                $perusahaan = $sertifikat->user->role ?? '-';
                break;
        }

        return [
            $rowNumber,
            $sertifikat->user->name ?? '-',
            $perusahaan,
            $sertifikat->user->cabang ?? '-',
            $sertifikat->average_score.'/100',
            $sertifikat->awarded_at ? $sertifikat->awarded_at->format('d F Y H:i') : '-',
            $sertifikat->certificate_uuid ?? '-',
        ];
    }
}
