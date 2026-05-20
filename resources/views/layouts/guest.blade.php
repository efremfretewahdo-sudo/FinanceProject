<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FinanceTracker') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-950">
<div class="min-h-screen flex">

    {{-- Left brand panel (hidden on mobile) --}}
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 flex-col justify-between p-12 relative overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-500 rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-20 right-10 w-96 h-96 bg-indigo-500 rounded-full filter blur-3xl"></div>
        </div>

        {{-- Logo --}}
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 bg-emerald-500 rounded-xl flex items-center justify-center shadow-xl shadow-emerald-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <span class="text-white font-bold text-xl">FinanceTracker</span>
                    <span class="text-emerald-400 text-xs font-semibold ml-2 bg-emerald-400/10 px-2 py-0.5 rounded-full">Pro</span>
                </div>
            </div>
        </div>

        {{-- Headline --}}
        <div class="relative z-10">
            <h1 class="text-4xl font-bold text-white leading-tight mb-4">
                Your finances,<br>
                <span class="text-emerald-400">under control.</span>
            </h1>
            <p class="text-slate-400 text-lg leading-relaxed mb-10">
                Track income, manage expenses, and get AI-powered insights — all in one premium dashboard.
            </p>

            {{-- Feature list --}}
            <div class="space-y-4">
                @foreach([
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'text' => 'Real-time financial dashboards with Chart.js'],
                    ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'text' => 'AI insights in Tigrinya & English'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'text' => 'Member & payment management'],
                ] as $feature)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-500/10 border border-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature['icon'] }}"/>
                        </svg>
                    </div>
                    <span class="text-slate-300 text-sm">{{ $feature['text'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Footer quote --}}
        <div class="relative z-10">
            <p class="text-slate-600 text-xs">&copy; {{ now()->year }} FinanceTracker Pro. Built for professionals.</p>
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-3 justify-center mb-8">
                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-xl">FinanceTracker</span>
            </div>

            {{ $slot }}
        </div>
    </div>
</div>
</body>
</html>
