<x-app-layout>
    <div class="py-8">
        <div class="mb-8">
            <p class="text-xs font-semibold text-violet-500 uppercase tracking-widest mb-1">AI Powered — Tigrinya</p>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">AI Finance Consultant</h1>
            <p class="text-sm text-slate-500 mt-1">ናይ ፋይናንስ ዝርዝር ጸብጻብ ብቋንቋ ትግርኛ — Detailed financial reports in Tigrinya</p>
        </div>

        {{-- Report Buttons --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8" x-data="aiReport()">
            <button @click="generate('weekly')" :disabled="loading"
                    class="group relative bg-white border-2 border-slate-200 hover:border-emerald-400 rounded-2xl p-6 text-left transition-all duration-200 hover:shadow-lg hover:shadow-emerald-500/10 disabled:opacity-60">
                <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-sky-100 transition-colors">
                    <svg class="w-6 h-6 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Weekly Report</h3>
                <p class="text-xs text-slate-400">ናይ ሰሙን ጸብጻብ — Last 7 days analysis</p>
                <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-sky-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Generate in Tigrinya
                </div>
            </button>

            <button @click="generate('monthly')" :disabled="loading"
                    class="group relative bg-white border-2 border-slate-200 hover:border-emerald-400 rounded-2xl p-6 text-left transition-all duration-200 hover:shadow-lg hover:shadow-emerald-500/10 disabled:opacity-60">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Monthly Report</h3>
                <p class="text-xs text-slate-400">ናይ ወርሒ ጸብጻብ — This month's full analysis</p>
                <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-emerald-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Generate in Tigrinya
                </div>
            </button>

            <button @click="generate('yearly')" :disabled="loading"
                    class="group relative bg-white border-2 border-slate-200 hover:border-emerald-400 rounded-2xl p-6 text-left transition-all duration-200 hover:shadow-lg hover:shadow-emerald-500/10 disabled:opacity-60">
                <div class="w-12 h-12 bg-violet-50 rounded-xl flex items-center justify-center mb-4 group-hover:bg-violet-100 transition-colors">
                    <svg class="w-6 h-6 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 mb-1">Yearly Report</h3>
                <p class="text-xs text-slate-400">ናይ ዓመት ጸብጻብ — Full year deep analysis</p>
                <div class="mt-4 flex items-center gap-1.5 text-xs font-semibold text-violet-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Generate in Tigrinya
                </div>
            </button>

            {{-- Loading state --}}
            <div x-show="loading" class="sm:col-span-3 flex items-center justify-center gap-3 py-8">
                <svg class="animate-spin w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <span class="text-slate-500 text-sm font-medium">ጸብጻብ ይዳሎ ኣሎ — Generating your report...</span>
            </div>

            {{-- Report output --}}
            <div x-show="report" x-cloak class="sm:col-span-3">
                <div class="bg-slate-900 rounded-2xl p-6 border border-slate-700">
                    {{-- Mini stats --}}
                    <div class="grid grid-cols-3 gap-4 mb-6 pb-6 border-b border-slate-800">
                        <div class="text-center">
                            <p class="text-xs text-slate-500 uppercase tracking-wide">Income</p>
                            <p class="text-xl font-bold text-emerald-400 mt-1" x-text="'$' + parseFloat(stats.income||0).toFixed(2)"></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-slate-500 uppercase tracking-wide">Expenses</p>
                            <p class="text-xl font-bold text-rose-400 mt-1" x-text="'$' + parseFloat(stats.expenses||0).toFixed(2)"></p>
                        </div>
                        <div class="text-center">
                            <p class="text-xs text-slate-500 uppercase tracking-wide">Net</p>
                            <p class="text-xl font-bold mt-1" :class="stats.net>=0?'text-emerald-400':'text-rose-400'" x-text="(stats.net>=0?'+':'') + '$' + Math.abs(parseFloat(stats.net||0)).toFixed(2)"></p>
                        </div>
                    </div>
                    {{-- Tigrinya Report Text --}}
                    <div class="text-slate-300 text-sm leading-relaxed font-mono whitespace-pre-wrap" x-text="report"></div>
                    <div class="mt-5 flex items-center justify-between pt-4 border-t border-slate-800">
                        <span class="text-xs text-slate-600">ብ ADAM44 AI · Unity Manager Pro — Tigrinya Financial Report</span>
                        <div class="flex items-center gap-3">
                            <button @click="navigator.clipboard.writeText(report).then(()=>{copied=true;setTimeout(()=>copied=false,2000)})"
                                    x-data="{copied:false}"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors"
                                    :class="copied ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-800 text-slate-400 hover:text-slate-200 hover:bg-slate-700'">
                                <span x-show="!copied">Copy</span>
                                <span x-show="copied" x-cloak>✓ Copied!</span>
                            </button>
                            <button @click="report=null;stats={}" class="text-xs text-slate-500 hover:text-slate-300 transition-colors">✕ Close report</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info section --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach([
                ['icon'=>'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z','title'=>'Smart Analysis','desc'=>'Automatically analyzes income, expenses, savings rate, and top spending categories.','color'=>'violet'],
                ['icon'=>'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129','title'=>'ቋንቋ ትግርኛ','desc'=>'Reports generated in professional Tigrinya with proper financial terminology.','color'=>'emerald'],
                ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','title'=>'Actionable Advice','desc'=>'Personalized recommendations based on your savings rate and spending patterns.','color'=>'sky'],
            ] as $c)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
                <div class="w-10 h-10 bg-{{ $c['color'] }}-50 rounded-xl flex items-center justify-center mb-3">
                    <svg class="w-5 h-5 text-{{ $c['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $c['icon'] }}"/></svg>
                </div>
                <h3 class="font-bold text-slate-800 mb-1 text-sm">{{ $c['title'] }}</h3>
                <p class="text-xs text-slate-400 leading-relaxed">{{ $c['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    @push('scripts')
    <script>
    function aiReport() {
        return {
            loading: false,
            report: null,
            stats: {},
            async generate(period) {
                this.loading = true;
                this.report = null;
                try {
                    const res = await fetch('{{ route("ai.generate") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify({ period })
                    });
                    const data = await res.json();
                    this.report = data.report;
                    this.stats = { income: data.income, expenses: data.expenses, net: data.net };
                } catch(e) {
                    this.report = 'ጌጋ ተፈጢሩ ኣሎ። (Error generating report. Please try again.)';
                }
                this.loading = false;
            }
        }
    }
    </script>
    @endpush
</x-app-layout>
