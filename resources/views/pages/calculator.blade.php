<x-app-layout>
    <div class="py-8">
        <div class="mb-7">
            <p class="text-xs font-semibold text-emerald-500 uppercase tracking-widest mb-1">Tools · ሕሳብ</p>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">ሕሳብ</h1>
            <p class="text-sm text-slate-500 mt-1">ቅልጡፍ ናይ ፋይናንስ ሕሳብ ኣካቢ።</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 items-start">

            {{-- ══ COMPACT VIBRANT CALCULATOR ══ --}}
            <div class="w-full flex justify-center lg:justify-start lg:flex-shrink-0">
                <div x-data="calculator()"
                     style="width:320px; max-width:320px; background:#0f172a; border-radius:28px; padding:18px; box-shadow:0 28px 70px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.07), inset 0 1px 0 rgba(255,255,255,.06);">

                    {{-- Display --}}
                    <div style="min-height:100px; display:flex; flex-direction:column; justify-content:flex-end; align-items:flex-end; padding:8px 4px 12px; margin-bottom:12px; background:rgba(255,255,255,.03); border-radius:16px; border:1px solid rgba(255,255,255,.05);">
                        <p style="font-size:.75rem; color:#475569; min-height:18px; word-break:break-all; text-align:right; margin-bottom:4px; padding:0 8px;"
                           x-text="expression || ''"></p>
                        <p style="font-size:2.8rem; font-weight:300; color:#f8fafc; line-height:1; word-break:break-all; text-align:right; max-width:100%; overflow:hidden; padding:0 8px;"
                           x-text="display"
                           :style="display.length > 9 ? 'font-size:1.8rem' : display.length > 6 ? 'font-size:2.2rem' : ''"></p>
                    </div>

                    {{-- Buttons Grid --}}
                    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:8px;">
                        <template x-for="(btn, idx) in buttons" :key="idx">
                            <button @click="press(btn)"
                                    :style="btn.wide ? 'grid-column: span 2; border-radius:22px; justify-content:flex-start; padding-left:22px;' : ''"
                                    :data-style="btn.style"
                                    x-init="$el.style.cssText += btn.style"
                                    style="height:62px; border-radius:50%; border:none; font-size:1.2rem; font-weight:700; cursor:pointer; transition:filter .08s, transform .08s; display:flex; align-items:center; justify-content:center; font-family:inherit; letter-spacing:-.01em;"
                                    onmousedown="this.style.filter='brightness(1.25)';this.style.transform='scale(.94)'"
                                    onmouseup="this.style.filter='';this.style.transform=''"
                                    onmouseleave="this.style.filter='';this.style.transform=''"
                                    x-text="btn.label">
                            </button>
                        </template>
                    </div>

                    {{-- Brand --}}
                    <div style="text-align:center; margin-top:14px; padding-top:10px; border-top:1px solid rgba(255,255,255,.05);">
                        <span style="font-size:.62rem; color:#1e3a5f; font-weight:800; letter-spacing:.12em; text-transform:uppercase;">ADAM44 Calculator</span>
                    </div>
                </div>
            </div>

            {{-- ══ FINANCIAL TOOLS ══ --}}
            <div class="flex-1 w-full space-y-5">

                {{-- Percentage Calculator --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6" x-data="{amount:'',pct:'',result:null}">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm">
                        <span class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white text-sm font-bold flex-shrink-0">%</span>
                        Percentage Calculator
                    </h3>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="text-xs text-slate-500 font-medium block mb-1">Amount ($)</label>
                            <input type="number" x-model="amount" placeholder="1000"
                                   class="w-full text-sm border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 font-medium block mb-1">Percentage (%)</label>
                            <input type="number" x-model="pct" placeholder="15"
                                   class="w-full text-sm border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none">
                        </div>
                    </div>
                    <button @click="result = (parseFloat(amount)||0) * (parseFloat(pct)||0) / 100"
                            class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors">
                        Calculate
                    </button>
                    <div x-show="result !== null" x-cloak class="mt-3 bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 text-center">
                        <p class="text-xs text-slate-500 mb-1">Result</p>
                        <p class="text-2xl font-bold text-emerald-600" x-text="'$' + result.toFixed(2)"></p>
                    </div>
                </div>

                {{-- Loan / EMI Calculator --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6" x-data="{principal:'',rate:'',months:'',monthly:null,total:null}">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm">
                        <span class="w-8 h-8 bg-sky-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        </span>
                        Loan / EMI Calculator
                    </h3>
                    <div class="space-y-3 mb-3">
                        <div>
                            <label class="text-xs text-slate-500 font-medium block mb-1">Loan Amount ($)</label>
                            <input type="number" x-model="principal" placeholder="10000"
                                   class="w-full text-sm border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 outline-none">
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs text-slate-500 font-medium block mb-1">Annual Rate (%)</label>
                                <input type="number" x-model="rate" placeholder="5.5" step="0.1"
                                       class="w-full text-sm border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 outline-none">
                            </div>
                            <div>
                                <label class="text-xs text-slate-500 font-medium block mb-1">Months</label>
                                <input type="number" x-model="months" placeholder="24"
                                       class="w-full text-sm border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-sky-400 focus:border-sky-400 outline-none">
                            </div>
                        </div>
                    </div>
                    <button @click="
                        let r=parseFloat(rate)/100/12, n=parseInt(months), p=parseFloat(principal);
                        monthly = r ? (p*r*Math.pow(1+r,n))/(Math.pow(1+r,n)-1) : p/n;
                        total = monthly * n;
                    " class="w-full bg-sky-500 hover:bg-sky-600 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors">
                        Calculate EMI
                    </button>
                    <div x-show="monthly !== null" x-cloak class="mt-3 grid grid-cols-2 gap-3">
                        <div class="bg-sky-50 border border-sky-100 rounded-xl px-3 py-3 text-center">
                            <p class="text-xs text-slate-500 mb-1">Monthly EMI</p>
                            <p class="text-xl font-bold text-sky-600" x-text="'$' + monthly.toFixed(2)"></p>
                        </div>
                        <div class="bg-slate-50 border border-slate-100 rounded-xl px-3 py-3 text-center">
                            <p class="text-xs text-slate-500 mb-1">Total Paid</p>
                            <p class="text-xl font-bold text-slate-700" x-text="'$' + total.toFixed(2)"></p>
                        </div>
                    </div>
                </div>

                {{-- Savings Rate Calculator --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6" x-data="{income:'',expenses:'',rate:null}">
                    <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm">
                        <span class="w-8 h-8 bg-violet-500 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </span>
                        Savings Rate
                    </h3>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="text-xs text-slate-500 font-medium block mb-1">Monthly Income ($)</label>
                            <input type="number" x-model="income" placeholder="3000"
                                   class="w-full text-sm border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-violet-400 focus:border-violet-400 outline-none">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 font-medium block mb-1">Monthly Expenses ($)</label>
                            <input type="number" x-model="expenses" placeholder="2200"
                                   class="w-full text-sm border-slate-200 rounded-lg px-3 py-2.5 focus:ring-2 focus:ring-violet-400 focus:border-violet-400 outline-none">
                        </div>
                    </div>
                    <button @click="rate = income > 0 ? ((income - expenses) / income * 100).toFixed(1) : 0"
                            class="w-full bg-violet-500 hover:bg-violet-600 text-white text-sm font-semibold py-2.5 rounded-xl transition-colors">
                        Calculate
                    </button>
                    <div x-show="rate !== null" x-cloak class="mt-3 bg-violet-50 border border-violet-100 rounded-xl px-4 py-3">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs text-slate-500">Savings Rate</p>
                            <p class="text-xl font-bold" :class="rate >= 20 ? 'text-emerald-600' : rate >= 10 ? 'text-amber-600' : 'text-rose-500'" x-text="rate + '%'"></p>
                        </div>
                        <div class="w-full bg-slate-200 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all" :class="rate >= 20 ? 'bg-emerald-500' : rate >= 10 ? 'bg-amber-500' : 'bg-rose-500'"
                                 :style="'width:' + Math.min(Math.max(rate, 0), 100) + '%'"></div>
                        </div>
                        <p class="text-xs text-slate-500 mt-2" x-text="rate >= 30 ? '🌟 Excellent! Keep investing.' : rate >= 20 ? '✅ Great savings rate.' : rate >= 10 ? '⚠️ Good, aim for 20%+.' : '❌ Try to reduce expenses.'"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function calculator() {
        return {
            display: '0',
            expression: '',
            currentVal: '',
            buttons: [
                // Row 1: Functions
                { label:'AC', style:'background:#dc2626; color:#fff; box-shadow:0 4px 14px rgba(220,38,38,.4);' },
                { label:'±',  style:'background:#ea580c; color:#fff; box-shadow:0 4px 14px rgba(234,88,12,.35);' },
                { label:'%',  style:'background:#d97706; color:#fff; box-shadow:0 4px 14px rgba(217,119,6,.35);' },
                { label:'÷',  style:'background:#2563eb; color:#fff; box-shadow:0 4px 14px rgba(37,99,235,.4);' },
                // Row 2
                { label:'7', style:'background:#1e293b; color:#f1f5f9;' },
                { label:'8', style:'background:#1e293b; color:#f1f5f9;' },
                { label:'9', style:'background:#1e293b; color:#f1f5f9;' },
                { label:'×', style:'background:#7c3aed; color:#fff; box-shadow:0 4px 14px rgba(124,58,237,.4);' },
                // Row 3
                { label:'4', style:'background:#1e293b; color:#f1f5f9;' },
                { label:'5', style:'background:#1e293b; color:#f1f5f9;' },
                { label:'6', style:'background:#1e293b; color:#f1f5f9;' },
                { label:'−', style:'background:#db2777; color:#fff; box-shadow:0 4px 14px rgba(219,39,119,.4);' },
                // Row 4
                { label:'1', style:'background:#1e293b; color:#f1f5f9;' },
                { label:'2', style:'background:#1e293b; color:#f1f5f9;' },
                { label:'3', style:'background:#1e293b; color:#f1f5f9;' },
                { label:'+', style:'background:#0d9488; color:#fff; box-shadow:0 4px 14px rgba(13,148,136,.4);' },
                // Row 5
                { label:'0', style:'background:#1e293b; color:#f1f5f9;', wide: true },
                { label:'.', style:'background:#1e293b; color:#f1f5f9;' },
                { label:'=', style:'background:linear-gradient(135deg,#10b981,#059669); color:#fff; box-shadow:0 4px 16px rgba(16,185,129,.5);' },
            ],
            press(btn) {
                const map = { '÷':'/', '×':'*', '−':'-' };
                const l = btn.label;
                if (l === 'AC') { this.display='0'; this.expression=''; this.currentVal=''; return; }
                if (l === '±')  { this.display = String(parseFloat(this.display)*-1); this.currentVal = this.display; return; }
                if (l === '%')  { this.display = String(parseFloat(this.display)/100); this.currentVal = this.display; return; }
                if (l === '=') {
                    try {
                        let expr = (this.expression + this.currentVal).replace(/[÷×−]/g, s => map[s]||s);
                        let r = Function('"use strict"; return (' + expr + ')')();
                        this.display = String(parseFloat(r.toFixed(10)));
                        this.expression = ''; this.currentVal = this.display;
                    } catch { this.display = 'Error'; }
                    return;
                }
                if (['+','−','×','÷'].includes(l)) {
                    this.expression += this.currentVal + l;
                    this.currentVal = ''; return;
                }
                if (l === '.' && this.currentVal.includes('.')) return;
                this.currentVal = (this.currentVal === '0' && l !== '.') ? l : this.currentVal + l;
                this.display = this.currentVal;
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
