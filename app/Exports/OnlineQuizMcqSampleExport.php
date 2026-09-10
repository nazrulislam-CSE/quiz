<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class OnlineQuizMcqSampleExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        return [
            ['What is PHP?', 'Programming Language', 'Database', 'Framework', 'CMS', 0],
            ['What is Laravel?', 'Framework', 'Language', 'Server', 'Tool', 0],
            ['What is MySQL?', 'Database', 'Language', 'Framework', 'CMS', 0],
            ['Which one is a JavaScript framework?', 'Laravel', 'Django', 'React', 'Spring', 2],
        ];
    }

    public function headings(): array
    {
        return ['Question', 'Option 1', 'Option 2', 'Option 3', 'Option 4', 'Correct Answer (0-3)'];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '28A745']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $highestRow = $sheet->getHighestRow();
        $sheet->getStyle('A2:F' . $highestRow)->applyFromArray([
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        $sheet->getStyle('F2:F' . $highestRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getRowDimension(1)->setRowHeight(25);

        return $sheet;
    }
}