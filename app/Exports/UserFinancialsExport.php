<?php

namespace App\Exports;

use App\Exports\Sheets\MembersSheet;
use App\Exports\Sheets\OtherIncomeSheet;
use App\Exports\Sheets\SummarySheet;
use App\Exports\Sheets\TransactionsSheet;
use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UserFinancialsExport implements WithMultipleSheets
{
    public function __construct(private readonly User $user) {}

    public function sheets(): array
    {
        return [
            new SummarySheet($this->user),
            new MembersSheet($this->user),
            new TransactionsSheet($this->user, 'income',  'Inflow'),
            new TransactionsSheet($this->user, 'expense', 'Outflow'),
            new OtherIncomeSheet($this->user),
        ];
    }
}
