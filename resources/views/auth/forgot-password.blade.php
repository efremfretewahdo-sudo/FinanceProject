<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white mb-1">Forgot Your Password?</h2>
        <p class="text-slate-400 text-sm">No problem. Enter your email and we'll send a reset link.</p>
    </div>

    <p class="text-slate-400 text-sm mb-6 leading-relaxed">
        Enter your registered email address below and we will send you a secure password reset link.
    </p>

    {{-- Session Status --}}
    @if (session('status'))
    <div class="mb-5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl text-sm">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('email') border-rose-500 @enderror"
                   placeholder="you@example.com">
            @error('email')
            <p class="mt-1.5 text-rose-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-emerald-500 hover:bg-emerald-400 text-white font-semibold text-sm py-3 rounded-xl transition-all duration-150 shadow-lg shadow-emerald-500/25 mt-2">
            Email Reset Link
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        Remember your password?
        <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">Back to sign in →</a>
    </p>
</x-guest-layout>
