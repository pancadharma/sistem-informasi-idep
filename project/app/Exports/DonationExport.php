<?php
// app/Exports/DonationExport.php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DonationExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $donations;

    public function __construct($donations)
    {
        $this->donations = $donations;
    }

    public function collection()
    {
        return $this->donations;
    }

    public function headings(): array
    {
        return [
            'No',
            'Pendonor',
            'Kategori Pendonor',
            'Program',
            'Nilai Donasi',
            'Tanggal'
        ];
    }

    public function map($donation): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $donation->pendonor?->nama ?? '-',
            $donation->pendonor?->mpendonnorkategori?->nama ?? '-',
            $donation->program?->nama ?? '-',
            $donation->nilaidonasi,
            $donation->created_at?->format('d/m/Y') ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}