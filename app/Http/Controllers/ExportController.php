<?php

namespace App\Http\Controllers;

use App\Exports\UserFinancialsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function downloadFinancials(Request $request): BinaryFileResponse
    {
        $user     = $request->user();
        $filename = 'adam44_financials_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new UserFinancialsExport($user), $filename);
    }
}
