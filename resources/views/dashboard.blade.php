<x-app-layout>

    @push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush

    <div class="py-6 md:py-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#10b981;">ዳሽቦርድ · Overview</p>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 leading-tight">
                    Dashboard <span class="text-slate-300 font-light">|</span> Overview
                </h1>
                <p class="text-sm text-slate-400 mt-1">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <a href="{{ route('transactions.index') }}"
               class="inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 rounded-xl transition-all self-start sm:self-auto border"
               style="border-color:rgba(16,185,129,.4); color:#10b981; background:rgba(16,185,129,.08);"
               onmouseover="this.style.background='rgba(16,185,129,.15)'" onmouseout="this.style.background='rgba(16,185,129,.08)'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                View Report
            </a>
        </div>

        {{-- ═══ 5 STAT CARDS ═══ --}}
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

            {{-- 1. MEMBERS --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Members<br><span class="text-slate-300 font-medium normal-case">ኣባላት</span></span>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#ede9fe;">
                        <svg class="w-5 h-5" style="color:#7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-extrabold text-slate-800">{{ number_format($totalMembers) }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Total registered</p>
                </div>
            </div>

            {{-- 2. INCOME --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Income<br><span class="text-slate-300 font-medium normal-case">ኣታዊ</span></span>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#dcfce7;">
                        <svg class="w-5 h-5" style="color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 11l5-5m0 0l5 5m-5-5v12"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-extrabold" style="color:#16a34a;">${{ number_format($totalIncome, 2) }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">All time income</p>
                </div>
            </div>

            {{-- 3. EXPENSES --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Expenses<br><span class="text-slate-300 font-medium normal-case">ወጻኢታት</span></span>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#fee2e2;">
                        <svg class="w-5 h-5" style="color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-extrabold" style="color:#dc2626;">${{ number_format($totalExpense, 2) }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Total spent</p>
                </div>
            </div>

            {{-- 4. OTHER INCOME --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-400">Other<br><span class="text-slate-300 font-medium normal-case">ካልእ ኣታዊ</span></span>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#e0f2fe;">
                        <svg class="w-5 h-5" style="color:#0284c7;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-extrabold" style="color:#0284c7;">${{ number_format($totalOtherIncome, 2) }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">Grants & donations</p>
                </div>
            </div>

            {{-- 5. NET BALANCE --}}
            <div class="lg:col-span-1 col-span-2 rounded-2xl p-5 shadow-lg flex flex-col gap-3"
                 style="background:#070e1c; border:1px solid rgba(255,255,255,.07);">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest" style="color:#475569;">Net Balance<br><span style="color:#334155; font-weight:500;" class="normal-case">ጠቕላሊ ሰሌዳ</span></span>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:rgba(16,185,129,.15);">
                        <svg class="w-5 h-5" style="color:#10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                </div>
                <div>
                    <p class="text-3xl font-extrabold {{ $netPosition >= 0 ? '' : '' }}"
                       style="color:{{ $netPosition >= 0 ? '#10b981' : '#fb7185' }};">
                        {{ $netPosition >= 0 ? '+' : '-' }}${{ number_format(abs($netPosition), 2) }}
                    </p>
                    <p class="text-xs mt-0.5" style="color:#475569;">
                        {{ $netPosition >= 0 ? 'Surplus · ትርፊ' : 'Deficit · ጉድለት' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">

            {{-- Income Flow --}}
            <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <div>
                        <h2 class="text-base font-bold text-slate-800">Income Flow · ናይ ኣታዊ ፍሰት</h2>
                        <p class="text-xs text-slate-400 mt-0.5">{{ now()->year }} — Income vs Target</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-medium">
                        <span class="flex items-center gap-1.5 text-slate-500">
                            <span class="w-3 h-1 rounded-full inline-block" style="background:#10b981;"></span> Income
                        </span>
                        <span class="flex items-center gap-1.5 text-slate-500">
                            <span class="w-3 h-px inline-block border-t-2 border-dashed border-slate-400" style="background:transparent;"></span> Target
                        </span>
                    </div>
                </div>
                <div class="relative h-64 md:h-72">
                    <canvas id="incomeFlowChart"></canvas>
                </div>
            </div>

            {{-- Expense Breakdown --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="mb-5">
                    <h2 class="text-base font-bold text-slate-800">Expense Breakdown</h2>
                    <p class="text-xs text-slate-400 mt-0.5">{{ now()->format('F Y') }} · ወጻኢ ብምድብ</p>
                </div>
                @if($categoryBreakdown->isNotEmpty())
                <div class="relative h-44">
                    <canvas id="doughnutChart"></canvas>
                </div>
                <div class="mt-4 space-y-2">
                    @foreach($categoryBreakdown->take(4) as $cat)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $cat['color'] }}"></span>
                            <span class="text-sm text-slate-600">{{ $cat['name'] }}</span>
                        </div>
                        <span class="text-sm font-bold text-slate-700">${{ number_format($cat['total'], 0) }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="flex flex-col items-center justify-center h-52 text-slate-300">
                    <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
                    <p class="text-sm font-medium text-slate-400">No expenses this month</p>
                    <a href="{{ route('expenses') }}" class="mt-2 text-xs font-semibold" style="color:#10b981;">Log an expense →</a>
                </div>
                @endif
            </div>
        </div>

        {{-- Recent Transactions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center justify-between px-6 py-5 border-b border-slate-100">
                <div>
                    <h2 class="text-base font-bold text-slate-800">Recent Transactions · ናይ ቀረባ ምንቅስቓሳት</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Latest financial activity</p>
                </div>
                <a href="{{ route('transactions.index') }}" class="text-sm font-semibold transition-colors" style="color:#10b981;"
                   onmouseover="this.style.color='#059669'" onmouseout="this.style.color='#10b981'">View all →</a>
            </div>

            @if($recentTransactions->isNotEmpty())
            <div class="divide-y divide-slate-50">
                @foreach($recentTransactions as $tx)
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-50 transition-colors">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:{{ $tx->type==='income' ? '#dcfce7' : '#fee2e2' }};">
                        @if($tx->type === 'income')
                        <svg class="w-5 h-5" style="color:#16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                        @else
                        <svg class="w-5 h-5" style="color:#dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $tx->title }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">
                            {{ $tx->category?->name ?? 'Uncategorized' }}
                            <span class="mx-1">&middot;</span>
                            {{ $tx->transaction_date->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="text-sm font-extrabold flex-shrink-0"
                         style="color:{{ $tx->type==='income' ? '#16a34a' : '#dc2626' }};">
                        {{ $tx->type === 'income' ? '+' : '-' }}${{ number_format($tx->amount, 2) }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-16 text-slate-300">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm font-medium text-slate-400">No transactions yet</p>
                <a href="{{ route('transactions.create') }}" class="mt-2 text-sm font-semibold" style="color:#10b981;">Add first transaction</a>
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
    Chart.defaults.font.family = 'Figtree, sans-serif';

    const flowCtx = document.getElementById('incomeFlowChart').getContext('2d');
    const grad = flowCtx.createLinearGradient(0, 0, 0, 300);
    grad.addColorStop(0, 'rgba(16,185,129,.22)');
    grad.addColorStop(1, 'rgba(16,185,129,0)');

    new Chart(flowCtx, {
        type: 'line',
        data: {
            labels: @json($monthLabels),
            datasets: [
                {
                    label: 'Income',
                    data: @json($incomeFlow),
                    borderColor: '#10b981',
                    backgroundColor: grad,
                    borderWidth: 2.5, fill: true, tension: 0.45,
                    pointBackgroundColor: '#10b981', pointBorderColor: '#fff', pointBorderWidth: 2,
                    pointRadius: 4, pointHoverRadius: 6,
                },
                {
                    label: 'Target',
                    data: @json($targetFlow),
                    borderColor: '#cbd5e1', backgroundColor: 'transparent',
                    borderWidth: 2, borderDash: [6,4], fill: false, tension: 0,
                    pointRadius: 0,
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a', titleColor: '#94a3b8', bodyColor: '#fff',
                    padding: 12, cornerRadius: 10,
                    callbacks: { label: c => ' $' + c.parsed.y.toLocaleString('en-US', {minimumFractionDigits:2}) }
                }
            },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                y: {
                    grid: { color: '#f1f5f9' }, border: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 11 }, callback: v => '$' + v.toLocaleString() }
                }
            }
        }
    });

    @if($categoryBreakdown->isNotEmpty())
    const doCtx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(doCtx, {
        type: 'doughnut',
        data: {
            labels: @json($categoryBreakdown->pluck('name')),
            datasets: [{ data: @json($categoryBreakdown->pluck('total')), backgroundColor: @json($categoryBreakdown->pluck('color')), borderWidth: 0, hoverOffset: 6 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '72%',
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#0f172a', padding: 10, cornerRadius: 8, callbacks: { label: c => ' $' + c.parsed.toFixed(2) } }
            }
        }
    });
    @endif
    </script>
    @endpush

</x-app-layout>
