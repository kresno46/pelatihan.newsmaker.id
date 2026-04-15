<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class ApupptSertifikatPerCabangExport implements FromQuery, WithHeadings, WithMapping, WithTitle
{
    protected $query;

    protected $cabang;

    public function __construct(Builder $query, string $cabang)
    {
        $this->query = $query;
        $this->cabang = $cabang;
    }

    public function query()
    {
        return $this->query;
    }

    public function title(): string
    {
        return 'Sertifikat - ' . $this->cabang;
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