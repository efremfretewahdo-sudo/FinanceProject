<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-white mb-1">ሓድሽ ቃልሕፁፅ ምምሳሕ</h2>
        <p class="text-slate-400 text-sm">Reset your password · Choose a strong new password</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">ኢሜይል · Email address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                   class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('email') border-rose-500 @enderror">
            @error('email')
            <p class="mt-1.5 text-rose-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">ሓድሽ ቃልሕፁፅ · New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('password') border-rose-500 @enderror">
            @error('password')
            <p class="mt-1.5 text-rose-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1.5">ቃልሕፁፁ ደጋጊምካ ጸሓፍ · Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all @error('password_confirmation') border-rose-500 @enderror">
            @error('password_confirmation')
            <p class="mt-1.5 text-rose-400 text-xs">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-emerald-500 hover:bg-emerald-400 text-white font-semibold text-sm py-3 rounded-xl transition-all duration-150 shadow-lg shadow-emerald-500/25 mt-2">
            ቃልሕፁፅ ምሳሕ · Reset Password
        </button>
    </form>
</x-guest-layout>
