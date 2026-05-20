<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel — ADAM44</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        * { box-sizing: border-box; }
        body { background: #f4f6fa; font-family: 'Figtree', sans-serif; margin: 0; }
        .adm-header {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        .adm-brand { display:flex; align-items:center; gap:10px; }
        .adm-brand-icon {
            width:36px; height:36px; border-radius:10px;
            background:linear-gradient(135deg,#10b981,#059669);
            display:flex; align-items:center; justify-content:center;
            box-shadow: 0 4px 12px rgba(16,185,129,.3);
        }
        .adm-title { font-size:1rem; font-weight:700; color:#111827; }
        .adm-title span { color:#6b7280; font-weight:500; font-size:.875rem; margin-left:8px; }
        .adm-header-right { display:flex; align-items:center; gap:10px; }
        .adm-btn-icon {
            width:36px; height:36px; border-radius:9px; border:1px solid #e5e7eb;
            background:#fff; display:flex; align-items:center; justify-content:center;
            cursor:pointer; color:#6b7280; transition:all .15s;
        }
        .adm-btn-icon:hover { background:#f9fafb; color:#111827; border-color:#d1d5db; }
        .adm-btn-logout {
            display:flex; align-items:center; gap:6px; padding:0 14px; height:36px;
            border-radius:9px; border:1px solid #fecaca; background:#fff5f5;
            color:#ef4444; font-size:.8rem; font-weight:600; cursor:pointer;
            transition:all .15s; text-decoration:none;
        }
        .adm-btn-logout:hover { background:#fee2e2; border-color:#fca5a5; }

        /* Tab nav */
        .adm-nav {
            background:#fff;
            border-bottom:1px solid #e5e7eb;
            padding:0 32px;
            display:flex; gap:2px;
        }
        .adm-tab {
            display:flex; align-items:center; gap:6px;
            padding:14px 16px; font-size:.8375rem; font-weight:600;
            color:#6b7280; border-bottom:2px solid transparent;
            text-decoration:none; transition:all .15s; cursor:pointer;
        }
        .adm-tab:hover { color:#374151; }
        .adm-tab.active { color:#10b981; border-bottom-color:#10b981; }
        .adm-tab .tab-badge {
            font-size:.68rem; font-weight:700; padding:1px 6px;
            border-radius:20px; background:#fee2e2; color:#ef4444;
        }
        .adm-tab.active .tab-badge { background:#d1fae5; color:#059669; }

        /* Content area */
        .adm-content { max-width:1280px; margin:0 auto; padding:28px 32px; }

        /* Stat cards */
        .stat-card {
            background:#fff; border-radius:14px; padding:22px 24px;
            border:1px solid #e5e7eb; box-shadow:0 1px 4px rgba(0,0,0,.04);
        }
        .stat-label { font-size:.73rem; font-weight:600; color:#9ca3af; text-transform:uppercase; letter-spacing:.08em; }
        .stat-value { font-size:2rem; font-weight:800; color:#111827; margin:6px 0 8px; line-height:1; }
        .stat-badge {
            display:inline-flex; align-items:center; gap:4px;
            font-size:.73rem; font-weight:700; padding:3px 8px; border-radius:20px;
        }
        .stat-badge.up   { background:#d1fae5; color:#059669; }
        .stat-badge.down { background:#fee2e2; color:#ef4444; }
        .stat-badge.neu  { background:#f3f4f6; color:#6b7280; }

        /* Section cards */
        .section-card {
            background:#fff; border-radius:14px; border:1px solid #e5e7eb;
            box-shadow:0 1px 4px rgba(0,0,0,.04); overflow:hidden;
        }
        .section-card-header { padding:18px 22px 14px; border-bottom:1px solid #f3f4f6; }
        .section-card-title { font-size:.9rem; font-weight:700; color:#111827; }
        .section-card-sub { font-size:.75rem; color:#9ca3af; margin-top:2px; }
        .section-card-body { padding:18px 22px; }

        /* Activity cards */
        .activity-card {
            border-radius:14px; padding:18px 20px;
            position:relative; overflow:hidden;
        }
        .activity-card-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.09em; opacity:.8; }
        .activity-card-value { font-size:1.75rem; font-weight:800; margin:6px 0 4px; }
        .activity-card-sub { font-size:.75rem; opacity:.7; }

        /* Action buttons */
        .action-btn {
            display:flex; align-items:center; gap:10px;
            width:100%; padding:12px 16px; border-radius:10px;
            border:1px solid #e5e7eb; background:#fff; text-decoration:none;
            font-size:.83rem; font-weight:600; color:#374151;
            transition:all .15s; cursor:pointer; margin-bottom:8px;
        }
        .action-btn:hover { border-color:#10b981; color:#059669; background:#f0fdf9; }
        .action-btn-icon {
            width:34px; height:34px; border-radius:8px;
            display:flex; align-items:center; justify-content:center; flex-shrink:0;
        }

        /* Table */
        .adm-table { width:100%; border-collapse:collapse; }
        .adm-table th { padding:10px 14px; text-align:left; font-size:.72rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.07em; border-bottom:1px solid #f3f4f6; }
        .adm-table td { padding:12px 14px; font-size:.83rem; color:#374151; border-bottom:1px solid #f9fafb; vertical-align:middle; }
        .adm-table tr:last-child td { border-bottom:none; }
        .adm-table tr:hover td { background:#fafafa; }

        /* Pills */
        .pill { display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:20px; font-size:.72rem; font-weight:700; }
        .pill-green  { background:#d1fae5; color:#059669; }
        .pill-amber  { background:#fef3c7; color:#d97706; }
        .pill-rose   { background:#fee2e2; color:#ef4444; }
        .pill-slate  { background:#f1f5f9; color:#64748b; }
        .pill-violet { background:#ede9fe; color:#7c3aed; }
        .pill-sky    { background:#e0f2fe; color:#0284c7; }
    </style>
</head>
<body>

{{-- ══ HEADER ══ --}}
<header class="adm-header">
    <div class="adm-brand">
        <div class="adm-brand-icon">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <span class="adm-title">Admin Panel <span>· ADAM44</span></span>
        </div>
    </div>

    <div class="adm-header-right">
        <div class="adm-btn-icon" title="Settings">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 rounded-lg" style="background:#f9fafb; border:1px solid #e5e7eb;">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white"
                 style="background:linear-gradient(135deg,#10b981,#059669);">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->name }}</span>
            <span class="pill pill-rose text-xs">SA</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="adm-btn-logout">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</header>

{{-- ══ TAB NAV ══ --}}
<nav class="adm-nav">
    @php $pending = \App\Models\User::where('is_approved', false)->count(); @endphp
    <a href="{{ route('admin.dashboard') }}"
       class="adm-tab {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
    </a>
    <a href="{{ route('admin.users') }}"
       class="adm-tab {{ request()->routeIs('admin.users') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Users
        @if($pending > 0)
        <span class="tab-badge">{{ $pending }}</span>
        @endif
    </a>
    <a href="{{ route('admin.messages') }}"
       class="adm-tab {{ request()->routeIs('admin.messages') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Messages
        @php $unread = \App\Models\ContactMessage::where('is_read', false)->count(); @endphp
        @if($unread > 0)
        <span class="tab-badge">{{ $unread }}</span>
        @endif
    </a>
    <a href="{{ route('admin.subscriptions') }}"
       class="adm-tab {{ request()->routeIs('admin.subscriptions') ? 'active' : '' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
        Subscriptions
    </a>
</nav>

{{-- Flash --}}
@if(session('success') || session('error'))
<div style="max-width:1280px; margin:0 auto; padding:16px 32px 0;">
    @if(session('success'))
    <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#15803d; padding:10px 16px; border-radius:10px; font-size:.84rem; font-weight:500; display:flex; align-items:center; gap:8px;">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:#fff5f5; border:1px solid #fecaca; color:#dc2626; padding:10px 16px; border-radius:10px; font-size:.84rem; font-weight:500; display:flex; align-items:center; gap:8px;">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
        {{ session('error') }}
    </div>
    @endif
</div>
@endif

{{-- Page content --}}
<main class="adm-content">
    {{ $slot }}
</main>

@stack('scripts')
</body>
</html>
