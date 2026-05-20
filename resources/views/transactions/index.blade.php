<x-app-layout>
    <div class="py-8" id="reportArea">

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-8 print:mb-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest mb-1 print:hidden" style="color:#10b981;">Finance · ታሪኽ</p>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800">Finance Summary Report</h1>
                <p class="text-sm text-slate-400 mt-1">Complete financial overview — {{ now()->format('F Y') }}</p>
            </div>
            <div class="flex items-center gap-2 print:hidden">
                <button onclick="exportPDF()"
                        class="inline-flex items-center gap-2 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors shadow-sm"
                        style="background:#10b981;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Export PDF
                </button>
                <button onclick="window.print()"
                        class="inline-flex items-center gap-2 bg-slate-700 hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print
                </button>
            </div>
        </div>

        {{-- Summary Cards --}}
        @php
            $income  = Auth::user()->transactions()->where('type','income')->sum('amount');
            $expense = Auth::user()->transactions()->where('type','expense')->sum('amount');
            $net     = $income - $expense;
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">
                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-2">Total Income · ኣታዊ</p>
                <p class="text-3xl font-extrabold" style="color:#16a34a;">${{ number_format($income, 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">
                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-2">Total Expenses · ወጻኢ</p>
                <p class="text-3xl font-extrabold text-rose-600">${{ number_format($expense, 2) }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">
                <p class="text-xs text-slate-400 uppercase tracking-widest font-bold mb-2">Net Balance · ሰሌዳ</p>
                <p class="text-3xl font-extrabold {{ $net >= 0 ? '' : 'text-rose-600' }}"
                   style="{{ $net >= 0 ? 'color:#16a34a;' : '' }}">
                    {{ $net >= 0 ? '+' : '' }}${{ number_format(abs($net), 2) }}
                </p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-6 print:hidden">
            <form method="GET" action="{{ route('transactions.index') }}" class="flex flex-wrap gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transactions..."
                       class="flex-1 min-w-40 text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:border-transparent"
                       style="--tw-ring-color:#10b981;">
                <select name="type" class="text-sm border-slate-200 rounded-xl px-3 py-2.5">
                    <option value="">All Types</option>
                    <option value="income"  {{ request('type')==='income'  ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ request('type')==='expense' ? 'selected' : '' }}>Expense</option>
                </select>
                <select name="category_id" class="text-sm border-slate-200 rounded-xl px-3 py-2.5">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id')==$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="text-white text-sm font-semibold px-4 py-2.5 rounded-xl"
                        style="background:#10b981;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">Filter</button>
                @if(request()->hasAny(['search','type','category_id']))
                <a href="{{ route('transactions.index') }}" class="text-sm text-slate-500 hover:text-slate-700 px-3 py-2.5">Clear</a>
                @endif
            </form>
        </div>

        {{-- Transactions Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            @if($transactions->isNotEmpty())
            <table class="w-full text-sm" id="txTable">
                <thead>
                    <tr class="border-b border-slate-100" style="background:#f0fdf4;">
                        <th class="text-left text-xs font-bold uppercase tracking-wide px-5 py-3.5" style="color:#059669;">Title</th>
                        <th class="text-left text-xs font-bold uppercase tracking-wide px-5 py-3.5 hidden md:table-cell" style="color:#059669;">Category</th>
                        <th class="text-left text-xs font-bold uppercase tracking-wide px-5 py-3.5 hidden lg:table-cell" style="color:#059669;">Date</th>
                        <th class="text-left text-xs font-bold uppercase tracking-wide px-5 py-3.5" style="color:#059669;">Type</th>
                        <th class="text-right text-xs font-bold uppercase tracking-wide px-5 py-3.5" style="color:#059669;">Amount</th>
                        <th class="px-5 py-3.5 print:hidden"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                @foreach($transactions as $tx)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-semibold text-slate-800">{{ $tx->title }}</p>
                        @if($tx->description)
                        <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs">{{ $tx->description }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell">
                        @if($tx->category)
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">{{ $tx->category->name }}</span>
                        @else<span class="text-slate-300 text-xs">—</span>@endif
                    </td>
                    <td class="px-5 py-4 text-slate-400 text-xs hidden lg:table-cell">{{ $tx->transaction_date->format('M d, Y') }}</td>
                    <td class="px-5 py-4">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $tx->type==='income' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-600' }}">
                            {{ ucfirst($tx->type) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right font-extrabold {{ $tx->type==='income' ? '' : 'text-rose-600' }}"
                        style="{{ $tx->type==='income' ? 'color:#16a34a;' : '' }}">
                        {{ $tx->type==='income' ? '+' : '-' }}${{ number_format($tx->amount, 2) }}
                    </td>
                    <td class="px-5 py-4 print:hidden">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('transactions.edit', $tx) }}" class="text-slate-300 hover:text-indigo-500 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('transactions.destroy', $tx) }}" onsubmit="return confirm('Delete this transaction?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-slate-100 print:hidden">{{ $transactions->links() }}</div>
            @else
            <div class="flex flex-col items-center justify-center py-16 text-slate-300">
                <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <p class="text-sm font-medium text-slate-400">No transactions found</p>
            </div>
            @endif
        </div>

        {{-- Print footer --}}
        <div class="hidden print:block mt-8 pt-4 border-t border-slate-200 text-center text-xs text-slate-400">
            ADAM44 · Unity Manager Pro — Financial Summary Report · Generated {{ now()->format('F d, Y \a\t H:i') }} · {{ Auth::user()->name }}
        </div>
    </div>

    @push('styles')
    <style>
    @media print {
        body { background: white !important; }
        aside, header, #main > header { display: none !important; }
        #main { margin-left: 0 !important; }
    }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
    <script>
    function exportPDF() {
        const { jsPDF } = window.jspdf;

        // A4 Portrait
        const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
        const W = doc.internal.pageSize.getWidth();   // 210mm
        const EM = [16, 185, 129];   // emerald-500
        const DARK = [7, 14, 28];    // sidebar navy
        const SLATE = [71, 85, 105];
        const LIGHT = [241, 245, 249];

        // ── HEADER BAND ──────────────────────────────────────────
        doc.setFillColor(...DARK);
        doc.rect(0, 0, W, 38, 'F');

        // Logo circle
        doc.setFillColor(...EM);
        doc.circle(18, 19, 8, 'F');
        // Dollar sign inside logo
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(11);
        doc.setFont('helvetica', 'bold');
        doc.text('$', 15.5, 22.5);

        // Brand name
        doc.setFontSize(20);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(...EM);
        doc.text('ADAM44', 30, 17);
        doc.setTextColor(148, 163, 184);
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
        doc.text('Unity Manager Pro', 30, 23);

        // Report title (right side)
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(13);
        doc.setFont('helvetica', 'bold');
        doc.text('Financial Summary Report', W - 14, 16, { align: 'right' });
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
        doc.setTextColor(148, 163, 184);
        doc.text('Generated: {{ now()->format('d F Y · H:i') }}', W - 14, 23, { align: 'right' });
        doc.text('Account: {{ Auth::user()->name }}', W - 14, 29, { align: 'right' });

        // ── EMERALD ACCENT LINE ───────────────────────────────────
        doc.setFillColor(...EM);
        doc.rect(0, 38, W, 1.2, 'F');

        // ── SUMMARY BOX ──────────────────────────────────────────
        const income  = {{ number_format($income, 2, '.', '') }};
        const expense = {{ number_format($expense, 2, '.', '') }};
        const net     = income - expense;

        const boxY = 46;
        const boxW = (W - 28 - 8) / 3;

        const summaryCards = [
            { label: 'Total Income', value: '${{ number_format($income, 2) }}', color: [22, 163, 74] },
            { label: 'Total Expenses', value: '${{ number_format($expense, 2) }}', color: [220, 38, 38] },
            { label: 'Net Balance', value: '{{ ($net ?? 0) >= 0 ? '+' : '' }}${{ number_format(abs($net ?? 0), 2) }}',
              color: net >= 0 ? [22, 163, 74] : [220, 38, 38] },
        ];

        summaryCards.forEach((card, i) => {
            const x = 14 + i * (boxW + 4);
            // Card background
            doc.setFillColor(...LIGHT);
            doc.roundedRect(x, boxY, boxW, 22, 3, 3, 'F');
            // Emerald top border
            doc.setFillColor(...EM);
            doc.roundedRect(x, boxY, boxW, 1.5, 1, 1, 'F');
            // Label
            doc.setFontSize(7);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(...SLATE);
            doc.text(card.label.toUpperCase(), x + boxW / 2, boxY + 9, { align: 'center' });
            // Value
            doc.setFontSize(13);
            doc.setFont('helvetica', 'bold');
            doc.setTextColor(...card.color);
            doc.text(card.value, x + boxW / 2, boxY + 18, { align: 'center' });
        });

        // ── SECTION TITLE ─────────────────────────────────────────
        doc.setFontSize(9);
        doc.setFont('helvetica', 'bold');
        doc.setTextColor(...EM);
        doc.text('TRANSACTION DETAIL', 14, boxY + 30);
        doc.setFillColor(...EM);
        doc.rect(14, boxY + 31.5, 40, 0.5, 'F');

        // ── BUILD ROWS FROM DOM ───────────────────────────────────
        const rows = [];
        document.querySelectorAll('#txTable tbody tr').forEach(tr => {
            const cells = tr.querySelectorAll('td');
            if (cells.length < 5) return;
            const title = cells[0].querySelector('p')?.innerText?.trim() || '';
            const cat   = cells[1]?.innerText?.trim().replace(/\s+/g,' ') || '—';
            const date  = cells[2]?.innerText?.trim() || '';
            const type  = cells[3]?.innerText?.trim() || '';
            const amt   = cells[4]?.innerText?.trim() || '';
            rows.push([title, cat, date, type, amt]);
        });

        // ── TABLE ─────────────────────────────────────────────────
        doc.autoTable({
            startY: boxY + 34,
            head: [['Title', 'Category', 'Date', 'Type', 'Amount']],
            body: rows.length ? rows : [['No transactions found', '', '', '', '']],
            headStyles: {
                fillColor: DARK,
                textColor: [255, 255, 255],
                fontStyle: 'bold',
                fontSize: 8,
                cellPadding: { top: 4, bottom: 4, left: 4, right: 4 },
            },
            bodyStyles: {
                fontSize: 8,
                textColor: [30, 41, 59],
                cellPadding: { top: 3.5, bottom: 3.5, left: 4, right: 4 },
                lineColor: [226, 232, 240],
                lineWidth: 0.25,
            },
            alternateRowStyles: { fillColor: [248, 250, 252] },
            columnStyles: {
                0: { cellWidth: 52 },
                1: { cellWidth: 38 },
                2: { cellWidth: 30 },
                3: { cellWidth: 24 },
                4: { cellWidth: 'auto', halign: 'right', fontStyle: 'bold' },
            },
            margin: { left: 14, right: 14 },
            tableLineColor: [203, 213, 225],
            tableLineWidth: 0.3,
            didParseCell(data) {
                if (data.section === 'body' && data.column.index === 4) {
                    const v = data.cell.raw || '';
                    data.cell.styles.textColor = v.startsWith('+') ? [22, 163, 74] : [220, 38, 38];
                }
                if (data.section === 'body' && data.column.index === 3) {
                    const v = (data.cell.raw || '').toLowerCase();
                    data.cell.styles.textColor = v === 'income' ? [22, 163, 74] : [220, 38, 38];
                }
            },
        });

        // ── FOOTER ────────────────────────────────────────────────
        const pageCount = doc.internal.getNumberOfPages();
        for (let i = 1; i <= pageCount; i++) {
            doc.setPage(i);
            const pH = doc.internal.pageSize.getHeight();
            doc.setFillColor(...DARK);
            doc.rect(0, pH - 10, W, 10, 'F');
            doc.setFontSize(7);
            doc.setFont('helvetica', 'normal');
            doc.setTextColor(...EM);
            doc.text('ADAM44 · Unity Manager Pro', 14, pH - 4);
            doc.setTextColor(100, 116, 139);
            doc.text(`Page ${i} of ${pageCount}`, W / 2, pH - 4, { align: 'center' });
            doc.text('Confidential Financial Document', W - 14, pH - 4, { align: 'right' });
        }

        doc.save('ADAM44-Financial-Report-{{ now()->format('Y-m-d') }}.pdf');
    }
    </script>
    @endpush
</x-app-layout>
