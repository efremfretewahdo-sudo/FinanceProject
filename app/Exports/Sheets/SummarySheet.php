<?php

namespace App\Exports\Sheets;

use App\Models\Member;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SummarySheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(private readonly User $user) {}

    public function title(): string
    {
        return 'Summary';
    }

    public function columnWidths(): array
    {
        return ['A' => 30, 'B' => 22];
    }

    public function array(): array
    {
        $u    = $this->user;
        $now  = now();

        $totalIncome      = (float) $u->transactions()->where('type', 'income')->sum('amount');
        $totalExpense     = (float) $u->transactions()->where('type', 'expense')->sum('amount');
        $totalOtherIncome = (float) $u->transactions()->where('type', 'income')->where('source_type', 'other_income')->sum('amount');
        $netBalance       = $totalIncome - $totalExpense;
        $totalMembers     = Member::where('user_id', $u->id)->count();

        $monthIncome  = (float) $u->transactions()->where('type', 'income')
            ->whereYear('transaction_date', $now->year)->whereMonth('transaction_date', $now->month)->sum('amount');
        $monthExpense = (float) $u->transactions()->where('type', 'expense')
            ->whereYear('transaction_date', $now->year)->whereMonth('transaction_date', $now->month)->sum('amount');

        return [
            ['ADAM44 — Financial Report'],
            [''],
            ['Generated',       $now->format('F j, Y  H:i:s')],
            ['Account Name',    $u->name],
            ['Email',           $u->email],
            ['Report Period',   'All time up to ' . $now->format('Y-m-d')],
            [''],
            ['── ALL-TIME SUMMARY ──────────────────', ''],
            ['Total Income',    '$' . number_format($totalIncome, 2)],
            ['Total Expenses',  '$' . number_format($totalExpense, 2)],
            ['Other Income',    '$' . number_format($totalOtherIncome, 2)],
            ['Net Balance',     ($netBalance >= 0 ? '+' : '-') . '$' . number_format(abs($netBalance), 2)],
            ['Total Members',   $totalMembers],
            [''],
            ['── CURRENT MONTH (' . $now->format('F Y') . ') ──', ''],
            ['Income',          '$' . number_format($monthIncome, 2)],
            ['Expenses',        '$' . number_format($monthExpense, 2)],
            ['Net This Month',  ($monthIncome - $monthExpense >= 0 ? '+' : '-') . '$' . number_format(abs($monthIncome - $monthExpense), 2)],
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        // Title row
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0f172a']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Section headers (rows 8 and 15)
        foreach ([8, 15] as $row) {
            $sheet->mergeCells("A{$row}:B{$row}");
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '334155']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'f1f5f9']],
            ]);
        }

        // Label column — bold
        $sheet->getStyle('A3:A18')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '475569']],
        ]);

        // Net Balance value — conditional colour
        $netBalance = (float) $this->user->transactions()->where('type', 'income')->sum('amount')
                    - (float) $this->user->transactions()->where('type', 'expense')->sum('amount');
        $sheet->getStyle('B12')->getFont()->setColor(
            new \PhpOffice\PhpSpreadsheet\Style\Color($netBalance >= 0 ? 'FF16a34a' : 'FFdc2626')
        );
        $sheet->getStyle('B12')->getFont()->setBold(true);
    }
}
