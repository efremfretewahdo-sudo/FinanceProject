<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->transactions()->with('category')->latest('transaction_date');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $transactions = $query->paginate(15)->withQueryString();
        $categories = Auth::user()->categories()->get();

        return view('transactions.index', compact('transactions', 'categories'));
    }

    public function create()
    {
        $categories = Auth::user()->categories()->get();
        return view('transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'amount'           => 'required|numeric|min:0.01',
            'type'             => 'required|in:income,expense',
            'category_id'      => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        Auth::user()->transactions()->create($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction added successfully.');
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);
        $categories = Auth::user()->categories()->get();
        return view('transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'amount'           => 'required|numeric|min:0.01',
            'type'             => 'required|in:income,expense',
            'category_id'      => 'nullable|exists:categories,id',
            'description'      => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}
