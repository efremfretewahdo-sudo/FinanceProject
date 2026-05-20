<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Auth::user()->transactions()
            ->with('category')
            ->where('type', 'expense')
            ->latest('transaction_date')
            ->paginate(15);

        $totalExpenses = Auth::user()->transactions()
            ->where('type', 'expense')
            ->sum('amount');

        $expenseCount = Auth::user()->transactions()
            ->where('type', 'expense')
            ->count();

        $categories = Auth::user()->categories()->get();

        return view('pages.expenses', compact('expenses', 'totalExpenses', 'expenseCount', 'categories'));
    }
}
