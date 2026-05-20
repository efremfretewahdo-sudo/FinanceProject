<x-app-layout>
    <div class="py-8" x-data="{showForm:false}">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs font-semibold text-rose-500 uppercase tracking-widest mb-1">Finance</p>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Expenses</h1>
                <p class="text-sm text-slate-500 mt-1">All outgoing payments and expenditures</p>
            </div>
            <button @click="showForm=!showForm" class="inline-flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-lg shadow-rose-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Log Expense
            </button>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-rose-600">${{ number_format($totalExpenses, 2) }}</p>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide font-medium">Total Expenses</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-slate-700">{{ $expenseCount }}</p>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide font-medium">Transactions</p>
            </div>
        </div>

        {{-- Log Expense Form --}}
        <div x-show="showForm" x-transition class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h3 class="font-bold text-slate-800 mb-4">Log New Expense</h3>
            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf
                <input type="hidden" name="type" value="expense">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Title *</label>
                        <input type="text" name="title" required placeholder="e.g. Office Rent, Salaries" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-rose-500 focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Amount *</label>
                        <input type="number" name="amount" step="0.01" min="0.01" required placeholder="0.00" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-rose-500 focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Date *</label>
                        <input type="date" name="transaction_date" value="{{ now()->toDateString() }}" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-rose-500 focus:border-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Category</label>
                        <select name="category_id" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-rose-500 focus:border-rose-500">
                            <option value="">— No Category —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Description</label>
                        <input type="text" name="description" placeholder="Optional description..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-rose-500 focus:border-rose-500">
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-4">
                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Save Expense</button>
                    <button type="button" @click="showForm=false" class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2.5">Cancel</button>
                </div>
            </form>
        </div>

        {{-- Expenses Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            @if($expenses->isNotEmpty())
            <table class="w-full text-sm">
                <thead><tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">Title</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">Amount</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden md:table-cell">Category</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden lg:table-cell">Date</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @foreach($expenses as $expense)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-slate-800">{{ $expense->title }}</p>
                        @if($expense->description)
                        <p class="text-xs text-slate-400">{{ $expense->description }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 font-bold text-rose-600">${{ number_format($expense->amount, 2) }}</td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        @if($expense->category)
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">{{ $expense->category->name }}</span>
                        @else
                        <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-slate-400 text-xs hidden lg:table-cell">{{ $expense->transaction_date->format('M d, Y') }}</td>
                    <td class="px-5 py-4">
                        <form method="POST" action="{{ route('transactions.destroy', $expense) }}" onsubmit="return confirm('Delete this expense?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-slate-100">{{ $expenses->links() }}</div>
            @else
            <div class="flex flex-col items-center justify-center py-16 text-slate-300">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                <p class="text-sm font-medium text-slate-400">No expenses logged yet</p>
                <button @click="showForm=true" class="mt-3 text-sm text-rose-500 hover:underline">Log first expense</button>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
