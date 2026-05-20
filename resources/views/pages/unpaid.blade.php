<x-app-layout>
    <div class="py-8" x-data="{showForm:false, approving:null}">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs font-semibold text-rose-500 uppercase tracking-widest mb-1">Alerts</p>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Unpaid Items</h1>
                <p class="text-sm text-slate-500 mt-1">Outstanding balances and overdue payments</p>
            </div>
            <button @click="showForm=!showForm" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-lg shadow-emerald-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Unpaid Item
            </button>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-rose-600">${{ number_format($totalDue, 2) }}</p>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide font-medium">Total Due</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
                <p class="text-2xl font-bold text-amber-500">{{ $overdueCount }}</p>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide font-medium">Overdue</p>
            </div>
        </div>

        {{-- Overdue Alert --}}
        @if($overdueCount > 0)
        <div class="flex items-center gap-3 bg-rose-50 border border-rose-200 rounded-2xl px-5 py-4 mb-6">
            <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-semibold text-rose-700">{{ $overdueCount }} item{{ $overdueCount > 1 ? 's are' : ' is' }} overdue. Follow up immediately.</p>
        </div>
        @endif

        {{-- Add Unpaid Item Form --}}
        <div x-show="showForm" x-transition class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
            <h3 class="font-bold text-slate-800 mb-4">Add Unpaid Item</h3>
            <form method="POST" action="{{ route('unpaid.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Debtor Name *</label>
                        <input type="text" name="debtor_name" required placeholder="Who owes?" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Amount Due *</label>
                        <input type="number" name="amount_due" step="0.01" min="0.01" required placeholder="0.00" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Due Date *</label>
                        <input type="date" name="due_date" required class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Linked Member</label>
                        <select name="member_id" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">— None —</option>
                            @foreach($members as $member)
                            <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Status</label>
                        <select name="status" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="unpaid">Unpaid</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Description</label>
                        <input type="text" name="description" placeholder="What is this for?" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-4">
                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Save Item</button>
                    <button type="button" @click="showForm=false" class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2.5">Cancel</button>
                </div>
            </form>
        </div>

        {{-- Smart: Members with no payment in 30+ days --}}
        @if($lateMembers->isNotEmpty())
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></div>
                <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wide">Members Overdue 30+ Days <span class="text-amber-500">({{ $lateMembers->count() }})</span></h2>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-amber-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead><tr class="bg-amber-50 border-b border-amber-100">
                        <th class="text-left text-xs font-bold text-amber-700 uppercase tracking-wide px-5 py-3">Member · ኣባል</th>
                        <th class="text-left text-xs font-bold text-amber-700 uppercase tracking-wide px-5 py-3 hidden md:table-cell">Zone</th>
                        <th class="px-5 py-3"></th>
                    </tr></thead>
                    <tbody class="divide-y divide-amber-50">
                    @foreach($lateMembers as $lm)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                     style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                                    {{ strtoupper(substr($lm->full_name, 0, 2)) }}
                                </div>
                                <span class="font-semibold text-slate-800">{{ $lm->full_name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 hidden md:table-cell text-slate-500 text-xs">{{ $lm->zone ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <button @click="approving = approving === {{ $lm->id }} ? null : {{ $lm->id }}"
                                    class="inline-flex items-center gap-1.5 text-xs font-bold px-3.5 py-1.5 rounded-lg text-white transition-colors"
                                    style="background:#10b981;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Approve
                            </button>
                        </td>
                    </tr>
                    <tr x-show="approving === {{ $lm->id }}" x-cloak class="bg-emerald-50/70 border-b border-emerald-100">
                        <td colspan="3" class="px-5 py-4">
                            <form method="POST" action="{{ route('unpaid.approve', $lm) }}" class="flex flex-wrap items-end gap-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Amount Paid *</label>
                                    <input type="number" name="amount" step="0.01" min="0.01" required placeholder="0.00"
                                           class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-400 outline-none w-28">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-1">Method</label>
                                    <select name="payment_method" class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-400 outline-none">
                                        <option value="cash">Cash</option>
                                        <option value="bank_transfer">Bank Transfer</option>
                                        <option value="mobile_money">Mobile Money</option>
                                        <option value="check">Check</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="flex gap-2">
                                    <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors">Confirm Payment</button>
                                    <button type="button" @click="approving=null" class="text-xs text-slate-500 hover:text-slate-700 px-3 py-2">Cancel</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Unpaid Items Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            @if($items->isNotEmpty())
            <table class="w-full text-sm">
                <thead><tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">Debtor</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">Amount Due</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3 hidden md:table-cell">Due Date</th>
                    <th class="text-left text-xs font-semibold text-slate-500 uppercase tracking-wide px-5 py-3">Status</th>
                    <th class="px-5 py-3"></th>
                </tr></thead>
                <tbody class="divide-y divide-slate-50">
                @foreach($items as $item)
                @php $overdue = $item->status !== 'paid' && $item->due_date->isPast(); @endphp
                <tr class="hover:bg-slate-50 transition-colors {{ $overdue ? 'bg-rose-50/40' : '' }}">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-slate-800">{{ $item->debtor_name }}</p>
                        @if($item->description)
                        <p class="text-xs text-slate-400">{{ $item->description }}</p>
                        @endif
                        @if($item->member)
                        <p class="text-xs text-violet-500">{{ $item->member->full_name }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 font-bold text-rose-600">${{ number_format($item->amount_due, 2) }}</td>
                    <td class="px-5 py-4 hidden md:table-cell text-xs {{ $overdue ? 'text-rose-500 font-semibold' : 'text-slate-400' }}">
                        {{ $item->due_date->format('M d, Y') }}
                        @if($overdue) <span class="ml-1 text-rose-400">(overdue)</span> @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $item->status==='paid'?'bg-emerald-100 text-emerald-700':($item->status==='partial'?'bg-amber-100 text-amber-700':'bg-rose-100 text-rose-600') }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            @if($item->status !== 'paid')
                            <form method="POST" action="{{ route('unpaid.pay', $item) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    Approve
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('unpaid.destroy', $item) }}" onsubmit="return confirm('Delete this item?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-rose-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-slate-100">{{ $items->links() }}</div>
            @else
            <div class="flex flex-col items-center justify-center py-16 text-slate-300">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm font-medium text-slate-400">All clear — no unpaid items</p>
                <button @click="showForm=true" class="mt-3 text-sm text-emerald-500 hover:underline">Add an unpaid item</button>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
