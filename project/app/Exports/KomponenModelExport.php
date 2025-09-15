<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KomponenModelExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        // Define your column headings here based on the structure of your $data
        return [
            'Total Component Models',
            'Programs With Components',
            'Geographic Coverage (Provinces)',
            'Geographic Coverage (Districts)',
            'Total Beneficiaries',
            'Program Distribution (Program Name)',
            'Program Distribution (Total Components)',
            'Province Level Summary (Province Name)',
            'Province Level Summary (Component Locations)',
            'District Level Summary (District Name)',
            'District Level Summary (Province Name)',
            'District Level Summary (Component Locations)',
            'Interactive Map Data (Lat)',
            'Interactive Map Data (Long)',
            'Model Distribution (Model Name)',
            'Model Distribution (Total Components)',
            'Model Distribution (Total Quantity)',
            'User Assignment (User Name)',
            'User Assignment (Assigned Components)',
            'Gender Distribution (Jenis Kelamin)',
            'Gender Distribution (Total)',
            'Age Group Analysis (Age Group)',
            'Age Group Analysis (Total)',
            'Program Hierarchy',
            'User Assignment Matrix (User Name)',
            'User Assignment Matrix (Assigned Components)',
        ];
    }
}