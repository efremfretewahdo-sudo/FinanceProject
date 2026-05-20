<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white mb-1">ይቐደም ዝሓለፈ ቃልሕፁፅ</h2>
        <p class="text-slate-400 text-sm">Forgot your password? No problem.</p>
    </div>

    <p class="text-slate-400 text-sm mb-6 leading-relaxed">
        ናይ ኢሜይል ኣድራሻኻ ጸሓፍ — ናይ ቃልሕፁፅ ምምሳሕ ሊንክ ናብ ኢሜይልካ ክልእኸልካ ኢና።
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
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">ኢሜይል · Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('email') border-rose-500 @enderror">
            @error('email')
            <p class="mt-1.5 text-rose-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-emerald-500 hover:bg-emerald-400 text-white font-semibold text-sm py-3 rounded-xl transition-all duration-150 shadow-lg shadow-emerald-500/25 mt-2">
            ናይ ምምሳሕ ሊንክ ስደድ · Email Reset Link
        </button>
    </form>

    <p class="text-center text-sm text-slate-500 mt-6">
        ቃልሕፁፁ ዘክርካዮ?
        <a href="{{ route('login') }}" class="text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">ናብ ኣካውንት እቶ →</a>
    </p>
</x-guest-layout>
