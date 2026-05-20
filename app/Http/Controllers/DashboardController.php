<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Single source of truth: transactions table (includes payments + other_income + manual)
        $totalIncome      = (float) $user->transactions()->where('type', 'income')->sum('amount');
        $totalExpense     = (float) $user->transactions()->where('type', 'expense')->sum('amount');
        $totalOtherIncome = (float) $user->transactions()->where('type', 'income')->where('source_type', 'other_income')->sum('amount');
        $netPosition      = $totalIncome - $totalExpense;
        $totalMembers     = Member::where('user_id', $user->id)->count();

        $recentTransactions = $user->transactions()
            ->with('category')
            ->latest('transaction_date')
            ->take(6)
            ->get();

        // 12-month Income Flow
        $incomeFlow  = [];
        $targetFlow  = [];
        $monthLabels = [];
        $avgMonthlyTarget = $totalIncome > 0 ? round(($totalIncome / 12) * 1.3) : 5000;

        for ($m = 1; $m <= 12; $m++) {
            $monthLabels[] = date('M', mktime(0, 0, 0, $m, 1));
            $incomeFlow[]  = (float) $user->transactions()
                ->where('type', 'income')
                ->whereYear('transaction_date', now()->year)
                ->whereMonth('transaction_date', $m)
                ->sum('amount');
            $targetFlow[]  = $avgMonthlyTarget;
        }

        // Category expense breakdown (current month)
        $categoryBreakdown = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->with('category')
            ->get()
            ->groupBy('category_id')
            ->map(fn($g) => [
                'name'  => $g->first()->category?->name ?? 'Uncategorized',
                'color' => $g->first()->category?->color ?? '#10b981',
                'total' => (float) $g->sum('amount'),
            ])
            ->values();

        $totalExpenses = $totalExpense;

        return view('dashboard', compact(
            'totalIncome', 'totalExpense', 'totalExpenses', 'totalOtherIncome',
            'netPosition', 'totalMembers',
            'recentTransactions', 'monthLabels', 'incomeFlow', 'targetFlow',
            'categoryBreakdown'
        ));
    }
}
