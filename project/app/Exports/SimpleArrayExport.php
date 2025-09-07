<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SimpleArrayExport implements FromArray, WithHeadings
{
    protected array $rows;
    protected array $headings;

    public function __construct(array $headings, array $rows)
    {
        $this->headings = $headings;
        $this->rows = $rows;
    }

    public function array(): array
    {
        // Ensure rows are simple arrays in the correct order of headings if associative
        if (!empty($this->rows) && is_array($this->rows[0])) {
            $first = $this->rows[0];
            // If associative arrays, preserve order by headings when keys exist
            if (array_keys($first) !== range(0, count($first) - 1)) {
                return array_map(function ($r) {
                    return array_values($r);
                }, $this->rows);
            }
        }
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }
}

