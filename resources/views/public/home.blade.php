<!DOCTYPE html>
<html lang="ti" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADAM44 — ናይ ፋይናንስ ምሕደራ ስርዓት</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#10b981">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="ADAM44">
    <link rel="apple-touch-icon" href="/icons/icon.svg">
    <script>
        (function(){
            var d = localStorage.getItem('adam44-dark');
            if (d === '0') document.documentElement.classList.add('pub-light');
        })();
    </script>
    <style>
        :root { --em: #10b981; --navy: #070e1c; }

        /* ── DARK (default) ── */
        body { background: var(--navy); color: #f8fafc; font-family: 'Figtree', sans-serif; overflow-x: hidden; transition: background .25s, color .25s; }
        .pub-nav            { background: rgba(7,14,28,.85); border-bottom: 1px solid rgba(255,255,255,.06); }
        .pub-link           { color: #94a3b8; }
        .pub-link:hover     { color: #f8fafc; }
        .pub-link.active    { color: #f8fafc; }
        .feat-card          { background: #0d1526; border: 1px solid rgba(255,255,255,.06); }
        .feat-card:hover    { border-color: rgba(16,185,129,.3); background: #0f1b2d; }
        .stat-text-primary  { color: #f8fafc; }
        .stat-text-muted    { color: #64748b; }
        .section-dark       { background: #080f1e; }
        .how-card-bg        { background: var(--navy); }
        .footer-bg          { background: var(--navy); border-top: 1px solid rgba(255,255,255,.06); }
        .footer-link        { color: #475569; }
        .footer-link:hover  { color: #94a3b8; }
        .sign-in-btn-border { border: 1.5px solid rgba(255,255,255,.12); color: #94a3b8; }
        .sign-in-btn-border:hover { border-color: rgba(16,185,129,.5); color: #f8fafc; }

        /* Theme-responsive text */
        .logo-name        { color: #f8fafc; }
        .hero-h1-main     { color: #f8fafc; }
        .hero-h1-word     { color: #cbd5e1; }
        .hero-body        { color: #94a3b8; }
        .stat-ti          { color: #f8fafc; }
        .stat-en          { color: #475569; }
        .section-heading  { color: #f8fafc; }
        .section-sub      { color: #64748b; }
        .how-h3           { color: #f8fafc; }
        .how-p            { color: #64748b; }
        .cta-heading      { color: #f8fafc; }
        .cta-sub          { color: #64748b; }
        .cta-micro        { color: #334155; }
        .sec-stat-border  { border-top: 1px solid rgba(255,255,255,.07); }
        .cta-secondary    { border: 1.5px solid rgba(255,255,255,.15); color: #94a3b8; }
        .cta-secondary:hover { border-color: rgba(16,185,129,.5); color: #f8fafc; }
        .mob-menu-bg      { background: rgba(7,14,28,.98); border-top: 1px solid rgba(255,255,255,.07); }
        .mob-link         { color: #94a3b8; display:block; }
        .mob-link:hover   { color: #f8fafc; background: rgba(255,255,255,.04); }
        .mob-divider      { border-color: rgba(255,255,255,.07); }
        .ham-bar          { display:block; width:22px; height:2px; border-radius:2px; background:#94a3b8; transition:all .25s; }

        /* ── LIGHT MODE ── */
        html.pub-light body              { background: #f8fafc; color: #111827; }
        html.pub-light .pub-nav          { background: rgba(255,255,255,.96); border-bottom: 1px solid #e5e7eb; backdrop-filter: blur(20px); }
        html.pub-light .pub-link         { color: #6b7280; }
        html.pub-light .pub-link:hover   { color: #111827; }
        html.pub-light .pub-link.active  { color: #059669; }
        html.pub-light .feat-card        { background: #fff; border: 1px solid #e5e7eb; }
        html.pub-light .feat-card:hover  { border-color: #10b981; background: #f0fdf4; }
        html.pub-light .stat-text-primary{ color: #111827; }
        html.pub-light .stat-text-muted  { color: #6b7280; }
        html.pub-light .section-dark     { background: #f1f5f9; }
        html.pub-light .how-card-bg      { background: #ffffff; }
        html.pub-light .footer-bg        { background: #f8fafc; border-top: 1px solid #e5e7eb; }
        html.pub-light .footer-link      { color: #6b7280; }
        html.pub-light .footer-link:hover{ color: #374151; }
        html.pub-light .sign-in-btn-border { border: 1.5px solid #d1d5db; color: #374151; }
        html.pub-light .sign-in-btn-border:hover { border-color: #10b981; color: #059669; }
        html.pub-light .blob             { opacity: .2; }
        html.pub-light .grid-bg          {
            background-image: linear-gradient(rgba(16,185,129,.05) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(16,185,129,.05) 1px, transparent 1px);
        }
        html.pub-light .logo-name        { color: #111827; }
        html.pub-light .hero-h1-main     { color: #111827; }
        html.pub-light .hero-h1-word     { color: #374151; }
        html.pub-light .hero-body        { color: #374151; }
        html.pub-light .stat-ti          { color: #111827; }
        html.pub-light .stat-en          { color: #6b7280; }
        html.pub-light .section-heading  { color: #111827; }
        html.pub-light .section-sub      { color: #6b7280; }
        html.pub-light .how-h3           { color: #111827; }
        html.pub-light .how-p            { color: #6b7280; }
        html.pub-light .cta-heading      { color: #111827; }
        html.pub-light .cta-sub          { color: #6b7280; }
        html.pub-light .cta-micro        { color: #9ca3af; }
        html.pub-light .sec-stat-border  { border-top: 1px solid #e5e7eb; }
        html.pub-light .cta-secondary    { border: 1.5px solid #d1d5db; color: #374151; }
        html.pub-light .cta-secondary:hover { border-color: #10b981; color: #059669; }
        html.pub-light .mob-menu-bg      { background: rgba(255,255,255,.99); border-top: 1px solid #e5e7eb; }
        html.pub-light .mob-link         { color: #6b7280; }
        html.pub-light .mob-link:hover   { color: #111827; background: #f9fafb; }
        html.pub-light .mob-divider      { border-color: #e5e7eb; }
        html.pub-light .ham-bar          { background: #374151; }

        .glow-btn { box-shadow: 0 8px 32px rgba(16,185,129,.35); }
        .grid-bg  {
            background-image: linear-gradient(rgba(16,185,129,.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(16,185,129,.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .blob { position:absolute; border-radius:50%; filter:blur(80px); pointer-events:none; }

        @keyframes bar-grow { 0%,100%{transform:scaleY(1)} 50%{transform:scaleY(1.3)} }
        .logo-animated:hover .bar1 { animation: bar-grow .55s ease-in-out infinite; }
        .logo-animated:hover .bar2 { animation: bar-grow .55s ease-in-out .15s infinite; }
        .logo-animated:hover .bar3 { animation: bar-grow .55s ease-in-out .30s infinite; }
        .bar1,.bar2,.bar3 { transform-origin: bottom; }

        .dm-float {
            position:fixed; bottom:24px; right:24px; z-index:999;
            display:flex; align-items:center; gap:6px; padding:8px 16px; border-radius:40px;
            border:1px solid rgba(255,255,255,.15); background:rgba(7,14,28,.8);
            backdrop-filter:blur(12px); color:#94a3b8; font-size:.74rem; font-weight:700;
            cursor:pointer; transition:all .2s; box-shadow:0 4px 20px rgba(0,0,0,.4);
        }
        .dm-float:hover { background:rgba(7,14,28,.95); color:#f8fafc; border-color:rgba(16,185,129,.4); }
        html.pub-light .dm-float { background:rgba(255,255,255,.92); border-color:#d1d5db; color:#374151; box-shadow:0 4px 20px rgba(0,0,0,.12); }
        html.pub-light .dm-float:hover { background:#fff; border-color:#10b981; color:#059669; }

        #mobile-menu { display:none; }
        #mobile-menu.open { display:block; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body>

{{-- ════════════════ NAVBAR ════════════════ --}}
<nav class="pub-nav fixed top-0 w-full z-50" style="backdrop-filter:blur(20px);">
    <div class="max-w-7xl mx-auto px-5 py-3.5 flex items-center justify-between gap-4">

        <a href="{{ route('home') }}" class="flex items-center gap-3 logo-animated flex-shrink-0">
            <div style="width:40px;height:40px;flex-shrink:0;">
                <svg viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="hgl1" x1="10" y1="4" x2="46" y2="54" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#34d399"/><stop offset="100%" stop-color="#047857"/>
                        </linearGradient>
                        <linearGradient id="hgl2" x1="0" y1="0" x2="56" y2="56" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#6ee7b7" stop-opacity=".9"/>
                            <stop offset="100%" stop-color="#10b981" stop-opacity=".3"/>
                        </linearGradient>
                        <filter id="hglow"><feDropShadow dx="0" dy="3" stdDeviation="4" flood-color="#10b981" flood-opacity=".5"/></filter>
                    </defs>
                    <path d="M28 3L46 12V31C46 42 37.5 50 28 53C18.5 50 10 42 10 31V12Z" fill="url(#hgl1)" filter="url(#hglow)"/>
                    <path d="M28 8L42 16V31C42 40 35 47 28 49.5C21 47 14 40 14 31V16Z" fill="rgba(255,255,255,.07)"/>
                    <rect class="bar1" x="17" y="34" width="5" height="10" rx="2" fill="white" opacity=".95"/>
                    <rect class="bar2" x="24.5" y="27" width="5" height="17" rx="2" fill="white" opacity=".95"/>
                    <rect class="bar3" x="32" y="20" width="5" height="24" rx="2" fill="white" opacity=".95"/>
                    <polyline points="17,37 27.5,29.5 34.5,22" stroke="rgba(255,255,255,.65)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    <polygon points="34.5,18 40,25 31,26.5" fill="rgba(255,255,255,.85)"/>
                    <ellipse cx="22" cy="18" rx="5" ry="3" fill="url(#hgl2)" opacity=".5"/>
                </svg>
            </div>
            <div>
                <div class="logo-name font-black tracking-tight leading-tight" style="font-size:1.15rem;">ADAM<span style="color:#10b981;">44</span></div>
                <div class="font-bold tracking-widest uppercase hidden sm:block" style="font-size:.54rem;color:#10b981;letter-spacing:.14em;">Unity Manager Pro</div>
            </div>
        </a>

        <div class="hidden md:flex items-center gap-7">
            <a href="#hero"                  class="pub-link active text-sm font-semibold transition-colors">Home</a>
            <a href="{{ route('about') }}"   class="pub-link text-sm font-semibold transition-colors">About</a>
            <a href="{{ route('contact') }}" class="pub-link text-sm font-semibold transition-colors">Contact</a>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('login') }}" class="sign-in-btn-border hidden sm:inline-flex items-center text-sm font-semibold px-4 py-2 rounded-xl transition-all whitespace-nowrap">ናብ ኣካውንት እቶ</a>
            <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center text-sm font-bold px-4 py-2.5 rounded-xl transition-all glow-btn whitespace-nowrap" style="background:#10b981;color:#fff;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">ብነጻ ጀምር</a>
            <button onclick="toggleMob()" id="ham-btn" class="md:hidden flex flex-col justify-center items-center w-10 h-10 rounded-xl gap-1.5 flex-shrink-0 transition-all" style="background:rgba(255,255,255,.06);" aria-label="Menu">
                <span class="ham-bar" id="hb1"></span>
                <span class="ham-bar" id="hb2"></span>
                <span class="ham-bar" id="hb3"></span>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="mob-menu-bg md:hidden">
        <div class="px-5 pt-3 pb-5 flex flex-col gap-1">
            <a href="#hero"                  onclick="closeMob()" class="mob-link text-sm font-semibold py-3 px-4 rounded-xl transition-all">Home</a>
            <a href="{{ route('about') }}"   onclick="closeMob()" class="mob-link text-sm font-semibold py-3 px-4 rounded-xl transition-all">About</a>
            <a href="{{ route('contact') }}" onclick="closeMob()" class="mob-link text-sm font-semibold py-3 px-4 rounded-xl transition-all">Contact</a>
            <div class="mob-divider border-t mt-2 pt-3 flex flex-col gap-2">
                <a href="{{ route('login') }}"    class="sign-in-btn-border text-center text-sm font-semibold py-3 px-4 rounded-xl transition-all">ናብ ኣካውንት እቶ</a>
                <a href="{{ route('register') }}" class="text-center text-sm font-bold py-3 px-4 rounded-xl glow-btn" style="background:#10b981;color:#fff;">ብነጻ ጀምር &rarr;</a>
            </div>
        </div>
    </div>
</nav>

{{-- ════════════════ HERO ════════════════ --}}
<section id="hero" class="relative min-h-screen flex items-center grid-bg" style="padding-top:80px;">
    <div class="blob w-[600px] h-[600px] top-0 -left-48" style="background:rgba(16,185,129,.08);"></div>
    <div class="blob w-[500px] h-[500px] top-1/3 -right-48" style="background:rgba(99,102,241,.06);"></div>
    <div class="blob w-80 h-80 bottom-1/4 left-1/3"         style="background:rgba(16,185,129,.05);"></div>

    <div class="relative w-full max-w-5xl mx-auto px-5 py-16 md:py-24 text-center">
        <div class="inline-flex items-center gap-2.5 mb-8 rounded-full px-5 py-2" style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);">
            <span class="w-2 h-2 rounded-full animate-pulse" style="background:#10b981;"></span>
            <span class="text-xs font-bold tracking-widest uppercase" style="color:#10b981;">ብ AI ዝሰርሕ ናይ ፋይናንስ ፕላትፎርም</span>
        </div>

        <h1 class="hero-h1-main font-black tracking-tight mb-6" style="font-size:clamp(2rem, 6.5vw, 4.8rem); line-height:1.1;">
            ንትካላትካ ብዘመናዊ <span style="color:#10b981;">መድረኽ</span><br>
            ኣመሓድሮ<span style="color:#10b981;">።</span>
        </h1>

        <p class="text-base md:text-lg max-w-2xl mx-auto mb-12 leading-relaxed hero-body">
            ኣብ ውሽጢ ሓጺር ግዜ ተመዝገብ እሞ ዳታኻ ብውሑስ መገዲ ዓቅብ።
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-20">
            <a href="{{ route('register') }}" class="w-full sm:w-auto font-extrabold px-10 py-4 rounded-2xl transition-all text-lg glow-btn" style="background:#10b981;color:#fff;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">ብነጻ ጀምር &rarr;</a>
            <a href="{{ route('login') }}"    class="cta-secondary w-full sm:w-auto font-semibold px-10 py-4 rounded-2xl transition-all text-lg">ናብ ኣካውንት እቶ</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 max-w-3xl mx-auto pt-10 sec-stat-border">
            @foreach([
                ['val'=>'100%', 'ti'=>'ውሑስ ዳታ',               'en'=>'100% Secure'],
                ['val'=>'AI',   'ti'=>'ብትግርኛ ዝተዳለወ ትንተና AI', 'en'=>'AI Tigrinya Reports'],
                ['val'=>'6+',   'ti'=>'ናይ ፋይናንስ ክፍልታት',      'en'=>'Finance Modules'],
                ['val'=>'24/7', 'ti'=>'ኩሉ ግዜ ንጡፍ',           'en'=>'Always Available'],
            ] as $s)
            <div class="text-center">
                <p class="text-3xl md:text-4xl font-black mb-1" style="color:#10b981;">{{ $s['val'] }}</p>
                <p class="text-xs sm:text-sm font-bold stat-ti leading-snug">{{ $s['ti'] }}</p>
                <p class="text-xs mt-0.5 stat-en">{{ $s['en'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════ 6 MODULES ════════════════ --}}
<section id="features" class="section-dark py-24 px-5">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="text-xs font-bold uppercase tracking-[0.2em]" style="color:#10b981;">እቶም 6 ክፍልታት</span>
            <h2 class="section-heading text-4xl md:text-5xl font-black mt-4 mb-5">
                ኩሉ ዘድልየካ<br><span style="color:#10b981;">ኣብ ሓደ ቦታ</span>
            </h2>
            <p class="section-sub text-base md:text-lg max-w-2xl mx-auto leading-relaxed">
                ካብ ኣባላት ምምዝጋብ ክሳብ AI ጸብጻብ — ኩሉ ናይ ፋይናንስ ጉዳያትካ ኣብ ሓደ ብሉጽ ፕላትፎርም።
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach([
                ['n'=>'01',
                 'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                 'c'=>'#38bdf8','bg'=>'rgba(56,189,248,.1)',
                 'ti'=>'01 — ኣባላት ምምዝጋብ',
                 'de'=>'ምሉእ መረዳእታ ኣባላት (ስም፡ ዞባ፡ ቴለፎን) ብጽሬት ዝሕዝን ንጥፈታቶም ዝከታተልን ህያው መዝገብ።'],
                ['n'=>'02',
                 'icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                 'c'=>'#10b981','bg'=>'rgba(16,185,129,.1)',
                 'ti'=>'02 — ክፍሊታትን ቅብሊትን',
                 'de'=>'ክፍሊታት ምምዝጋብ፤ ዘይከፈሉ ናብ ዝኸፈሉ ብሓንቲ ክሊክ ምቕያር፤ ፕሮፌሽናል PDF ቅብሊት ብኣውቶማቲክ ምድላው።'],
                ['n'=>'03',
                 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z',
                 'c'=>'#a78bfa','bg'=>'rgba(139,92,246,.1)',
                 'ti'=>'03 — ናይ AI ጸብጻብ ብትግርኛ',
                 'de'=>'ሰሙናዊ፡ ወርሓዊ ወይ ዓመታዊ ፋይናንሳዊ ትንተና ብብሉጽ ትግርኛ። መጠን ዕቃቤ፡ ወጻኢታትን ፋይናንሳዊ ምኽርን ዘጠቓልል።'],
                ['n'=>'04',
                 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                 'c'=>'#fbbf24','bg'=>'rgba(251,191,36,.1)',
                 'ti'=>'04 — ወጻኢታት ምቁጽጻር',
                 'de'=>'ነፍሲ ወከፍ ናይቲ ትካል ወጻኢ ብምድብን ብዕለትን ምቁጽጻር። ገንዘብካ ኣበይ ይኸይድ ከም ዘሎ ብንጹር ምርኣይ።'],
                ['n'=>'05',
                 'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                 'c'=>'#fb7185','bg'=>'rgba(251,113,133,.1)',
                 'ti'=>'05 — ናይ ምንቅስቓሳት ታሪኽ',
                 'de'=>'ኩሉ ፋይናንሳዊ ምንቅስቓሳት (ኣታዊ፡ ወጻኢ፡ ክፍሊት) ብዝርዝር ዝርኣየሉን ናብ ጸብጻብ ዝወጽእሉን ህያው ታሪኽ።'],
                ['n'=>'06',
                 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
                 'c'=>'#34d399','bg'=>'rgba(52,211,153,.1)',
                 'ti'=>'06 — ምሉእ ናይ ፋይናንስ ምምዝጋብ',
                 'de'=>'ኣባላት ምዝጋብ፡ ክፍሊታት ምምዝጋብ፡ ወጻኢታት ምፍላጥ።'],
            ] as $f)
            <div class="feat-card rounded-2xl p-7 transition-all duration-300">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center" style="background:{{ $f['bg'] }};">
                        <svg class="w-6 h-6" style="color:{{ $f['c'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $f['icon'] }}"/>
                        </svg>
                    </div>
                    <span class="font-black text-3xl leading-none" style="color:{{ $f['c'] }};opacity:.15;">{{ $f['n'] }}</span>
                </div>
                <h3 class="stat-text-primary font-bold mb-2 text-sm">{{ $f['ti'] }}</h3>
                <p class="stat-text-muted text-sm leading-relaxed">{{ $f['de'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════ HOW IT WORKS ════════════════ --}}
<section id="how" class="how-card-bg py-20 px-5 grid-bg">
    <div class="max-w-4xl mx-auto text-center">
        <span class="text-xs font-bold uppercase tracking-[0.2em]" style="color:#10b981;">ከመይ ይሰርሕ?</span>
        <h2 class="section-heading text-3xl md:text-4xl font-black mt-4 mb-14">ኣብ 3 ስጉምቲ ጀምር</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['step'=>'01','ti'=>'ተመዝገብ',       'de'=>'ኣካውንትካ ብነጻ ኣብ ትሕቲ 2 ደቓይቕ ፍጠር።',
                 'icon'=>'M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'],
                ['step'=>'02','ti'=>'ዳታካ ኣቐምጥ',   'de'=>'ኣባላት ምዝጋብ፡ ክፍሊታት ምምዝጋብ፡ ወጻኢታት ምፍላጥ።',
                 'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ['step'=>'03','ti'=>'ጸብጻብ ርኸብ',   'de'=>'ብ AI ዝተዳለወ ጸብጻብ ብቋንቋ ትግርኛ ብቕጽበት ርኸብ።',
                 'icon'=>'M13 10V3L4 14h7v7l9-11h-7z'],
            ] as $s)
            <div>
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background:rgba(16,185,129,.12);border:1px solid rgba(16,185,129,.25);">
                    <svg class="w-7 h-7" style="color:#10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $s['icon'] }}"/>
                    </svg>
                </div>
                <div class="text-xs font-black mb-2" style="color:#10b981;">STEP {{ $s['step'] }}</div>
                <h3 class="how-h3 text-xl font-black mb-2">{{ $s['ti'] }}</h3>
                <p class="how-p text-sm leading-relaxed">{{ $s['de'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════ CTA ════════════════ --}}
<section class="section-dark py-20 px-5">
    <div class="max-w-4xl mx-auto">
        <div class="relative rounded-3xl p-10 md:p-14 text-center overflow-hidden" style="background:linear-gradient(135deg,rgba(16,185,129,.15),rgba(7,14,28,1) 60%,rgba(99,102,241,.1));border:1px solid rgba(16,185,129,.25);">
            <div class="absolute inset-0 rounded-3xl" style="background:radial-gradient(ellipse at 50% 0%,rgba(16,185,129,.2),transparent 70%);pointer-events:none;"></div>
            <div class="relative">
                <div class="inline-flex items-center gap-2 mb-6 rounded-full px-4 py-1.5" style="background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);">
                    <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#10b981;"></span>
                    <span class="text-xs font-bold" style="color:#10b981;">ሕጂ ዝርከብ — Available Now</span>
                </div>
                <h2 class="cta-heading text-4xl md:text-5xl font-black mb-5">ሕጂ ጀምር<br><span style="color:#10b981;">ብነጻ</span></h2>
                <p class="cta-sub mb-10 max-w-xl mx-auto leading-relaxed text-base md:text-lg">
                    ኣብ ሓጺር ደቓይቕ ትካልካ ናይ ፋይናንስ ምሕደራ ብምሉኡ ኣቐምጥ።<br>
                    ናይ ክሬዲት ካርድ ኣይሓትትን።
                </p>
                <a href="{{ route('register') }}" class="inline-block font-extrabold px-12 py-4 rounded-2xl text-lg transition-all" style="background:#10b981;color:#fff;box-shadow:0 8px 40px rgba(16,185,129,.4);" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">ብነጻ ጀምር &rarr;</a>
                <p class="cta-micro mt-5 text-xs">ናይ ክሬዲት ካርድ የለን · ግዳያት የለን · ኩሉ ግዜ ክሰርዝ ይከኣል</p>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════ FOOTER ════════════════ --}}
<footer class="footer-bg py-6 px-5">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
        <span class="footer-link text-sm font-semibold">&copy; {{ now()->year }} ADAM44 · Unity Manager Pro</span>
        <span class="footer-link text-xs">ናይ ፋይናንስ ምሕደራ ስርዓት — ንትካላትካ ብዘመናዊ መድረኽ</span>
    </div>
</footer>

<button onclick="togglePubDark()" id="pub-dm-btn" class="dm-float" title="Toggle dark/light mode">
    <svg id="pub-sun" class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
    <svg id="pub-moon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
    <span id="pub-dm-label">Light</span>
</button>

<script>
    function togglePubDark() {
        var isLight = document.getElementById('html-root').classList.toggle('pub-light');
        localStorage.setItem('adam44-dark', isLight ? '0' : '1');
        updatePubIcons(isLight);
    }
    function updatePubIcons(isLight) {
        var sun = document.getElementById('pub-sun'), moon = document.getElementById('pub-moon'), label = document.getElementById('pub-dm-label');
        if (sun)   sun.classList.toggle('hidden',  isLight);
        if (moon)  moon.classList.toggle('hidden', !isLight);
        if (label) label.textContent = isLight ? 'Dark' : 'Light';
    }
    (function(){ updatePubIcons(document.getElementById('html-root').classList.contains('pub-light')); })();

    var mobOpen = false;
    function toggleMob() {
        mobOpen = !mobOpen;
        document.getElementById('mobile-menu').classList.toggle('open', mobOpen);
        var b1=document.getElementById('hb1'),b2=document.getElementById('hb2'),b3=document.getElementById('hb3');
        if (mobOpen) { b1.style.transform='translateY(7px) rotate(45deg)'; b2.style.opacity='0'; b3.style.transform='translateY(-7px) rotate(-45deg)'; }
        else { b1.style.transform=b3.style.transform=''; b2.style.opacity='1'; }
    }
    function closeMob() { if (mobOpen) toggleMob(); }

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js');
        });
    }
</script>
</body>
</html>
