<!DOCTYPE html>
<html lang="ti" id="html-root">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ብዛዕብና — ADAM44 Unity Manager Pro</title>
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
        * { box-sizing: border-box; }
        :root { --em: #10b981; --navy: #020617; }

        /* ── DARK (default) ── */
        body { background: var(--navy); color: #f8fafc; font-family: 'Figtree', sans-serif; transition: background .3s, color .3s; overflow-x: hidden; }
        .abt-nav { background: rgba(2,6,23,.85); border-bottom: 1px solid rgba(255,255,255,.08); }
        .abt-nav-link { color: #64748b; font-size: .88rem; font-weight: 600; transition: color .15s; }
        .abt-nav-link:hover { color: #f8fafc; }
        .abt-card { background: #0d1526; border: 1px solid rgba(255,255,255,.06); transition: border-color .2s, transform .2s; }
        .abt-card:hover { border-color: rgba(16,185,129,.3); transform: translateY(-2px); }
        .abt-stat-card { background: rgba(16,185,129,.06); border: 1px solid rgba(16,185,129,.15); }
        .abt-section-alt { background: #080f1e; }
        .abt-footer { background: var(--navy); border-top: 1px solid rgba(255,255,255,.06); }
        .abt-h { color: #f8fafc; }
        .abt-muted { color: #94a3b8; }
        .abt-sub { color: #64748b; }
        .abt-link { color: #64748b; transition: color .15s; }
        .abt-link:hover { color: #94a3b8; }
        .abt-pill { background: rgba(16,185,129,.1); border: 1px solid rgba(16,185,129,.25); color: #10b981; }
        .logo-name { color: #f8fafc; }
        .sign-in-btn-border { border: 1.5px solid rgba(255,255,255,.12); color: #94a3b8; }
        .sign-in-btn-border:hover { border-color: rgba(16,185,129,.5); color: #f8fafc; }
        .mob-menu-bg { background: rgba(2,6,23,.98); border-top: 1px solid rgba(255,255,255,.07); }
        .mob-link { color: #94a3b8; display:block; }
        .mob-link:hover { color: #f8fafc; background: rgba(255,255,255,.04); }
        .mob-divider { border-color: rgba(255,255,255,.07); }
        .ham-bar { display:block; width:22px; height:2px; border-radius:2px; background:#94a3b8; transition:all .25s; }

        /* ── LIGHT MODE ── */
        html.pub-light body { background: #f8fafc; color: #111827; }
        html.pub-light .abt-nav { background: rgba(255,255,255,.95); border-bottom: 1px solid #e5e7eb; }
        html.pub-light .abt-nav-link { color: #6b7280; }
        html.pub-light .abt-nav-link:hover { color: #111827; }
        html.pub-light .abt-card { background: #fff; border: 1px solid #e5e7eb; }
        html.pub-light .abt-card:hover { border-color: #10b981; }
        html.pub-light .abt-stat-card { background: #f0fdf4; border: 1px solid #bbf7d0; }
        html.pub-light .abt-section-alt { background: #f1f5f9; }
        html.pub-light .abt-footer { background: #f8fafc; border-top: 1px solid #e5e7eb; }
        html.pub-light .abt-h { color: #111827; }
        html.pub-light .abt-muted { color: #374151; }
        html.pub-light .abt-sub { color: #6b7280; }
        html.pub-light .abt-link { color: #6b7280; }
        html.pub-light .abt-link:hover { color: #374151; }
        html.pub-light .abt-pill { background: #d1fae5; border-color: #a7f3d0; color: #059669; }
        html.pub-light .logo-name { color: #111827; }
        html.pub-light .sign-in-btn-border { border: 1.5px solid #d1d5db; color: #374151; }
        html.pub-light .sign-in-btn-border:hover { border-color: #10b981; color: #059669; }
        html.pub-light .mob-menu-bg { background: rgba(255,255,255,.99); border-top: 1px solid #e5e7eb; }
        html.pub-light .mob-link { color: #6b7280; }
        html.pub-light .mob-link:hover { color: #111827; background: #f9fafb; }
        html.pub-light .mob-divider { border-color: #e5e7eb; }
        html.pub-light .ham-bar { background: #374151; }

        .dm-float {
            position:fixed; bottom:24px; right:24px; z-index:999;
            display:flex; align-items:center; gap:6px; padding:8px 16px; border-radius:40px;
            border:1px solid rgba(255,255,255,.15); background:rgba(2,6,23,.8); backdrop-filter:blur(12px);
            color:#94a3b8; font-size:.74rem; font-weight:700; cursor:pointer; transition:all .2s;
            box-shadow:0 4px 20px rgba(0,0,0,.4);
        }
        .dm-float:hover { background:rgba(2,6,23,.95); color:#f8fafc; border-color:rgba(16,185,129,.4); }
        html.pub-light .dm-float { background:rgba(255,255,255,.9); border-color:#d1d5db; color:#374151; box-shadow:0 4px 20px rgba(0,0,0,.12); }
        html.pub-light .dm-float:hover { background:#fff; border-color:#10b981; color:#059669; }

        @keyframes bar-pulse { 0%,100%{transform:scaleY(1)} 50%{transform:scaleY(1.3)} }
        .logo-animated:hover .bar1 { animation: bar-pulse .6s ease-in-out infinite; }
        .logo-animated:hover .bar2 { animation: bar-pulse .6s ease-in-out .15s infinite; }
        .logo-animated:hover .bar3 { animation: bar-pulse .6s ease-in-out .3s infinite; }
        .bar1,.bar2,.bar3 { transform-origin: bottom; transition: transform .2s; }

        .grid-bg {
            background-image: linear-gradient(rgba(16,185,129,.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(16,185,129,.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        html.pub-light .grid-bg {
            background-image: linear-gradient(rgba(16,185,129,.06) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(16,185,129,.06) 1px, transparent 1px);
        }
        .blob { position:absolute; border-radius:50%; filter:blur(80px); pointer-events:none; }
        .glow-btn { box-shadow: 0 8px 32px rgba(16,185,129,.35); }
        #mobile-menu { display:none; }
        #mobile-menu.open { display:block; }
    </style>
</head>
<body class="font-sans antialiased">

{{-- ════ NAVBAR ════ --}}
<nav class="abt-nav fixed top-0 w-full z-50" style="backdrop-filter:blur(20px);">
    <div class="max-w-7xl mx-auto px-5 py-3.5 flex items-center justify-between gap-4">

        <a href="{{ route('home') }}" class="flex items-center gap-3 logo-animated flex-shrink-0">
            <div style="width:40px;height:40px;flex-shrink:0;">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="agl" x1="8" y1="3" x2="40" y2="46" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#34d399"/><stop offset="100%" stop-color="#047857"/>
                        </linearGradient>
                        <filter id="aglow"><feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#10b981" flood-opacity=".4"/></filter>
                    </defs>
                    <path d="M24 3L40 10.5V27C40 36.5 33 43 24 46C15 43 8 36.5 8 27V10.5Z" fill="url(#agl)" filter="url(#aglow)"/>
                    <path d="M24 7L37 13.5V27C37 35 31 40.5 24 43C17 40.5 11 35 11 27V13.5Z" fill="rgba(255,255,255,.06)"/>
                    <rect class="bar1" x="15" y="30" width="4.5" height="9" rx="1.5" fill="white" opacity=".95"/>
                    <rect class="bar2" x="21.5" y="24" width="4.5" height="15" rx="1.5" fill="white" opacity=".95"/>
                    <rect class="bar3" x="28" y="18" width="4.5" height="21" rx="1.5" fill="white" opacity=".95"/>
                    <polyline points="15,33 24.5,26 30.5,20" stroke="rgba(255,255,255,.6)" stroke-width="2" stroke-linecap="round" fill="none"/>
                </svg>
            </div>
            <div>
                <div class="logo-name font-black text-base tracking-tight leading-tight">ADAM<span style="color:#10b981;">44</span></div>
                <div class="font-bold uppercase hidden sm:block" style="font-size:.58rem;color:#10b981;letter-spacing:.12em;">Unity Manager Pro</div>
            </div>
        </a>

        <div class="hidden md:flex items-center gap-7">
            <a href="{{ route('home') }}"    class="abt-nav-link">Home</a>
            <a href="{{ route('about') }}"   class="abt-nav-link" style="color:#10b981;">About</a>
            <a href="{{ route('contact') }}" class="abt-nav-link">Contact</a>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('login') }}" class="sign-in-btn-border hidden sm:inline-flex items-center text-sm font-semibold px-4 py-2 rounded-xl transition-all whitespace-nowrap">ናብ ኣካውንት እቶ</a>
            <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center text-sm font-bold px-4 py-2.5 rounded-xl glow-btn whitespace-nowrap" style="background:#10b981;color:#fff;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">ብነጻ ጀምር</a>
            <button onclick="toggleMob()" id="ham-btn" class="md:hidden flex flex-col justify-center items-center w-10 h-10 rounded-xl gap-1.5 flex-shrink-0 transition-all" style="background:rgba(255,255,255,.06);" aria-label="Menu">
                <span class="ham-bar" id="hb1"></span>
                <span class="ham-bar" id="hb2"></span>
                <span class="ham-bar" id="hb3"></span>
            </button>
        </div>
    </div>

    <div id="mobile-menu" class="mob-menu-bg md:hidden">
        <div class="px-5 pt-3 pb-5 flex flex-col gap-1">
            <a href="{{ route('home') }}"    onclick="closeMob()" class="mob-link text-sm font-semibold py-3 px-4 rounded-xl transition-all">Home</a>
            <a href="{{ route('about') }}"   onclick="closeMob()" class="mob-link text-sm font-semibold py-3 px-4 rounded-xl transition-all" style="color:#10b981;">About</a>
            <a href="{{ route('contact') }}" onclick="closeMob()" class="mob-link text-sm font-semibold py-3 px-4 rounded-xl transition-all">Contact</a>
            <div class="mob-divider border-t mt-2 pt-3 flex flex-col gap-2">
                <a href="{{ route('login') }}"    class="sign-in-btn-border text-center text-sm font-semibold py-3 px-4 rounded-xl transition-all">ናብ ኣካውንት እቶ</a>
                <a href="{{ route('register') }}" class="text-center text-sm font-bold py-3 px-4 rounded-xl glow-btn" style="background:#10b981;color:#fff;">ብነጻ ጀምር &rarr;</a>
            </div>
        </div>
    </div>
</nav>

{{-- ════ HERO ════ --}}
<section class="relative pt-36 pb-24 px-6 grid-bg overflow-hidden">
    <div class="blob w-96 h-96 top-0 -left-32" style="background:rgba(16,185,129,.07);"></div>
    <div class="blob w-80 h-80 top-1/2 -right-20" style="background:rgba(139,92,246,.05);"></div>

    <div class="max-w-4xl mx-auto text-center relative">
        <span class="inline-flex items-center gap-2 rounded-full px-5 py-2 mb-8 abt-pill text-xs font-bold uppercase tracking-widest">
            <span class="w-2 h-2 rounded-full animate-pulse" style="background:#10b981;"></span>
            ዕላማ · ሚሽን · ታሪኽና
        </span>
        <h1 class="abt-h font-black leading-none mb-6" style="font-size:clamp(2.2rem,6vw,4.8rem);line-height:1.08;">
            ምትእስሳር <span style="color:#10b981;">ፋይናንሳዊ ብልሒ</span><br>
            ንኹሉ ትካላት<span style="color:#10b981;">።</span>
        </h1>
        <p class="abt-muted text-xl max-w-3xl mx-auto leading-relaxed">
            ADAM44 Unity Manager Pro፡ ንትካላት ቋንቋ ትግርኛ ተባሂሉ ዝተዳለወ፡ ብ AI ዝተደገፈ ትንተና፡ ምሕደራ ኣባላትን ልዑል ውሕስነትን ዘወሃህድ ናይ መወዳእታ ወለዶ ፋይናንሳዊ መድረኽ እዩ።
        </p>
    </div>
</section>

{{-- ════ STATS ════ --}}
<section class="py-16 px-6">
    <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-5">
        @foreach([
            ['val'=>'2024',  'label'=>'ዝተቐጠሰሉ ዓመት', 'en'=>'Founded'],
            ['val'=>'AI',    'label'=>'ናይ AI ጸብጻብ',   'en'=>'Tigrinya Reports'],
            ['val'=>'100%',  'label'=>'ዳታ ድሕንነት',    'en'=>'Encrypted Data'],
            ['val'=>'6+',    'label'=>'ናይ ፋይናንስ ሞዱላት','en'=>'Core Modules'],
        ] as $s)
        <div class="abt-stat-card rounded-2xl p-6 text-center">
            <div class="font-black mb-1" style="font-size:2.2rem;color:#10b981;">{{ $s['val'] }}</div>
            <div class="abt-h font-bold text-sm mb-1">{{ $s['label'] }}</div>
            <div class="abt-sub text-xs">{{ $s['en'] }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- ════ VISION & MISSION & STORY ════ --}}
<section class="abt-section-alt py-24 px-6">
    <div class="max-w-6xl mx-auto">

        <div class="text-center mb-16">
            <span class="abt-pill inline-block text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-full mb-4">ዕላማ & ሚሽን</span>
            <h2 class="abt-h font-black text-4xl md:text-5xl mb-4">ናይ <span style="color:#10b981;">ADAM44</span> ዕላማን ሚሽንን</h2>
            <p class="abt-muted text-lg max-w-2xl mx-auto">ዝኾነ ትካል — ብዘየገድስ ዓቐኑ ወይ ቋንቋኡ — ናይ ዓለም ደረጃ ናይ ፋይናንስ መሳርሒ ከምዘለዎ ንኣምን።</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">

            {{-- Vision --}}
            <div class="abt-card rounded-3xl p-10">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6" style="background:rgba(16,185,129,.1);">
                    <svg class="w-7 h-7" style="color:#10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <div class="abt-pill inline-block text-xs font-black uppercase tracking-widest px-3 py-1 rounded-full mb-4">ራእይ · Vision</div>
                <h3 class="abt-h text-2xl font-black mb-4">ፋይናንሳዊ ሓድነት ንኹሉ!</h3>
                <p class="abt-muted leading-relaxed text-base">
                    ራእይና ፋይናንሳዊ ንጹርነት መሰል ናይ ነፍሲ ወከፍ ትካል፡ ማሕበረሰብን ስድራቤትን ዝኾነላ ዓለም ምርኣይ እዩ። ADAM44 ዝተሃንጸሉ ቀንዲ ዕላማ፡ ንትካላት ቋንቋ ትግርኛ ዝለዓለ ደረጃ ዘለዎ ፋይናንሳዊ ብልሒ ንምቕራብ እዩ።
                </p>
            </div>

            {{-- Mission --}}
            <div class="abt-card rounded-3xl p-10">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-6" style="background:rgba(139,92,246,.1);">
                    <svg class="w-7 h-7" style="color:#a78bfa;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="inline-block text-xs font-black uppercase tracking-widest px-3 py-1 rounded-full mb-4" style="background:rgba(139,92,246,.1);border:1px solid rgba(139,92,246,.25);color:#a78bfa;">ሚሽን · Mission</div>
                <h3 class="abt-h text-2xl font-black mb-4">ብንጹርነት ዓቕሚ ምሃብ!</h3>
                <p class="abt-muted leading-relaxed text-base">
                    ብቛንቋኻ ዝዛረብ፡ ብ AI ዝተደገፈ ፋይናንሳዊ መድረኽ ምቕራብ። ጸብጻባት ብትግርኛ ብምድላው ንመሻርሕቲ ስራሕ ምሉእ ቁጽጽር ዳታ ምሃብ።
                </p>
            </div>
        </div>

        {{-- Story --}}
        <div class="abt-card rounded-3xl p-10 md:p-14">
            <div class="max-w-3xl">
                <div class="abt-pill inline-block text-xs font-black uppercase tracking-widest px-3 py-1 rounded-full mb-6">ታሪኽና · Our Story</div>
                <h3 class="abt-h text-3xl font-black mb-6">ካብ ሓቀኛ ጸገም ዝተበገሰ</h3>
                <p class="abt-muted text-lg leading-relaxed">
                    ADAM44 ዝተፈጥረሉ ምኽንያት ዓቢ ሃጓፍ ስለ ዝተራእየ እዩ። ትካላት ቋንቋ ትግርኛ (ቤተ-ክርስትያን፡ ማሕበራት፡ ንኣሽቱ ንግድታት) ብቛንቋኦም ዝሰርሕ ፋይናንሳዊ መድረኽ ኣይነበሮምን። ADAM44 ነዚ ሃጓፍ እዚ ንምምላእ ዝተሃንጸ ፕሮፌሽናል ሶፍትዌር እዩ።
                </p>
            </div>
        </div>

    </div>
</section>

{{-- ════ CTA ════ --}}
<section class="py-20 px-6 text-center">
    <div class="max-w-2xl mx-auto">
        <h2 class="abt-h font-black text-3xl md:text-4xl mb-4">ሕጂ ብነጻ ጀምር<span style="color:#10b981;">!</span></h2>
        <p class="abt-muted text-lg mb-8">ናይ ፋይናንስ ምሕደራ ናብ ዝቐለለን ዝፍለጠን ደረጃ ምምጻእ ዕላማና እዩ።</p>
        <a href="{{ route('register') }}" class="inline-block font-extrabold px-12 py-4 rounded-2xl text-base transition-all glow-btn"
           style="background:#10b981;color:#fff;"
           onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
            ብነጻ ጀምር &rarr;
        </a>
    </div>
</section>

{{-- ════ FOOTER ════ --}}
<footer class="abt-footer py-6 px-6">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="abt-link text-sm font-semibold">&copy; {{ now()->year }} ADAM44 · Unity Manager Pro</p>
        <div class="flex gap-6">
            <a href="{{ route('home') }}"    class="abt-link text-sm">Home</a>
            <a href="{{ route('about') }}"   class="abt-link text-sm" style="color:#10b981;">About</a>
            <a href="{{ route('contact') }}" class="abt-link text-sm">Contact</a>
        </div>
    </div>
</footer>

<script>
    function toggleMob() {
        var m = document.getElementById('mobile-menu');
        var open = m.classList.toggle('open');
        document.getElementById('hb1').style.transform = open ? 'translateY(7px) rotate(45deg)' : '';
        document.getElementById('hb2').style.opacity  = open ? '0' : '1';
        document.getElementById('hb3').style.transform = open ? 'translateY(-7px) rotate(-45deg)' : '';
    }
    function closeMob() {
        document.getElementById('mobile-menu').classList.remove('open');
        document.getElementById('hb1').style.transform = '';
        document.getElementById('hb2').style.opacity   = '1';
        document.getElementById('hb3').style.transform = '';
    }
    function togglePubDark() {
        var isLight = document.getElementById('html-root').classList.toggle('pub-light');
        localStorage.setItem('adam44-dark', isLight ? '0' : '1');
        updatePubIcons(isLight);
    }
    function updatePubIcons(isLight) {
        var sun   = document.getElementById('pub-sun');
        var moon  = document.getElementById('pub-moon');
        var label = document.getElementById('pub-dm-label');
        if (sun)  sun.classList.toggle('hidden',  isLight);
        if (moon) moon.classList.toggle('hidden', !isLight);
        if (label) label.textContent = isLight ? 'Dark' : 'Light';
    }
    (function(){
        var isLight = document.getElementById('html-root').classList.contains('pub-light');
        updatePubIcons(isLight);
    })();
</script>

<button onclick="togglePubDark()" id="pub-dm-btn" class="dm-float" title="Toggle dark/light mode">
    <svg id="pub-sun" class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
    <svg id="pub-moon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
    <span id="pub-dm-label">Light</span>
</button>
<script>if('serviceWorker'in navigator){window.addEventListener('load',()=>navigator.serviceWorker.register('/sw.js'));}</script>
</body>
</html>
