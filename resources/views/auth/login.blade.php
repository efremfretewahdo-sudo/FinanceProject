<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white mb-1">Welcome back</h2>
        <p class="text-slate-400 text-sm">Sign in to your ADAM44 account</p>
    </div>

    {{-- Session Status --}}
    @if (session('status'))
    <div class="mb-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl text-sm">
        {{ session('status') }}
    </div>
    @endif
    @if (session('error'))
    <div class="mb-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-xl text-sm">
        {{ session('error') }}
    </div>
    @endif

    {{-- Google Sign In --}}
    <a href="{{ route('auth.google') }}"
       class="flex items-center justify-center gap-3 w-full bg-white hover:bg-gray-50 text-slate-800 font-semibold text-sm py-3 px-4 rounded-xl transition-all duration-150 shadow-sm mb-6">
        <svg class="w-5 h-5" viewBox="0 0 24 24">
            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Sign in with Google
    </a>

    {{-- Divider --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="flex-1 h-px bg-slate-800"></div>
        <span class="text-slate-600 text-xs font-medium">or continue with email</span>
        <div class="flex-1 h-px bg-slate-800"></div>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-slate-300 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('email') border-rose-500 @enderror">
            @error('email')<p class="mt-1 text-rose-400 text-xs">{{ $message }}</p>@enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="text-sm font-medium text-slate-300">Password</label>
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-emerald-400 hover:text-emerald-300 transition-colors">Forgot password?</a>
                @endif
            </div>
            <input id="password" type="password" name="password" required
                   class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('password') border-rose-500 @enderror">
            @error('password')<p class="mt-1 text-rose-400 text-xs">{{ $message }}</p>@enderror
        </div>

        <div class="flex items-center gap-2">
            <input id="remember_me" type="checkbox" name="remember"
                   class="w-4 h-4 rounded border-slate-700 bg-slate-800 text-emerald-500 focus:ring-emerald-500">
            <label for="remember_me" class="text-sm text-slate-400">Remember me for 30 days</label>
        </div>

        <button type="submit"
                class="w-full bg-emerald-500 hover:bg-emerald-400 text-white font-semibold text-sm py-3 rounded-xl transition-all duration-150 shadow-lg shadow-emerald-500/25 mt-2">
            Sign in to Dashboard
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">Create one free</a>
    </p>
</x-guest-layout>
