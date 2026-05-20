<!DOCTYPE html>
<html lang="ti" id="html-root">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ርክብ — ADAM44 Unity Manager Pro</title>
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
        body { background: var(--navy); color: #f8fafc; font-family: 'Figtree', sans-serif; transition: background .25s, color .25s; overflow-x: hidden; }
        .cnt-nav { background: rgba(2,6,23,.85); border-bottom: 1px solid rgba(255,255,255,.08); }
        .cnt-nav-link { color: #64748b; font-size:.88rem; font-weight:600; transition:color .15s; }
        .cnt-nav-link:hover { color: #f8fafc; }
        .cnt-card { background: #0d1526; border: 1px solid rgba(255,255,255,.06); }
        .cnt-footer { background: var(--navy); border-top: 1px solid rgba(255,255,255,.06); }
        .cnt-link { color: #64748b; transition: color .15s; }
        .cnt-link:hover { color: #94a3b8; }
        .cnt-label { color: #cbd5e1; }
        .cnt-input { background: #1e293b; border: 1px solid #334155; color: #f8fafc; }
        .cnt-input::placeholder { color: #475569; }
        .cnt-input:focus { border-color: #10b981; outline: none; box-shadow: 0 0 0 3px rgba(16,185,129,.15); }
        .cnt-heading { color: #f8fafc; }
        .cnt-muted { color: #94a3b8; }
        .cnt-success-heading { color: #fff; }
        .cnt-success-sub { color: #94a3b8; }
        .cnt-success-ti { color: #64748b; }
        .cnt-err { background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.3); color: #f87171; }
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
        html.pub-light .cnt-nav { background: rgba(255,255,255,.95); border-bottom: 1px solid #e5e7eb; backdrop-filter: blur(20px); }
        html.pub-light .cnt-nav-link { color: #6b7280; }
        html.pub-light .cnt-nav-link:hover { color: #111827; }
        html.pub-light .cnt-card { background: #fff; border: 1px solid #e5e7eb; }
        html.pub-light .cnt-footer { background: #f8fafc; border-top: 1px solid #e5e7eb; }
        html.pub-light .cnt-link { color: #6b7280; }
        html.pub-light .cnt-link:hover { color: #374151; }
        html.pub-light .cnt-label { color: #374151; }
        html.pub-light .cnt-input { background: #f9fafb; border: 1px solid #d1d5db; color: #111827; }
        html.pub-light .cnt-input::placeholder { color: #9ca3af; }
        html.pub-light .cnt-input:focus { border-color: #10b981; box-shadow: 0 0 0 3px rgba(16,185,129,.1); }
        html.pub-light .cnt-heading { color: #111827; }
        html.pub-light .cnt-muted { color: #6b7280; }
        html.pub-light .cnt-success-heading { color: #111827; }
        html.pub-light .cnt-success-sub { color: #6b7280; }
        html.pub-light .cnt-success-ti { color: #9ca3af; }
        html.pub-light .cnt-err { background: #fff5f5; border: 1px solid #fecaca; color: #dc2626; }
        html.pub-light .logo-name { color: #111827; }
        html.pub-light .sign-in-btn-border { border: 1.5px solid #d1d5db; color: #374151; }
        html.pub-light .sign-in-btn-border:hover { border-color: #10b981; color: #059669; }
        html.pub-light .mob-menu-bg { background: rgba(255,255,255,.99); border-top: 1px solid #e5e7eb; }
        html.pub-light .mob-link { color: #6b7280; }
        html.pub-light .mob-link:hover { color: #111827; background: #f9fafb; }
        html.pub-light .mob-divider { border-color: #e5e7eb; }
        html.pub-light .ham-bar { background: #374151; }

        /* Floating dark mode toggle */
        .dm-float {
            position:fixed; bottom:24px; right:24px; z-index:999;
            display:flex; align-items:center; gap:6px;
            padding:8px 16px; border-radius:40px;
            border:1px solid rgba(255,255,255,.15);
            background:rgba(2,6,23,.8); backdrop-filter:blur(12px);
            color:#94a3b8; font-size:.74rem; font-weight:700;
            cursor:pointer; transition:all .2s;
            box-shadow:0 4px 20px rgba(0,0,0,.4);
        }
        .dm-float:hover { background:rgba(2,6,23,.95); color:#f8fafc; border-color:rgba(16,185,129,.4); }
        html.pub-light .dm-float { background:rgba(255,255,255,.9); border-color:#d1d5db; color:#374151; box-shadow:0 4px 20px rgba(0,0,0,.12); }
        html.pub-light .dm-float:hover { background:#fff; border-color:#10b981; color:#059669; }

        @keyframes bar-pulse {
            0%,100% { transform: scaleY(1); }
            50% { transform: scaleY(1.3); }
        }
        .logo-animated:hover .bar1 { animation: bar-pulse .6s ease-in-out infinite; }
        .logo-animated:hover .bar2 { animation: bar-pulse .6s ease-in-out .15s infinite; }
        .logo-animated:hover .bar3 { animation: bar-pulse .6s ease-in-out .3s infinite; }
        .bar1,.bar2,.bar3 { transform-origin: bottom; transition: transform .2s; }

        .grid-bg {
            background-image: linear-gradient(rgba(16,185,129,.04) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(16,185,129,.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .glow-btn { box-shadow: 0 8px 32px rgba(16,185,129,.35); }

        #mobile-menu { display:none; }
        #mobile-menu.open { display:block; }
    </style>
</head>
<body class="font-sans antialiased">

{{-- ════ NAVBAR ════ --}}
<nav class="cnt-nav fixed top-0 w-full z-50" style="backdrop-filter:blur(20px);">
    <div class="max-w-7xl mx-auto px-5 py-3.5 flex items-center justify-between gap-4">

        <a href="{{ route('home') }}" class="flex items-center gap-3 logo-animated flex-shrink-0">
            <div style="width:40px;height:40px;flex-shrink:0;">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <linearGradient id="cgl" x1="8" y1="3" x2="40" y2="46" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#34d399"/><stop offset="100%" stop-color="#047857"/>
                        </linearGradient>
                        <filter id="cglow"><feDropShadow dx="0" dy="2" stdDeviation="3" flood-color="#10b981" flood-opacity=".4"/></filter>
                    </defs>
                    <path d="M24 3L40 10.5V27C40 36.5 33 43 24 46C15 43 8 36.5 8 27V10.5Z" fill="url(#cgl)" filter="url(#cglow)"/>
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
            <a href="{{ route('home') }}"    class="cnt-nav-link">Home</a>
            <a href="{{ route('about') }}"   class="cnt-nav-link">About</a>
            <a href="{{ route('contact') }}" class="cnt-nav-link" style="color:#10b981;">Contact</a>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('login') }}"    class="sign-in-btn-border hidden sm:inline-flex items-center text-sm font-semibold px-4 py-2 rounded-xl transition-all whitespace-nowrap">ናብ ኣካውንት እቶ</a>
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
            <a href="{{ route('about') }}"   onclick="closeMob()" class="mob-link text-sm font-semibold py-3 px-4 rounded-xl transition-all">About</a>
            <a href="{{ route('contact') }}" onclick="closeMob()" class="mob-link text-sm font-semibold py-3 px-4 rounded-xl transition-all" style="color:#10b981;">Contact</a>
            <div class="mob-divider border-t mt-2 pt-3 flex flex-col gap-2">
                <a href="{{ route('login') }}"    class="sign-in-btn-border text-center text-sm font-semibold py-3 px-4 rounded-xl transition-all">ናብ ኣካውንት እቶ</a>
                <a href="{{ route('register') }}" class="text-center text-sm font-bold py-3 px-4 rounded-xl glow-btn" style="background:#10b981;color:#fff;">ብነጻ ጀምር &rarr;</a>
            </div>
        </div>
    </div>
</nav>

{{-- ════ CONTACT FORM ════ --}}
<div class="grid-bg min-h-screen pt-28 pb-28 px-6">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 rounded-full px-5 py-2 mb-6 text-xs font-bold uppercase tracking-widest" style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25);color:#10b981;">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background:#10b981;"></span>
                ርክብ · Get in Touch
            </span>
            <h1 class="cnt-heading text-4xl md:text-5xl font-black mt-2 mb-4">
                ምስና <span style="color:#10b981;">ተራኸቡ</span>
            </h1>
            <p class="cnt-muted text-lg">ሕቶ ኣለካ? ክንሰምዖ ንፈቱ።</p>
            <p class="cnt-muted text-sm mt-1">Have a question? We'd love to hear from you.</p>
        </div>

        <div class="cnt-card rounded-3xl p-8 md:p-10">

            @if(session('contact_success'))
            <div class="text-center py-14">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-6" style="background:rgba(16,185,129,.1);">
                    <svg class="w-8 h-8" style="color:#10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="cnt-success-heading text-2xl font-bold mb-3">መልእኽቲ ተሰዲዱ!</h3>
                <p class="cnt-success-sub mb-1">ኣመሰግነካ — ቡድናና ኣብ ቀረባ ግዜ ክምልሰልካ እዩ።</p>
                <p class="cnt-success-ti text-sm">Message Sent! Thank you — we'll get back to you shortly.</p>
                <a href="{{ route('contact') }}" class="mt-8 inline-block text-sm font-semibold transition-colors" style="color:#10b981;">ካልእ መልእኽቲ ስደድ →</a>
            </div>
            @else

            @if($errors->any())
            <div class="cnt-err mb-5 rounded-xl px-4 py-3">
                <ul class="text-sm space-y-1">
                    @foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="cnt-label block text-sm font-semibold mb-2">ምሉእ ስም · Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               placeholder="ምሉእ ስምካ ጸሓፍ"
                               class="cnt-input w-full text-sm rounded-xl px-4 py-3 transition-all">
                    </div>
                    <div>
                        <label class="cnt-label block text-sm font-semibold mb-2">ኢሜይል · Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               placeholder="you@example.com"
                               class="cnt-input w-full text-sm rounded-xl px-4 py-3 transition-all">
                    </div>
                </div>
                <div>
                    <label class="cnt-label block text-sm font-semibold mb-2">ርእሲ · Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}"
                           placeholder="እንታይ ዛዕባ እዩ?"
                           class="cnt-input w-full text-sm rounded-xl px-4 py-3 transition-all">
                </div>
                <div>
                    <label class="cnt-label block text-sm font-semibold mb-2">መልእኽቲ · Message *</label>
                    <textarea name="message" required rows="5"
                              placeholder="መልእኽቲኻ ጸሓፍ..."
                              class="cnt-input w-full text-sm rounded-xl px-4 py-3 transition-all resize-none">{{ old('message') }}</textarea>
                </div>
                <button type="submit"
                        class="w-full font-bold py-3.5 rounded-xl transition-all glow-btn text-white text-base"
                        style="background:#10b981;"
                        onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                    መልእኽቲ ስደድ · Send Message
                </button>
            </form>
            @endif
        </div>

        {{-- Contact Info Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8">
            @foreach([
                ['icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                 'label'=>'ኢሜይል · Email','val'=>'info@adam44.com','color'=>'#10b981'],
                ['icon'=>'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z',
                 'label'=>'ቴለፎን · Phone','val'=>'+291 7xx xxx','color'=>'#a78bfa'],
                ['icon'=>'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
                 'label'=>'ቦታ · Location','val'=>'Tigrinya Community','color'=>'#38bdf8'],
            ] as $c)
            <div class="cnt-card rounded-2xl p-5 text-center">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center mx-auto mb-3" style="background:rgba(16,185,129,.08);">
                    <svg class="w-5 h-5" style="color:{{ $c['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $c['icon'] }}"/></svg>
                </div>
                <p class="cnt-muted text-xs font-semibold uppercase tracking-wide mb-1">{{ $c['label'] }}</p>
                <p class="cnt-heading text-sm font-bold">{{ $c['val'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ════ FOOTER ════ --}}
<footer class="cnt-footer py-6 px-6">
    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
        <p class="cnt-link text-sm font-semibold">&copy; {{ now()->year }} ADAM44 · Unity Manager Pro</p>
        <div class="flex gap-6">
            <a href="{{ route('home') }}"    class="cnt-link text-sm">Home</a>
            <a href="{{ route('about') }}"   class="cnt-link text-sm">About</a>
            <a href="{{ route('contact') }}" class="cnt-link text-sm" style="color:#10b981;">Contact</a>
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
