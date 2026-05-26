<?php

namespace App\Exports\Sheets;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class OtherIncomeSheet implements FromQuery, WithTitle, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    public function __construct(private readonly User $user) {}

    public function title(): string
    {
        return 'Other Income';
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 28, 'C' => 14, 'D' => 20, 'E' => 20, 'F' => 40];
    }

    public function query()
    {
        return \App\Models\OtherIncome::query()
            ->where('user_id', $this->user->id)
            ->orderBy('income_date', 'desc');
    }

    public function headings(): array
    {
        return ['#', 'Source', 'Amount ($)', 'Category', 'Date', 'Description'];
    }

    public function map($item): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $item->source,
            number_format((float) $item->amount, 2),
            $item->category ?? '—',
            $item->income_date?->format('Y-m-d') ?? '—',
            $item->description ?? '',
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1e3a5f']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle('C2:C' . $sheet->getHighestRow())
            ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 1) {
            for ($row = 2; $row <= $lastRow; $row++) {
                $color = ($row % 2 === 0) ? 'f8fafc' : 'FFFFFF';
                $sheet->getStyle("A{$row}:F{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($color);
            }
        }

        $sheet->getStyle("A1:F{$lastRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            ->getColor()->setRGB('e2e8f0');
    }
}
