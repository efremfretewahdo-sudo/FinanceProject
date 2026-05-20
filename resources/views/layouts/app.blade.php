<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'ADAM44' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#10b981">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ADAM44">
    <link rel="apple-touch-icon" href="/icons/icon.svg">
    @stack('styles')
    <style>
        :root { --sw: 258px; }
        body { background: #f1f5f9; transition: background .2s, color .2s; }

        /* ── Nav links ── */
        .nav-a {
            display:flex; align-items:center; gap:9px; padding:9px 11px;
            border-radius:10px; font-size:.84rem; font-weight:700;
            color:#94a3b8; transition:all .15s; border-left:3px solid transparent;
            text-decoration:none; white-space:nowrap; letter-spacing:.01em;
        }
        .nav-a:hover { color:#e2e8f0; background:rgba(255,255,255,.08); }
        .nav-a.on  { color:#fff; background:rgba(255,255,255,.1); }
        .nav-a.adm:hover { color:#fb7185; background:rgba(251,113,133,.08); }
        .nav-a.adm.on    { color:#fb7185; background:rgba(251,113,133,.13); border-left-color:#fb7185; }

        .nav-icon {
            width:30px; height:30px; border-radius:8px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            transition:transform .15s;
        }
        .nav-a:hover .nav-icon { transform:scale(1.08); }

        .sec { font-size:.62rem; font-weight:800; letter-spacing:.14em; text-transform:uppercase; color:#334155; padding:16px 12px 4px; }
        .badge { font-size:.62rem; font-weight:800; padding:2px 6px; border-radius:20px; margin-left:auto; }

        /* ── Dark Mode — Bulletproof ── */
        body, main, h1, h2, h3, h4, h5, h6, p, span, label, td, th, li, a, button, input, select, textarea {
            transition: background-color .25s, color .25s, border-color .2s;
        }

        html.dark body { background:#0d1117; color:#e2e8f0; }

        /* Headings & text — guaranteed visible in dark mode */
        html.dark h1,
        html.dark h2,
        html.dark h3,
        html.dark h4,
        html.dark h5,
        html.dark h6 { color:#f1f5f9 !important; }
        html.dark p   { color:#cbd5e1; }
        html.dark label { color:#94a3b8 !important; }
        html.dark td  { color:#cbd5e1; }
        html.dark th  { color:#8b949e; }
        html.dark li  { color:#cbd5e1; }

        /* Backgrounds */
        html.dark .bg-white       { background:#161b22 !important; }
        html.dark .bg-slate-50    { background:#0d1117 !important; }
        html.dark .bg-slate-100   { background:#1c2128 !important; }
        html.dark .bg-gray-50     { background:#0d1117 !important; }
        html.dark .bg-gray-100    { background:#1c2128 !important; }

        /* Borders */
        html.dark .border-slate-100 { border-color:#30363d !important; }
        html.dark .border-slate-200 { border-color:#30363d !important; }
        html.dark .border-gray-200  { border-color:#30363d !important; }

        /* Text utility classes */
        html.dark .text-slate-800  { color:#e2e8f0 !important; }
        html.dark .text-slate-700  { color:#cdd5e0 !important; }
        html.dark .text-slate-600  { color:#b0bac8 !important; }
        html.dark .text-slate-500  { color:#8b949e !important; }
        html.dark .text-slate-400  { color:#6e7681 !important; }
        html.dark .text-slate-300  { color:#6e7681 !important; }
        html.dark .text-gray-800   { color:#e2e8f0 !important; }
        html.dark .text-gray-700   { color:#cdd5e0 !important; }
        html.dark .text-gray-600   { color:#b0bac8 !important; }
        html.dark .text-gray-500   { color:#8b949e !important; }
        html.dark .text-gray-400   { color:#6e7681 !important; }
        html.dark .text-black      { color:#f1f5f9 !important; }

        /* Shadows & decorative */
        html.dark .shadow-sm { box-shadow:0 1px 3px rgba(0,0,0,.5) !important; }
        html.dark .shadow    { box-shadow:0 2px 8px rgba(0,0,0,.5) !important; }

        /* Form elements */
        html.dark input, html.dark select, html.dark textarea {
            background:#161b22 !important; color:#e2e8f0 !important; border-color:#30363d !important;
        }
        html.dark input::placeholder, html.dark textarea::placeholder { color:#4b5563 !important; }

        /* Tinted backgrounds */
        html.dark .bg-emerald-50  { background:rgba(16,185,129,.12) !important; }
        html.dark .bg-rose-50     { background:rgba(239,68,68,.12)   !important; }
        html.dark .bg-amber-50    { background:rgba(245,158,11,.12)  !important; }
        html.dark .bg-violet-50   { background:rgba(139,92,246,.12)  !important; }
        html.dark .bg-sky-50      { background:rgba(14,165,233,.12)  !important; }
        html.dark .bg-blue-50     { background:rgba(59,130,246,.12)  !important; }

        /* Border colors for tinted elements */
        html.dark .border-emerald-200 { border-color:rgba(16,185,129,.3) !important; }
        html.dark .border-rose-200    { border-color:rgba(239,68,68,.3)  !important; }

        /* Text in tinted elements */
        html.dark .text-emerald-800 { color:#6ee7b7 !important; }
        html.dark .text-rose-800    { color:#fca5a5 !important; }

        /* Tables */
        html.dark table thead     { background:#1c2128 !important; }
        html.dark table tbody tr  { border-color:#30363d !important; }
        html.dark table tbody tr:hover { background:#161b22 !important; }
        html.dark .divide-slate-50 > * { border-color:#30363d !important; }
        html.dark .hover\:bg-slate-50:hover { background:#1c2128 !important; }

        /* Cards & panels */
        html.dark .rounded-2xl.border { border-color:#30363d !important; }
        html.dark .rounded-xl.border  { border-color:#30363d !important; }

        /* Stat cards */
        html.dark .stat-label { color:#8b949e !important; }
        html.dark .stat-value { color:#f1f5f9 !important; }

        /* ── Animated Logo ── */
        @keyframes bar-grow {
            0%,100% { transform: scaleY(1); opacity:.95; }
            50%      { transform: scaleY(1.28); opacity:1; }
        }
        .logo-wrap { display:flex; align-items:center; gap:12px; cursor:pointer; }
        .logo-wrap:hover .logo-bar-1 { animation: bar-grow .55s ease-in-out infinite; }
        .logo-wrap:hover .logo-bar-2 { animation: bar-grow .55s ease-in-out .15s infinite; }
        .logo-wrap:hover .logo-bar-3 { animation: bar-grow .55s ease-in-out .3s  infinite; }
        .logo-bar-1, .logo-bar-2, .logo-bar-3 { transform-origin: bottom; }
        @keyframes logo-pulse {
            0%,100% { filter:drop-shadow(0 2px 3px rgba(16,185,129,.5)); }
            50%      { filter:drop-shadow(0 4px 12px rgba(16,185,129,.9)); }
        }
        .logo-wrap:hover svg { animation: logo-pulse .8s ease-in-out infinite; }
    </style>
</head>
<body class="font-sans antialiased" x-data="{ open: false }">

{{-- Mobile overlay --}}
<div x-show="open" @click="open=false" x-transition.opacity
     class="fixed inset-0 bg-black/70 z-40 md:hidden" style="display:none;"></div>

{{-- ═══════════════════ SIDEBAR ═══════════════════ --}}
<aside :class="open ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 flex flex-col transform transition-transform duration-300 ease-in-out md:translate-x-0"
       style="width:var(--sw); background:linear-gradient(180deg,#060d1a 0%,#070e1c 100%); border-right:1px solid rgba(255,255,255,.05);">

    {{-- ── LOGO / BRAND ── --}}
    <div class="px-4 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
        <a href="{{ route('dashboard') }}" class="logo-wrap">

            {{-- Animated Shield + Growth Logo --}}
            <div class="flex-shrink-0" style="width:42px; height:42px;">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="sg1" x1="8" y1="3" x2="40" y2="46" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#34d399"/>
                            <stop offset="100%" stop-color="#059669"/>
                        </linearGradient>
                        <filter id="sglow">
                            <feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#10b981" flood-opacity="0.5"/>
                        </filter>
                    </defs>
                    <path d="M24 3L40 10.5V27C40 36.5 33 43 24 46C15 43 8 36.5 8 27V10.5Z"
                          fill="url(#sg1)" filter="url(#sglow)"/>
                    <path d="M24 7L37 13.5V27C37 35 31 40.5 24 43C17 40.5 11 35 11 27V13.5Z"
                          fill="rgba(255,255,255,.08)"/>
                    <rect class="logo-bar-1" x="15" y="30" width="4.5" height="9"  rx="1.5" fill="white" opacity=".95"/>
                    <rect class="logo-bar-2" x="21.5" y="24" width="4.5" height="15" rx="1.5" fill="white" opacity=".95"/>
                    <rect class="logo-bar-3" x="28"   y="18" width="4.5" height="21" rx="1.5" fill="white" opacity=".95"/>
                    <polyline points="15,33 24.5,26 30.5,20" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none" opacity=".7"/>
                    <polygon points="30.5,17 35,21 28,23" fill="white" opacity=".9"/>
                </svg>
            </div>

            <div class="min-w-0">
                <p class="font-black text-sm leading-tight tracking-tight" style="color:#f8fafc;">
                    ADAM<span style="color:#10b981;">44</span>
                </p>
                <p style="font-size:.65rem; color:#10b981; font-weight:700; letter-spacing:.06em; text-transform:uppercase;">Unity Manager Pro</p>
            </div>
        </a>
    </div>

    {{-- ── NAVIGATION ── --}}
    <nav class="flex-1 overflow-y-auto px-2.5 pb-3 pt-1" style="scrollbar-width:thin;scrollbar-color:#1e293b transparent;">

        <p class="sec">ቀንዲ</p>

        {{-- ዳሽቦርድ --}}
        <a href="{{ route('dashboard') }}"
           class="nav-a {{ request()->routeIs('dashboard') ? 'on' : '' }}"
           style="{{ request()->routeIs('dashboard') ? 'border-left-color:#10b981;' : '' }}">
            <span class="nav-icon" style="background:{{ request()->routeIs('dashboard') ? 'rgba(16,185,129,.25)' : 'rgba(16,185,129,.1)' }};">
                <svg class="w-4 h-4" style="color:#10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </span>
            <span>ዳሽቦርድ</span>
        </a>

        {{-- ኣባላት --}}
        <a href="{{ route('members') }}"
           class="nav-a {{ request()->routeIs('members') ? 'on' : '' }}"
           style="{{ request()->routeIs('members') ? 'border-left-color:#3b82f6;' : '' }}">
            <span class="nav-icon" style="background:{{ request()->routeIs('members') ? 'rgba(59,130,246,.25)' : 'rgba(59,130,246,.1)' }};">
                <svg class="w-4 h-4" style="color:#60a5fa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </span>
            <span>ኣባላት</span>
        </a>

        <p class="sec">ፋይናንስ</p>

        {{-- ክፍሊታት --}}
        <a href="{{ route('payments') }}"
           class="nav-a {{ request()->routeIs('payments*') ? 'on' : '' }}"
           style="{{ request()->routeIs('payments*') ? 'border-left-color:#8b5cf6;' : '' }}">
            <span class="nav-icon" style="background:{{ request()->routeIs('payments*') ? 'rgba(139,92,246,.25)' : 'rgba(139,92,246,.1)' }};">
                <svg class="w-4 h-4" style="color:#a78bfa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </span>
            <span>ክፍሊታት</span>
        </a>

        {{-- ዘይከፈሉ --}}
        <a href="{{ route('unpaid') }}"
           class="nav-a {{ request()->routeIs('unpaid') ? 'on' : '' }}"
           style="{{ request()->routeIs('unpaid') ? 'border-left-color:#ef4444;' : '' }}">
            <span class="nav-icon" style="background:{{ request()->routeIs('unpaid') ? 'rgba(239,68,68,.25)' : 'rgba(239,68,68,.1)' }};">
                <svg class="w-4 h-4" style="color:#f87171;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </span>
            <span>ዘይከፈሉ</span>
            <span class="badge" style="background:rgba(244,63,94,.2);color:#fb7185;">!</span>
        </a>

        {{-- ካልእ ኣታዊ --}}
        <a href="{{ route('other-income') }}"
           class="nav-a {{ request()->routeIs('other-income') ? 'on' : '' }}"
           style="{{ request()->routeIs('other-income') ? 'border-left-color:#f59e0b;' : '' }}">
            <span class="nav-icon" style="background:{{ request()->routeIs('other-income') ? 'rgba(245,158,11,.25)' : 'rgba(245,158,11,.1)' }};">
                <svg class="w-4 h-4" style="color:#fbbf24;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
            </span>
            <span>ካልእ ኣታዊ</span>
        </a>

        {{-- ወጻኢታት --}}
        <a href="{{ route('expenses') }}"
           class="nav-a {{ request()->routeIs('expenses') ? 'on' : '' }}"
           style="{{ request()->routeIs('expenses') ? 'border-left-color:#f97316;' : '' }}">
            <span class="nav-icon" style="background:{{ request()->routeIs('expenses') ? 'rgba(249,115,22,.25)' : 'rgba(249,115,22,.1)' }};">
                <svg class="w-4 h-4" style="color:#fb923c;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/>
                </svg>
            </span>
            <span>ወጻኢታት</span>
        </a>

        {{-- ታሪኽ --}}
        <a href="{{ route('transactions.index') }}"
           class="nav-a {{ request()->routeIs('transactions.*') ? 'on' : '' }}"
           style="{{ request()->routeIs('transactions.*') ? 'border-left-color:#06b6d4;' : '' }}">
            <span class="nav-icon" style="background:{{ request()->routeIs('transactions.*') ? 'rgba(6,182,212,.25)' : 'rgba(6,182,212,.1)' }};">
                <svg class="w-4 h-4" style="color:#22d3ee;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
            </span>
            <span>ታሪኽ</span>
        </a>

        <p class="sec">መሳርሒ</p>

        {{-- ትንተና AI --}}
        <a href="{{ route('ai-insights') }}"
           class="nav-a {{ request()->routeIs('ai-insights') ? 'on' : '' }}"
           style="{{ request()->routeIs('ai-insights') ? 'border-left-color:#a78bfa;' : '' }}">
            <span class="nav-icon" style="background:{{ request()->routeIs('ai-insights') ? 'rgba(167,139,250,.25)' : 'rgba(167,139,250,.1)' }};">
                <svg class="w-4 h-4" style="color:#c4b5fd;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </span>
            <span>ትንተና AI</span>
            <span class="badge" style="background:rgba(139,92,246,.2);color:#a78bfa;">AI</span>
        </a>

        {{-- ሕሳብ --}}
        <a href="{{ route('calculator') }}"
           class="nav-a {{ request()->routeIs('calculator') ? 'on' : '' }}"
           style="{{ request()->routeIs('calculator') ? 'border-left-color:#10b981;' : '' }}">
            <span class="nav-icon" style="background:{{ request()->routeIs('calculator') ? 'rgba(16,185,129,.25)' : 'rgba(16,185,129,.1)' }};">
                <svg class="w-4 h-4" style="color:#34d399;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </span>
            <span>ሕሳብ</span>
        </a>

        @if(Auth::user()->email === env('ADMIN_EMAIL', 'efremfretewahdo@gmail.com'))
        <p class="sec" style="color:#7f1d1d;">ስርዓት</p>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-a adm {{ request()->routeIs('admin.*') ? 'on' : '' }}">
            <span class="nav-icon" style="background:{{ request()->routeIs('admin.*') ? 'rgba(251,113,133,.2)' : 'rgba(251,113,133,.08)' }};">
                <svg class="w-4 h-4" style="color:#fb7185;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </span>
            <span>ኣድሚን ፓነል</span>
            <span class="badge" style="background:rgba(251,113,133,.18);color:#fb7185;">SA</span>
        </a>
        @endif
    </nav>

    {{-- ── BOTTOM (Profile + Sign Out) ── --}}
    <div class="px-2.5 py-3" style="border-top:1px solid rgba(255,255,255,.06);">
        <a href="{{ route('profile.edit') }}"
           class="nav-a {{ request()->routeIs('profile.*') ? 'on' : '' }}">
            <span class="nav-icon" style="background:rgba(100,116,139,.12);">
                <svg class="w-4 h-4" style="color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </span>
            <span>መገለጺ</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-a w-full text-left"
                    onmouseover="this.style.color='#fb7185';this.style.background='rgba(251,113,133,.08)'"
                    onmouseout="this.style.color='';this.style.background=''">
                <span class="nav-icon" style="background:rgba(239,68,68,.08);">
                    <svg class="w-4 h-4" style="color:#f87171;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </span>
                ምውጻእ
            </button>
        </form>

        {{-- Dark mode toggle --}}
        <button id="dm-toggle-desk" onclick="toggleDark()" title="Toggle dark mode"
                class="nav-a w-full text-left mt-1">
            <span class="nav-icon" style="background:rgba(100,116,139,.1);">
                <svg id="dm-sun-desk" class="w-4 h-4 hidden" style="color:#fbbf24;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                <svg id="dm-moon-desk" class="w-4 h-4" style="color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </span>
            <span id="dm-label-desk">Dark Mode</span>
        </button>

        {{-- User chip --}}
        <div class="flex items-center gap-2.5 mt-2.5 pt-2.5 px-2" style="border-top:1px solid rgba(255,255,255,.05);">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black text-white flex-shrink-0"
                 style="background:linear-gradient(135deg,#10b981,#059669);">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold truncate" style="color:#e2e8f0;">{{ Auth::user()->name }}</p>
                <p class="truncate" style="font-size:.65rem;color:#475569;">{{ Auth::user()->email }}</p>
            </div>
            @if(Auth::user()->email === env('ADMIN_EMAIL', 'efremfretewahdo@gmail.com'))
            <span class="badge flex-shrink-0" style="background:rgba(251,113,133,.18);color:#fb7185;">SA</span>
            @endif
        </div>
    </div>
</aside>

{{-- ═══════════════════ MAIN ═══════════════════ --}}
<div id="main" class="flex flex-col min-h-screen" style="margin-left:0;">

    {{-- Mobile header --}}
    <header class="md:hidden sticky top-0 z-30 flex items-center justify-between px-4 py-3 shadow-lg"
            style="background:#070e1c; border-bottom:1px solid rgba(255,255,255,.06);">
        <div class="flex items-center gap-3">
            <button @click="open=true" style="color:#64748b;">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-black text-sm" style="color:#f8fafc;">
                ADAM<span style="color:#10b981;">44</span>
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button id="dm-toggle-mob" onclick="toggleDark()" title="Toggle dark mode"
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                    style="background:rgba(255,255,255,.07); color:#94a3b8;">
                <svg id="dm-sun-mob" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                <svg id="dm-moon-mob" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </button>
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-black text-white"
                 style="background:linear-gradient(135deg,#10b981,#059669);">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
        </div>
    </header>

    {{-- Flash --}}
    @if(session('success') || session('error'))
    <div class="px-6 pt-5">
        @if(session('success'))
        <div class="mb-3 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm shadow-sm">
            <svg class="w-4 h-4 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-3 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm shadow-sm">
            <svg class="w-4 h-4 flex-shrink-0 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            {{ session('error') }}
        </div>
        @endif
    </div>
    @endif

    <main class="flex-1 px-4 md:px-8 pb-10 pt-2">{{ $slot }}</main>
</div>

<script>
    (function(){
        var m = document.getElementById('main');
        function fix(){ m.style.marginLeft = window.innerWidth >= 768 ? 'var(--sw)' : '0'; }
        fix(); window.addEventListener('resize', fix);
    })();
    (function(){
        if(localStorage.getItem('adam44-dark') === '1'){
            document.getElementById('html-root').classList.add('dark');
        }
    })();
    function toggleDark(){
        var html = document.getElementById('html-root');
        var isDark = html.classList.toggle('dark');
        localStorage.setItem('adam44-dark', isDark ? '1' : '0');
        updateDMIcons(isDark);
    }
    function updateDMIcons(isDark){
        ['mob','desk'].forEach(function(s){
            var sun  = document.getElementById('dm-sun-'  + s);
            var moon = document.getElementById('dm-moon-' + s);
            var lbl  = document.getElementById('dm-label-' + s);
            if(sun)  sun.classList.toggle('hidden', !isDark);
            if(moon) moon.classList.toggle('hidden',  isDark);
            if(lbl)  lbl.textContent = isDark ? 'Light Mode' : 'Dark Mode';
        });
    }
    document.addEventListener('DOMContentLoaded', function(){
        updateDMIcons(document.getElementById('html-root').classList.contains('dark'));
    });
</script>
@stack('scripts')
<script>if('serviceWorker'in navigator){window.addEventListener('load',()=>navigator.serviceWorker.register('/sw.js'));}</script>
</body>
</html>
