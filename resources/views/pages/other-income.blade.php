<x-app-layout>
    <div class="py-8" x-data="{showForm:false}">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs font-semibold text-sky-500 uppercase tracking-widest mb-1">Finance</p>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Other Income</h1>
                <p class="text-sm text-slate-500 mt-1">Grants, donations, investments, and miscellaneous income</p>
            </div>
            <button @click="showForm=!showForm" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-lg shadow-emerald-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Income
            </button>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
                <p class="text-3xl font-bold text-sky-600">${{ number_format($total, 2) }}</p>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide font-medium">Total Other Income</p>
            </div>
        </div>

        {{-- Add Income Form --}}
        <div x-show="showForm" x-transition class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h3 class="font-bold text-slate-800 mb-4">Record Other Income</h3>
            <form method="POST" action="{{ route('other-income.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Source *</label>
                        <input type="text" name="source" required placeholder="e.g. USAID Grant, NGO Donation" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Amount *</label>
                        <input type="number" name="amount" step="0.01" min="0.01" required placeholder="0.00" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Income Date *</label>
                        <input type="date" name="income_date" value="{{ now()->toDateString() }}" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Category *</label>
                        <select name="category" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="grant">Grant</option>
                            <option value="donation">Donation</option>
                            <option value="investment">Investment Return</option>
                            <option value="interest">Interest</option>
                            <option value="rental">Rental</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Description</label>
                        <input type="text" name="description" placeholder="Optional description..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-4">
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Save Income</button>
                    <button type="button" @click="showForm=false" class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2.5">Cancel</button>
                </div>
            </form>
        </div>

        {{-- Income Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            @if($incomes->isNotEmpty())
            <table class="w-full text-sm">
                <thead><tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">Source</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">Amount</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden md:table-cell">Category</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden lg:table-cell">Date</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @foreach($incomes as $income)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-slate-800">{{ $income->source }}</p>
                        @if($income->description)
                        <p class="text-xs text-slate-400">{{ $income->description }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 font-bold text-sky-600">${{ number_format($income->amount, 2) }}</td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        @php
                        $catColors = ['grant'=>'violet','donation'=>'emerald','investment'=>'sky','interest'=>'indigo','rental'=>'amber','other'=>'slate'];
                        $c = $catColors[$income->category] ?? 'slate';
                        @endphp
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-{{ $c }}-100 text-{{ $c }}-700">
                            {{ ucfirst($income->category) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-slate-400 text-xs hidden lg:table-cell">{{ $income->income_date->format('M d, Y') }}</td>
                    <td class="px-5 py-4">
                        <form method="POST" action="{{ route('other-income.destroy', $income) }}" onsubmit="return confirm('Delete this income record?')">
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
            <div class="px-5 py-4 border-t border-slate-100">{{ $incomes->links() }}</div>
            @else
            <div class="flex flex-col items-center justify-center py-16 text-slate-300">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                <p class="text-sm font-medium text-slate-400">No other income recorded yet</p>
                <button @click="showForm=true" class="mt-3 text-sm text-emerald-500 hover:underline">Record first income</button>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
