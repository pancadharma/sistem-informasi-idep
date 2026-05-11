<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TimesheetRecapExport implements FromArray, WithEvents
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return []; // kita render manual di AfterSheet
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // --- Definisi Style ---
                $headerTableStyle = [
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD3D3D3'], // Light gray
                    ],
                ];
                $boldStyle = ['font' => ['bold' => true]];
                $leftAlignStyle = ['alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT]];

                // --- 1. HEADER INFO ---
                $sheet->setCellValue('A1', 'Nama');
                $sheet->setCellValue('B1', $this->data['user']->nama);
                $sheet->setCellValue('A2', 'Divisi');
                $sheet->setCellValue('B2', $this->data['user']->jabatan->divisi->nama ?? '-');
                $sheet->setCellValue('A3', 'Jabatan');
                $sheet->setCellValue('B3', $this->data['user']->jabatan->nama ?? '-');
                $bulan = Carbon::create($this->data['year'], $this->data['month'])->translatedFormat('F Y');
                $sheet->setCellValue('A4', 'Periode');
                $sheet->setCellValue('B4', $bulan);
                $sheet->setCellValue('A5', 'Status');
                $sheet->setCellValue('B5', ucfirst($this->data['status']));
                $sheet->getStyle('A1:A5')->applyFromArray($boldStyle);

                // --- 2. TABLE HEADER ---
                $startRow = 7;
                $daysInMonth = $this->data['daysInMonth'];
                $totalColIndex = $daysInMonth + 2;
                $lastDataColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($daysInMonth + 1);
                $totalColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalColIndex);

                // Baris 1 dari header tabel
                $sheet->mergeCells("A{$startRow}:A".($startRow + 1));
                $sheet->setCellValue("A{$startRow}", "Program");
                $sheet->mergeCells("B{$startRow}:{$lastDataColLetter}{$startRow}");
                $sheet->setCellValue("B{$startRow}", "Calendar Day");
                $sheet->mergeCells("{$totalColLetter}{$startRow}:{$totalColLetter}".($startRow + 1));
                $sheet->setCellValue("{$totalColLetter}{$startRow}", "Total Hari");

                // Baris 2 dari header tabel (nomor hari)
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $sheet->setCellValueByColumnAndRow($d + 1, $startRow + 1, $d);
                }

                // --- 3. ISI DATA ---
                $currentRow = $startRow + 2;
                foreach ($this->data['matrix'] as $program => $values) {
                    $sheet->setCellValue("A{$currentRow}", $program);
                    for ($d = 1; $d <= $daysInMonth; $d++) {
                        $col = $d + 1;
                        $v = $values[$d] ?? ($this->data['nonWorkingMarker'][$d] ?? '');
                        $sheet->setCellValueByColumnAndRow($col, $currentRow, $v);
                    }
                    $sheet->setCellValueByColumnAndRow($totalColIndex, $currentRow, $values['total'] ?? 0);
                    $sheet->getStyle("{$totalColLetter}{$currentRow}")->applyFromArray($boldStyle);
                    $currentRow++;
                }

                // --- 4. GRAND TOTAL ---
                $grandTotalRow = $currentRow;
                $sheet->setCellValue("A{$grandTotalRow}", "Total Jam");
                for ($d = 1; $d <= $daysInMonth; $d++) {
                    $sheet->setCellValueByColumnAndRow($d + 1, $grandTotalRow, $this->data['grandDaily'][$d] ?? 0);
                }
                $sheet->setCellValueByColumnAndRow($totalColIndex, $grandTotalRow, $this->data['grandTotal']);
                $sheet->getStyle("A{$grandTotalRow}:{$totalColLetter}{$grandTotalRow}")->applyFromArray($boldStyle);

                // --- 5. STYLING TABEL ---
                $tableEndRow = $grandTotalRow;
                $tableRange = "A{$startRow}:{$totalColLetter}{$tableEndRow}";

                $sheet->getStyle($tableRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                // Style khusus header tabel
                $sheet->getStyle("A{$startRow}:{$totalColLetter}".($startRow + 1))->applyFromArray($headerTableStyle);
                // Rata kiri untuk kolom program
                $sheet->getStyle("A".($startRow + 2).":A{$tableEndRow}")->applyFromArray($leftAlignStyle);
                
                // --- 6. EQUIVALENT DAYS & LEGEND ---
                $currentRow = $tableEndRow + 2;
                $sheet->setCellValue("A{$currentRow}", "Equivalent Days (8 Jam/Hari):");
                $sheet->setCellValue("B{$currentRow}", $this->data['equivalent']);
                $sheet->getStyle("A{$currentRow}")->applyFromArray($boldStyle);
                
                $currentRow += 2;
                $sheet->setCellValue("A{$currentRow}", "Legend:");
                $sheet->getStyle("A{$currentRow}")->applyFromArray($boldStyle);
                $sheet->setCellValue("A".($currentRow+1), "C : Cuti");
                $sheet->setCellValue("A".($currentRow+2), "D : DOC");
                $sheet->setCellValue("A".($currentRow+3), "L : Libur");
                $sheet->setCellValue("A".($currentRow+4), "S : Sakit");
                $sheet->getStyle("A".($currentRow+1).":A".($currentRow+4))->applyFromArray($leftAlignStyle);
                
                // --- 7. AUTO-SIZE COLUMNS ---
                $sheet->getColumnDimension('A')->setAutoSize(true);
                for ($i = 2; $i <= $totalColIndex - 1; $i++) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                    $sheet->getColumnDimension($colLetter)->setWidth(5);
                }
                $sheet->getColumnDimension($totalColLetter)->setAutoSize(true);
            }
        ];
    }
}