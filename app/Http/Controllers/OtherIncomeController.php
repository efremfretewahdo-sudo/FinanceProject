<?php

namespace App\Http\Controllers;

use App\Models\OtherIncome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtherIncomeController extends Controller
{
    public function index()
    {
        $incomes = OtherIncome::where('user_id', Auth::id())->latest('income_date')->paginate(15);
        $total   = OtherIncome::where('user_id', Auth::id())->sum('amount');
        return view('pages.other-income', compact('incomes','total'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'source'      => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0.01',
            'income_date' => 'required|date',
            'category'    => 'required|in:grant,donation,investment,interest,rental,other',
            'description' => 'nullable|string',
        ]);
        $data['user_id'] = Auth::id();
        $income = OtherIncome::create($data);
        $income->syncTransaction();
        return back()->with('success', 'ካልእ ኣታዊ ተመዝጊቡ ኣሎ (Other income recorded).');
    }

    public function destroy(OtherIncome $income)
    {
        abort_if($income->user_id !== Auth::id(), 403);
        $income->removeTransaction();
        $income->delete();
        return back()->with('success', 'ኣታዊ ተሰሪዙ ኣሎ (Income deleted).');
    }
}
