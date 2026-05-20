<x-app-layout>
    <div class="py-8">
        <div class="mb-8">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-widest mb-1">Preferences</p>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Settings</h1>
            <p class="text-sm text-slate-500 mt-1">Configure your account and application preferences.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Settings menu --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-3 h-fit">
                @foreach([
                    ['label' => 'Account', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'active' => true],
                    ['label' => 'Notifications', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'active' => false],
                    ['label' => 'Security', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'active' => false],
                    ['label' => 'Billing', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'active' => false],
                    ['label' => 'Appearance', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'active' => false],
                ] as $item)
                <button class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-left transition-colors
                    {{ $item['active'] ? 'bg-emerald-50 text-emerald-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-800' }}">
                    <svg class="w-5 h-5 flex-shrink-0 {{ $item['active'] ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </button>
                @endforeach
            </div>

            {{-- Account Settings Panel --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="font-bold text-slate-800 mb-5">Account Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Full Name</label>
                            <input type="text" value="{{ Auth::user()->name }}" disabled class="w-full text-sm border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-600 cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-600 mb-1.5">Email Address</label>
                            <input type="email" value="{{ Auth::user()->email }}" disabled class="w-full text-sm border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-600 cursor-not-allowed">
                        </div>
                    </div>
                    <div class="mt-5 pt-5 border-t border-slate-100 flex items-center justify-between">
                        <p class="text-xs text-slate-400">To edit your profile, go to the Profile page.</p>
                        <a href="{{ route('profile.edit') }}" class="text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">Edit Profile →</a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h2 class="font-bold text-slate-800 mb-1">Application Preferences</h2>
                    <p class="text-sm text-slate-400 mb-5">Customize your experience.</p>
                    <div class="space-y-4">
                        @foreach([
                            ['label' => 'Email Notifications', 'desc' => 'Receive activity summaries by email'],
                            ['label' => 'Monthly Reports', 'desc' => 'Auto-generate monthly financial reports'],
                            ['label' => 'Dark Mode Sidebar', 'desc' => 'Keep the dark sidebar (current style)', 'checked' => true],
                        ] as $pref)
                        <div class="flex items-center justify-between py-2">
                            <div>
                                <p class="text-sm font-medium text-slate-700">{{ $pref['label'] }}</p>
                                <p class="text-xs text-slate-400">{{ $pref['desc'] }}</p>
                            </div>
                            <button class="relative w-11 h-6 rounded-full transition-colors {{ isset($pref['checked']) ? 'bg-emerald-500' : 'bg-slate-200' }}">
                                <span class="absolute top-0.5 {{ isset($pref['checked']) ? 'left-5' : 'left-0.5' }} w-5 h-5 bg-white rounded-full shadow transition-all"></span>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6">
                    <h2 class="font-bold text-rose-700 mb-1">Danger Zone</h2>
                    <p class="text-sm text-rose-500 mb-4">These actions are irreversible. Proceed with caution.</p>
                    <button class="text-sm font-semibold text-rose-600 border border-rose-300 hover:bg-rose-100 px-4 py-2 rounded-lg transition-colors">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
