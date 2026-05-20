<x-app-layout>
<div class="py-8" x-data="{showForm:false, editing:null}">

    <div class="flex items-center justify-between mb-7">
        <div>
            <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-1">Finance · ፋይናንስ</p>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">ክፍሊታት</h1>
            <p class="text-sm text-slate-500 mt-1">ናይ ኣባላት ክፍሊታት ተቆጻጸር።</p>
        </div>
        <button @click="showForm=!showForm"
                class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors shadow-lg shadow-emerald-500/25">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            ክፍሊት ምዝጋብ
        </button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
            <p class="text-2xl font-bold text-emerald-600">${{ number_format($totalPaid, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide font-semibold">ዝተኸፍለ ድምር</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
            <p class="text-2xl font-bold text-amber-500">{{ $unpaidCount }}</p>
            <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide font-semibold">ዘይተኸፍሉ</p>
        </div>
    </div>

    {{-- Add Payment Form --}}
    <div x-show="showForm" x-transition class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
        <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2 text-base">
            <span class="w-7 h-7 bg-emerald-500 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </span>
            ሓድሽ ክፍሊት ምዝጋብ
        </h3>
        <form method="POST" action="{{ route('payments.store') }}">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                {{-- Payer Name — dropdown from members --}}
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">ኣባል (Payer) *</label>
                    <select name="member_id" required
                            class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="">— ኣባል ምረጽ —</option>
                        @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->full_name }}</option>
                        @endforeach
                    </select>
                    @if($members->isEmpty())
                    <p class="text-xs text-amber-500 mt-1">ቅድም ኣባላት ምዝጋብ የድሊ।</p>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">መጠን (Amount) *</label>
                    <input type="number" name="amount" step="0.01" min="0.01" required placeholder="0.00"
                           class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">ዕለት *</label>
                    <input type="date" name="payment_date" value="{{ now()->toDateString() }}" required
                           class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">ኣገባብ *</label>
                    <select name="payment_method"
                            class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="cash">ናቕፋ (Cash)</option>
                        <option value="bank_transfer">ባንኪ (Bank Transfer)</option>
                        <option value="mobile_money">ሞባይል (Mobile Money)</option>
                        <option value="check">ቼክ (Check)</option>
                        <option value="other">ካልእ (Other)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">ስታቱስ *</label>
                    <select name="status"
                            class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="paid">ተኸፊሉ (Paid)</option>
                        <option value="unpaid">ዘይተኸፍለ (Unpaid)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1.5 uppercase tracking-wide">ተወሳኺ ሓሳብ</label>
                    <input type="text" name="notes" placeholder="Optional notes..."
                           class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                </div>
            </div>

            <div class="flex items-center gap-3 mt-5">
                <button type="submit"
                        class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold px-7 py-2.5 rounded-xl transition-colors shadow-lg shadow-emerald-500/25">
                    ምዝጋብ
                </button>
                <button type="button" @click="showForm=false"
                        class="text-sm text-slate-400 hover:text-slate-600 px-4 py-2.5 transition-colors">
                    ሰርዝ
                </button>
            </div>
        </form>
    </div>

    {{-- Payments Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @if($payments->isNotEmpty())
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide px-5 py-3">ኣባል</th>
                    <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide px-5 py-3">መጠን</th>
                    <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide px-5 py-3 hidden md:table-cell">ኣገባብ</th>
                    <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide px-5 py-3">ስታቱስ</th>
                    <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide px-5 py-3 hidden lg:table-cell">ዕለት</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
            @foreach($payments as $p)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                             style="background:linear-gradient(135deg,#10b981,#059669);">
                            {{ strtoupper(substr($p->payer_name, 0, 2)) }}
                        </div>
                        <span class="font-semibold text-slate-800">{{ $p->payer_name }}</span>
                    </div>
                </td>
                <td class="px-5 py-4 font-bold {{ $p->status==='paid' ? 'text-emerald-600' : 'text-amber-500' }}">
                    ${{ number_format($p->amount, 2) }}
                </td>
                <td class="px-5 py-4 hidden md:table-cell text-slate-500 text-xs capitalize">
                    {{ str_replace('_', ' ', $p->payment_method) }}
                </td>
                <td class="px-5 py-4">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full
                        {{ $p->status==='paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $p->status==='paid' ? 'ተኸፊሉ' : 'ዘይተኸፍለ' }}
                    </span>
                </td>
                <td class="px-5 py-4 text-slate-400 text-xs hidden lg:table-cell">
                    {{ $p->payment_date->format('M d, Y') }}
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center gap-2">
                        @if($p->status === 'unpaid')
                        <form method="POST" action="{{ route('payments.paid', $p) }}">
                            @csrf @method('PATCH')
                            <button type="submit" title="Mark as Paid"
                                    class="flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                ተኸፊሉ
                            </button>
                        </form>
                        @else
                        <a href="{{ route('payments.receipt', $p) }}"
                           class="flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            ቅብሊት
                        </a>
                        @endif
                        <button @click="editing = editing === {{ $p->id }} ? null : {{ $p->id }}"
                                class="text-slate-400 hover:text-emerald-500 transition-colors p-1" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <form method="POST" action="{{ route('payments.destroy', $p) }}"
                              onsubmit="return confirm('ምሕዛዝ — ክሰርዞ?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            {{-- Inline edit row --}}
            <tr x-show="editing === {{ $p->id }}" x-cloak class="bg-emerald-50/60 border-b border-emerald-100">
                <td colspan="6" class="px-5 py-4">
                    <form method="POST" action="{{ route('payments.update', $p) }}" class="flex flex-wrap items-end gap-3">
                        @csrf @method('PATCH')
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Amount *</label>
                            <input type="number" name="amount" value="{{ $p->amount }}" step="0.01" min="0.01" required
                                   class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-400 outline-none w-28">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Date *</label>
                            <input type="date" name="payment_date" value="{{ $p->payment_date->toDateString() }}" required
                                   class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-400 outline-none w-36">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Method</label>
                            <select name="payment_method" class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-400 outline-none">
                                @foreach(['cash'=>'Cash','bank_transfer'=>'Bank Transfer','mobile_money'=>'Mobile Money','check'=>'Check','other'=>'Other'] as $val=>$label)
                                <option value="{{ $val }}" @selected($p->payment_method===$val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Status</label>
                            <select name="status" class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-400 outline-none">
                                <option value="paid" @selected($p->status==='paid')>ተኸፊሉ (Paid)</option>
                                <option value="unpaid" @selected($p->status==='unpaid')>ዘይተኸፍለ (Unpaid)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Notes</label>
                            <input type="text" name="notes" value="{{ $p->notes }}"
                                   class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-400 outline-none w-36">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold px-4 py-2 rounded-xl transition-colors">Save</button>
                            <button type="button" @click="editing=null" class="text-xs text-slate-500 hover:text-slate-700 px-3 py-2">Cancel</button>
                        </div>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-slate-100">{{ $payments->links() }}</div>
        @else
        <div class="flex flex-col items-center justify-center py-16 text-slate-300">
            <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            <p class="text-sm font-medium text-slate-400 mb-1">ክፍሊት ኣይተዘርዝረን</p>
            <button @click="showForm=true" class="mt-2 text-sm text-emerald-500 hover:underline font-semibold">
                ቀዳማይ ክፍሊት ምዝጋብ
            </button>
        </div>
        @endif
    </div>
</div>
</x-app-layout>
