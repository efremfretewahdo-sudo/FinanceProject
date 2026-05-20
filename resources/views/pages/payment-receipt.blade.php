<!DOCTYPE html>
<html lang="ti">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ቅብሊት #{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }} — ADAM44</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"/>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Figtree', sans-serif;
            background: #eef2f7;
            color: #111827;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px 80px;
        }

        /* ─── Page wrapper ─── */
        .page {
            background: #fff;
            width: 100%;
            max-width: 740px;
            border-radius: 4px;
            box-shadow: 0 8px 40px rgba(0,0,0,.12), 0 0 0 1px rgba(0,0,0,.06);
            overflow: hidden;
        }

        /* ─── Header band ─── */
        .hdr {
            background: linear-gradient(135deg, #03302a 0%, #064e3b 45%, #065f46 100%);
            padding: 36px 44px 32px;
            position: relative;
            overflow: hidden;
        }
        .hdr::after {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(16,185,129,.06);
        }
        .hdr-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            position: relative;
            z-index: 1;
        }
        .brand { display: flex; align-items: center; gap: 14px; }
        .brand-icon {
            width: 52px; height: 52px; flex-shrink: 0;
            background: rgba(255,255,255,.12);
            border: 1.5px solid rgba(255,255,255,.2);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-name { color: #fff; font-size: 1.5rem; font-weight: 900; letter-spacing: -.02em; line-height: 1; }
        .brand-name span { color: #6ee7b7; }
        .brand-sub { color: rgba(255,255,255,.55); font-size: .68rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; margin-top: 3px; }
        .receipt-label {
            text-align: right; position: relative; z-index: 1;
        }
        .receipt-label-ti { color: rgba(255,255,255,.55); font-size: .7rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .receipt-label-en { color: rgba(255,255,255,.35); font-size: .62rem; letter-spacing: .08em; margin-top: 2px; }
        .receipt-num { color: #6ee7b7; font-size: 1.6rem; font-weight: 900; letter-spacing: .04em; margin-top: 4px; }

        .hdr-amount {
            margin-top: 28px;
            position: relative; z-index: 1;
            display: flex; align-items: flex-end; justify-content: space-between;
        }
        .amount-block {}
        .amount-label { color: rgba(255,255,255,.5); font-size: .68rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; margin-bottom: 4px; }
        .amount-value { color: #fff; font-size: 3rem; font-weight: 900; line-height: 1; }
        .amount-sub { color: rgba(255,255,255,.45); font-size: .78rem; margin-top: 5px; }

        /* Status badge */
        .status-badge {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 7px 18px; border-radius: 40px;
            font-size: .78rem; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;
        }
        .status-paid   { background: rgba(110,231,183,.15); color: #6ee7b7; border: 1.5px solid rgba(110,231,183,.3); }
        .status-unpaid { background: rgba(251,191,36,.12);  color: #fbbf24; border: 1.5px solid rgba(251,191,36,.3); }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; background: currentColor; }

        /* ─── Divider (torn edge) ─── */
        .divider {
            height: 18px;
            background: #eef2f7;
            background-image:
                radial-gradient(circle at 18px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 54px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 90px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 126px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 162px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 198px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 234px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 270px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 306px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 342px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 378px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 414px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 450px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 486px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 522px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 558px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 594px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 630px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 666px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 702px 0, #fff 11px, transparent 11px),
                radial-gradient(circle at 738px 0, #fff 11px, transparent 11px);
            background-size: 36px 18px;
        }

        /* ─── Body ─── */
        .body { padding: 36px 44px; }

        /* Meta row */
        .meta-row {
            display: grid; grid-template-columns: 1fr 1fr 1fr;
            gap: 0;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 32px;
        }
        .meta-cell {
            padding: 14px 18px;
            border-right: 1px solid #e5e7eb;
        }
        .meta-cell:last-child { border-right: none; }
        .meta-lbl { font-size: .65rem; color: #9ca3af; font-weight: 800; text-transform: uppercase; letter-spacing: .12em; margin-bottom: 4px; }
        .meta-val { font-size: .92rem; color: #111827; font-weight: 800; }

        /* Main table */
        .tbl { width: 100%; border-collapse: collapse; }
        .tbl thead tr {
            background: #064e3b;
        }
        .tbl thead th {
            color: rgba(255,255,255,.85); font-size: .68rem; font-weight: 800;
            text-transform: uppercase; letter-spacing: .12em;
            padding: 11px 18px; text-align: left;
        }
        .tbl thead th:last-child { text-align: right; }
        .tbl tbody tr { border-bottom: 1px solid #f3f4f6; }
        .tbl tbody tr:last-child { border-bottom: none; }
        .tbl tbody tr:nth-child(even) { background: #f9fafb; }
        .tbl td {
            padding: 14px 18px; font-size: .88rem;
        }
        .tbl td.lbl { color: #6b7280; font-weight: 700; font-size: .8rem; width: 38%; }
        .tbl td.val { color: #111827; font-weight: 700; }

        .amount-cell { color: #059669 !important; font-size: 1.1rem !important; font-weight: 900 !important; }

        /* Footer */
        .ftr {
            background: #f9fafb;
            border-top: 1px dashed #d1d5db;
            padding: 20px 44px;
            text-align: center;
        }
        .ftr-text { font-size: .72rem; color: #9ca3af; line-height: 1.7; }
        .ftr-brand { font-size: .8rem; font-weight: 900; color: #059669; margin-top: 6px; }

        /* Certification row */
        .cert-row {
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 44px;
            border-top: 1px solid #f3f4f6;
        }
        .cert-left { font-size: .72rem; color: #9ca3af; }
        .cert-right { font-size: .72rem; color: #9ca3af; text-align: right; }
        .cert-sig { width: 100px; height: 1px; background: #d1d5db; margin-top: 24px; }

        /* Action bar */
        .actions {
            display: flex; gap: 10px; justify-content: center;
            padding: 20px 44px;
            background: #f8fafc;
            border-top: 1px solid #e5e7eb;
        }
        .btn-print {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg,#059669,#047857);
            color: #fff; font-size: .85rem; font-weight: 700;
            padding: 12px 28px; border-radius: 12px; border: none;
            cursor: pointer; text-decoration: none;
            box-shadow: 0 4px 16px rgba(5,150,105,.3); transition: opacity .15s;
        }
        .btn-print:hover { opacity: .9; }
        .btn-back {
            display: inline-flex; align-items: center; gap: 8px;
            background: #fff; color: #374151;
            border: 1.5px solid #e5e7eb;
            font-size: .85rem; font-weight: 600;
            padding: 12px 22px; border-radius: 12px;
            cursor: pointer; text-decoration: none;
            transition: background .15s;
        }
        .btn-back:hover { background: #f9fafb; }

        @media print {
            body { background: #fff; padding: 0; display: block; }
            .page { box-shadow: none; border-radius: 0; max-width: 100%; }
            .actions { display: none !important; }
            .divider { background: #fff; }
            @page { margin: 12mm 15mm; size: A4; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- ──────────── HEADER ──────────── --}}
    <div class="hdr">
        <div class="hdr-top">
            <div class="brand">
                <div class="brand-icon">
                    <svg width="28" height="28" viewBox="0 0 48 48" fill="none">
                        <path d="M24 3L40 10.5V27C40 36.5 33 43 24 46C15 43 8 36.5 8 27V10.5Z" fill="rgba(255,255,255,.9)"/>
                        <rect x="15" y="30" width="4.5" height="9"  rx="1.5" fill="#059669"/>
                        <rect x="21.5" y="24" width="4.5" height="15" rx="1.5" fill="#059669"/>
                        <rect x="28" y="18" width="4.5" height="21" rx="1.5" fill="#059669"/>
                        <polyline points="15,33 24.5,26 30.5,20" stroke="#047857" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
                <div>
                    <div class="brand-name">ADAM<span>44</span></div>
                    <div class="brand-sub">Unity Manager Pro</div>
                </div>
            </div>
            <div class="receipt-label">
                <div class="receipt-label-ti">ቅብሊት</div>
                <div class="receipt-label-en">Payment Receipt</div>
                <div class="receipt-num">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <div class="hdr-amount">
            <div class="amount-block">
                <div class="amount-label">ዝተኸፍለ መጠን ገንዘብ</div>
                <div class="amount-value">${{ number_format($payment->amount, 2) }}</div>
                <div class="amount-sub">ብ {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</div>
            </div>
            <div class="status-badge {{ $payment->status === 'paid' ? 'status-paid' : 'status-unpaid' }}">
                <span class="status-dot"></span>
                {{ $payment->status === 'paid' ? 'ተኸፊሉ · PAID' : 'ዘይተኸፍለ · UNPAID' }}
            </div>
        </div>
    </div>

    {{-- ──────────── TORN EDGE ──────────── --}}
    <div class="divider"></div>

    {{-- ──────────── BODY ──────────── --}}
    <div class="body">

        {{-- Meta info row --}}
        <div class="meta-row">
            <div class="meta-cell">
                <div class="meta-lbl">ቅብሊት ቚጽሪ</div>
                <div class="meta-val">#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-lbl">ዕለት</div>
                <div class="meta-val">{{ $payment->payment_date->format('d M Y') }}</div>
            </div>
            <div class="meta-cell">
                <div class="meta-lbl">ዝተዘርዘረሉ</div>
                <div class="meta-val">{{ $payment->created_at->format('d M Y · H:i') }}</div>
            </div>
        </div>

        {{-- Main detail table --}}
        <table class="tbl">
            <thead>
                <tr>
                    <th>ዝርዝር ሓበሬታ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="lbl">ኣባል / Payer</td>
                    <td class="val">{{ $payment->payer_name }}</td>
                </tr>
                <tr>
                    <td class="lbl">መጠን ገንዘብ / Amount</td>
                    <td class="val amount-cell">${{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr>
                    <td class="lbl">ኣገባብ / Method</td>
                    <td class="val">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                </tr>
                <tr>
                    <td class="lbl">ኩነታት / Status</td>
                    <td class="val">
                        <span style="display:inline-flex;align-items:center;gap:5px;padding:3px 12px;border-radius:20px;font-size:.74rem;font-weight:800;{{ $payment->status==='paid' ? 'background:#d1fae5;color:#059669;' : 'background:#fef3c7;color:#d97706;' }}">
                            {{ $payment->status === 'paid' ? '✓ ተኸፊሉ' : '⏳ ዘይተኸፍለ' }}
                        </span>
                    </td>
                </tr>
                @if($payment->notes)
                <tr>
                    <td class="lbl">ተወሳኺ ሓሳብ / Notes</td>
                    <td class="val" style="color:#6b7280; font-weight:500;">{{ $payment->notes }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    {{-- ──────────── FOOTER ──────────── --}}
    <div class="ftr">
        <div class="ftr-text">ይቐንየልና — Thanks for your payment.</div>
        <div class="ftr-brand">Issued by: {{ $user->name }}</div>
    </div>

    {{-- ──────────── ACTIONS (hidden on print) ──────────── --}}
    <div class="actions">
        <button onclick="window.print()" class="btn-print">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            ህትመት / Print PDF
        </button>
        <a href="{{ route('payments') }}" class="btn-back">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            ተመለስ
        </a>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        setTimeout(function() { window.print(); }, 600);
    });
</script>
</body>
</html>
