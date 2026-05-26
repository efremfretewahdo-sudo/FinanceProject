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

class TransactionsSheet implements FromQuery, WithTitle, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    public function __construct(
        private readonly User   $user,
        private readonly string $type,   // 'income' or 'expense'
        private readonly string $sheetTitle,
    ) {}

    public function title(): string
    {
        return $this->sheetTitle;
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 30, 'C' => 14, 'D' => 20, 'E' => 14, 'F' => 40];
    }

    public function query()
    {
        return \App\Models\Transaction::query()
            ->with('category')
            ->where('user_id', $this->user->id)
            ->where('type', $this->type)
            ->where(fn($q) => $q->whereNull('source_type')->orWhere('source_type', '!=', 'other_income'))
            ->orderBy('transaction_date', 'desc');
    }

    public function headings(): array
    {
        return ['#', 'Title', 'Amount ($)', 'Category', 'Date', 'Description'];
    }

    public function map($transaction): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $transaction->title,
            number_format((float) $transaction->amount, 2),
            $transaction->category?->name ?? '—',
            $transaction->transaction_date?->format('Y-m-d') ?? '—',
            $transaction->description ?? '',
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        $headerColor = $this->type === 'income' ? '14532d' : '7f1d1d';
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $headerColor]],
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
