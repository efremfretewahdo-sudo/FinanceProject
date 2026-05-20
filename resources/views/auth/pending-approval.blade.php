<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ session('plan_expired') ? 'Plan Expired' : 'Awaiting Approval' }} — ADAM44</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-auto px-6 text-center">
        <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-10">

            @if(session('plan_expired'))
            {{-- Plan Expired State --}}
            <div class="w-16 h-16 bg-rose-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="inline-flex items-center gap-2 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-5">
                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full inline-block"></span>
                Plan Expired
            </div>
            <h1 class="text-2xl font-bold text-slate-800 mb-3">Your Plan Has Expired</h1>
            <p class="text-slate-500 text-sm leading-relaxed mb-2">
                Hello, <span class="font-semibold text-slate-700">{{ Auth::user()->name }}</span>.
            </p>
            <p class="text-slate-500 text-sm leading-relaxed mb-6">
                Your ADAM44 subscription has expired. Contact the administrator to renew your plan and restore access.
            </p>
            @else
            {{-- Pending Approval State --}}
            <div class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="inline-flex items-center gap-2 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold px-3 py-1.5 rounded-full mb-5">
                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse inline-block"></span>
                Pending Approval
            </div>
            <h1 class="text-2xl font-bold text-slate-800 mb-3">Account Under Review</h1>
            <p class="text-slate-500 text-sm leading-relaxed mb-2">
                Welcome, <span class="font-semibold text-slate-700">{{ Auth::user()->name }}</span>!
                Your account has been created successfully.
            </p>
            <p class="text-slate-500 text-sm leading-relaxed mb-6">
                The ADAM44 administrator will review and approve your account shortly. You'll have full access once approved.
            </p>
            @endif

            {{-- Contact Admin Card --}}
            <div class="bg-slate-900 rounded-2xl p-5 mb-6 text-left">
                <p class="text-xs text-slate-400 uppercase tracking-widest font-semibold mb-3">Contact Administrator</p>
                <a href="tel:+972546694117" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-emerald-500/15 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-bold text-base group-hover:text-emerald-400 transition-colors">+972 54 669 4117</p>
                        <p class="text-slate-400 text-xs">Tap to call · Efrem (Admin)</p>
                    </div>
                </a>
            </div>

            @if(!session('plan_expired'))
            {{-- Progress Steps --}}
            <div class="bg-slate-50 rounded-2xl p-4 mb-6 text-left space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-sm text-slate-600">Account created</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-sm text-slate-600 font-medium">Awaiting admin approval</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <p class="text-sm text-slate-400">Access dashboard</p>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold py-3 rounded-xl transition-all text-sm">
                    Sign Out
                </button>
            </form>

            <p class="text-xs text-slate-400 mt-4">
                Registered as <span class="font-medium">{{ Auth::user()->email }}</span>
            </p>
        </div>

        <div class="mt-6 flex items-center justify-center gap-2">
            <div class="w-8 h-8 bg-slate-900 rounded-xl flex items-center justify-center">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-slate-500 text-sm font-semibold">ADAM44 · Unity Manager Pro</span>
        </div>
    </div>
</body>
</html>
