<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ApupptSertifikatExport implements FromQuery, WithHeadings, WithMapping
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
        return ['No', 'Nama', 'Perusahaan', 'Cabang', 'Kategori', 'Nilai', 'Tanggal Sertifikat', 'UUID Sertifikat'];
    }

    public function map($sertifikat): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $perusahaan = match ($sertifikat->user->role ?? '') {
            'Trainer (SGB)' => 'PT Solid Gold Berjangka',
            'Trainer (RFB)' => 'PT Rifan Financindo Berjangka',
            'Trainer (EWF)' => 'PT Equity World Futures',
            'Trainer (BPF)' => 'PT Best Profit Futures',
            'Trainer (KPF)' => 'PT Kontak Perkasa Futures',
            default => $sertifikat->user->role ?? '-',
        };

        return [
            $rowNumber,
            $sertifikat->user->name ?? '-',
            $perusahaan,
            $sertifikat->user->cabang ?? '-',
            optional(optional($sertifikat->postTestResult)->session)->tipe ?? '-',
            ($sertifikat->average_score ?? '-') . '/100',
            $sertifikat->awarded_at ? $sertifikat->awarded_at->format('d F Y H:i') : '-',
            $sertifikat->certificate_uuid ?? '-',
        ];
    }
}