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
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                /* ======================================
                 * 1. HEADER INFO
                 * ====================================== */

                $sheet->setCellValue('A1', 'Nama');
                $sheet->setCellValue('B1', $this->data['user']->nama);

                $sheet->setCellValue('A2', 'Divisi');
                $sheet->setCellValue(
                    'B2',
                    $this->data['user']->jabatan->divisi->nama ?? '-'
                );

                $sheet->setCellValue('A3', 'Jabatan');
                $sheet->setCellValue(
                    'B3',
                    $this->data['user']->jabatan->nama ?? '-'
                );

                $bulan = Carbon::create(
                    $this->data['year'],
                    $this->data['month']
                )->translatedFormat('F Y');

                $sheet->setCellValue('A4', 'Periode');
                $sheet->setCellValue('B4', $bulan);

                /* ======================================
                 * 2. HEADER TABLE
                 * ====================================== */

                $startRow = 6;

                $sheet->setCellValue("A{$startRow}", "Program");

                // Nomor hari
                for ($d = 1; $d <= $this->data['daysInMonth']; $d++) {
                    $sheet->setCellValueByColumnAndRow($d + 1, $startRow, $d);
                }

                $totalCol = $this->data['daysInMonth'] + 2;

                $sheet->setCellValueByColumnAndRow(
                    $totalCol,
                    $startRow,
                    "Total"
                );

                /* ======================================
                 * 3. ISI DATA
                 * ====================================== */

                $row = $startRow + 1;

                foreach ($this->data['matrix'] as $program => $values) {

                    $sheet->setCellValue("A{$row}", $program);

                    for ($d = 1; $d <= $this->data['daysInMonth']; $d++) {

                        $col = $d + 1;

                        if (isset($values[$d])) {
                            $v = $values[$d];

                        } elseif (isset($this->data['nonWorkingMarker'][$d])) {
                            $v = $this->data['nonWorkingMarker'][$d];

                        } else {
                            $v = '';
                        }

                        $sheet->setCellValueByColumnAndRow($col, $row, $v);
                    }

                    $sheet->setCellValueByColumnAndRow(
                        $totalCol,
                        $row,
                        $values['total'] ?? 0
                    );

                    $row++;
                }

                /* ======================================
                 * 4. GRAND TOTAL
                 * ====================================== */

                $sheet->setCellValue("A{$row}", "Grand Total");

                for ($d = 1; $d <= $this->data['daysInMonth']; $d++) {
                    $sheet->setCellValueByColumnAndRow(
                        $d + 1,
                        $row,
                        $this->data['grandDaily'][$d] ?? 0
                    );
                }

                $sheet->setCellValueByColumnAndRow(
                    $totalCol,
                    $row,
                    $this->data['grandTotal']
                );



                /* ======================================
                 * 6. STYLE AMAN
                 * ====================================== */

                $lastColLetter = $sheet->getHighestColumn();

                $sheet->getStyle("A{$startRow}:{$lastColLetter}{$row}")
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],

                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER,
                        ],
                    ]);

                    // auto width (AMAN sampai ratusan kolom)
                    for ($i = 1; $i <= $totalCol; $i++) {
                        $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
                        $sheet->getColumnDimension($colLetter)->setAutoSize(true);
                    }
                                    $row += 2;

                /* ======================================
                 * 5. EQUIVALENT DAYS
                 * ====================================== */

                $sheet->setCellValue("A{$row}", "Equivalent Days (÷8)");
                $sheet->setCellValue("B{$row}", $this->data['equivalent'] . ' hari');
                    /* ========================================
                    * 4. LEGEND MARKER (C D L S)
                    * ======================================== */

                    // cari baris terakhir yang terisi
                    $lastRow = $sheet->getHighestRow() + 2;

                    $sheet->setCellValue("A{$lastRow}", "Legend:");
                    $sheet->getStyle("A{$lastRow}")
                        ->getFont()->setBold(true);

                    // isi legend
                    $sheet->setCellValue("A".($lastRow+1), "C : Cuti");
                    $sheet->setCellValue("A".($lastRow+2), "D : DOC");
                    $sheet->setCellValue("A".($lastRow+3), "L : Libur");
                    $sheet->setCellValue("A".($lastRow+4), "S : Sakit");

                    // style sederhana biar rapi
                    $sheet->getStyle("A".($lastRow+1).":A".($lastRow+4))
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_LEFT);
            }
        ];

    }
}