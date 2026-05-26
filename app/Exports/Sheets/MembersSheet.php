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

class MembersSheet implements FromQuery, WithTitle, WithHeadings, WithStyles, WithColumnWidths, WithMapping
{
    public function __construct(private readonly User $user) {}

    public function title(): string
    {
        return 'Members';
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 28, 'C' => 18, 'D' => 20, 'E' => 18, 'F' => 14, 'G' => 14, 'H' => 30];
    }

    public function query()
    {
        return \App\Models\Member::query()
            ->where('user_id', $this->user->id)
            ->orderBy('joined_date', 'desc');
    }

    public function headings(): array
    {
        return ['#', 'Full Name', 'Phone', 'Zone', 'Membership Type', 'Status', 'Joined Date', 'Notes'];
    }

    public function map($member): array
    {
        static $i = 0;
        $i++;
        return [
            $i,
            $member->full_name,
            $member->phone ?? '—',
            $member->zone ?? '—',
            $member->membership_type ?? '—',
            ucfirst($member->status ?? 'active'),
            $member->joined_date?->format('Y-m-d') ?? '—',
            $member->notes ?? '',
        ];
    }

    public function styles(Worksheet $sheet): void
    {
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0f172a']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $lastRow = $sheet->getHighestRow();
        if ($lastRow > 1) {
            for ($row = 2; $row <= $lastRow; $row++) {
                $color = ($row % 2 === 0) ? 'f8fafc' : 'FFFFFF';
                $sheet->getStyle("A{$row}:H{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB($color);
            }
        }

        $sheet->getStyle("A1:H{$lastRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
            ->getColor()->setRGB('e2e8f0');
    }
}
