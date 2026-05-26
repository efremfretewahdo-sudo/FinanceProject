<x-guest-layout>

    {{--
        OTP Verification Page
        ─────────────────────
        Alpine.js drives all state. The component talks directly to
        /api/v1/auth/otp/send and /api/v1/auth/otp/verify via fetch().
        No page reload is required for any step.

        Flow:
          Phase "request" → user confirms / enters email → hits "Send Code"
          Phase "verify"  → 6 individual digit boxes; auto-submits on fill
          Phase "success" → green confirmation, then redirect

        The ?email= and ?redirect= query params are consumed server-side
        and injected safely into the Alpine component via @json().
    --}}

    <div x-data="otpForm()" x-init="init()">

        {{-- ── PHASE 1: email entry / confirmation ─────────────────────── --}}
        <div x-show="phase === 'request'" x-transition.opacity>

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-white mb-1">Verify Your Identity</h2>
                <p class="text-slate-400 text-sm">Enter your email and we'll send a 6-digit code</p>
            </div>

            <div x-show="errorMsg" x-transition
                 class="mb-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-xl text-sm"
                 x-text="errorMsg"></div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Email address</label>
                    <input type="email" x-model="email"
                           @keydown.enter="sendCode()"
                           placeholder="you@example.com"
                           autocomplete="email"
                           class="w-full bg-slate-800 border border-slate-700 text-white placeholder-slate-500 text-sm rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all">
                </div>

                <button type="button"
                        @click="sendCode()"
                        :disabled="loading || !email.trim()"
                        class="w-full bg-emerald-500 hover:bg-emerald-400 disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold text-sm py-3 rounded-xl transition-all duration-150 shadow-lg shadow-emerald-500/25">
                    <span x-show="!loading">Send Verification Code</span>
                    <span x-show="loading" class="inline-flex items-center justify-center gap-2">
                        <svg class="animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        Sending…
                    </span>
                </button>
            </div>

        </div>

        {{-- ── PHASE 2: 6-digit OTP entry ──────────────────────────────── --}}
        <div x-show="phase === 'verify'" x-transition.opacity style="display:none">

            <div class="mb-6">
                <h2 class="text-2xl font-bold text-white mb-1">Enter Verification Code</h2>
                <p class="text-slate-400 text-sm">
                    6-digit code sent to
                    <span class="text-emerald-400 font-semibold" x-text="email"></span>
                </p>
            </div>

            {{-- Error banner --}}
            <div x-show="errorMsg" x-transition
                 class="mb-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-xl text-sm"
                 x-text="errorMsg"></div>

            {{-- 6 individual digit boxes --}}
            {{-- Paste anywhere on the page is caught by @paste.window --}}
            <div class="flex gap-2 sm:gap-3 justify-center my-8"
                 @paste.window="handlePaste($event)">
                <template x-for="(_, index) in [0,1,2,3,4,5]" :key="index">
                    <input
                        type="text"
                        inputmode="numeric"
                        pattern="[0-9]*"
                        maxlength="1"
                        autocomplete="one-time-code"
                        data-digit
                        :value="digits[index]"
                        @input="handleInput(index, $event)"
                        @keydown="handleKeydown(index, $event)"
                        :class="digits[index] !== ''
                            ? 'border-emerald-500 text-emerald-400'
                            : 'border-slate-700 text-white focus:border-emerald-500'"
                        class="w-11 h-14 sm:w-12 sm:h-16 text-center text-2xl font-bold bg-slate-800 border-2 rounded-xl transition-all caret-transparent select-none focus:outline-none">
                </template>
            </div>

            {{-- Verifying spinner (shown while waiting for API) --}}
            <div x-show="loading" x-transition
                 class="flex items-center justify-center gap-2 text-slate-400 text-sm mb-4">
                <svg class="animate-spin w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
                Verifying…
            </div>

            {{-- Manual submit (fallback if auto-submit doesn't fire) --}}
            <button type="button"
                    @click="submitOtp()"
                    :disabled="!isComplete || loading"
                    class="w-full bg-emerald-500 hover:bg-emerald-400 disabled:opacity-40 disabled:cursor-not-allowed text-white font-semibold text-sm py-3 rounded-xl transition-all duration-150 shadow-lg shadow-emerald-500/25">
                Verify Code
            </button>

            {{-- Resend / cooldown --}}
            <div class="text-center mt-6 text-sm text-slate-500">
                Didn't receive a code?

                <span x-show="cooldown > 0">
                    Resend in
                    <span class="text-emerald-400 font-semibold tabular-nums" x-text="cooldown + 's'"></span>
                </span>

                <button x-show="cooldown === 0" x-transition
                        type="button"
                        @click="sendCode()"
                        :disabled="loading"
                        class="ml-1 text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">
                    Resend code
                </button>
            </div>

            {{-- Wrong email link --}}
            <div class="text-center mt-3">
                <button type="button"
                        @click="phase = 'request'; errorMsg = ''; digits = ['','','','','','']"
                        class="text-xs text-slate-600 hover:text-slate-400 transition-colors">
                    Wrong email address? Change it
                </button>
            </div>

        </div>

        {{-- ── PHASE 3: success confirmation ───────────────────────────── --}}
        <div x-show="phase === 'success'" x-transition.opacity style="display:none"
             class="text-center py-10">

            <div class="w-20 h-20 bg-emerald-500/10 border border-emerald-500/20 rounded-full
                        flex items-center justify-center mx-auto mb-5">
                <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-white mb-2">Identity Verified!</h3>
            <p class="text-slate-400 text-sm">Redirecting you now…</p>

            {{-- Progress bar --}}
            <div class="mt-6 w-full bg-slate-800 rounded-full h-1 overflow-hidden">
                <div class="h-1 bg-emerald-500 rounded-full animate-[progress_1.8s_linear_forwards]"></div>
            </div>

        </div>

    </div>

    {{-- Back to login --}}
    <p class="text-center text-sm text-slate-500 mt-6">
        <a href="{{ route('login') }}"
           class="text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">
            ← Back to sign in
        </a>
    </p>

    {{--
        Alpine component — defined in a plain script tag so it is available
        in global scope before Alpine initialises via app.js.
    --}}
    <script>
    function otpForm() {
        return {
            phase:    'request',
            email:    new URLSearchParams(window.location.search).get('email') || @json($email ?? ''),
            digits:   ['', '', '', '', '', ''],
            loading:  false,
            errorMsg: '',
            cooldown: 0,
            _timer:   null,

            // ── computed ─────────────────────────────────────────────────

            get isComplete() {
                return this.digits.every(d => d !== '');
            },

            get otpCode() {
                return this.digits.join('');
            },

            // ── lifecycle ─────────────────────────────────────────────────

            init() {
                // If an email is already known (query param or server-side session),
                // skip straight to the send step.
                if (this.email.trim()) {
                    this.sendCode();
                }
            },

            // ── API calls ─────────────────────────────────────────────────

            async sendCode() {
                if (!this.email.trim()) return;
                this.errorMsg = '';
                this.loading  = true;
                try {
                    const res  = await fetch('/api/v1/auth/otp/send', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body:    JSON.stringify({ email: this.email.trim() }),
                    });
                    const json = await res.json();
                    if (res.ok) {
                        this.phase = 'verify';
                        this.startCooldown(60);
                        this.$nextTick(() => {
                            this.$el.querySelectorAll('[data-digit]')[0]?.focus();
                        });
                    } else {
                        this.errorMsg = json.message || 'Failed to send code. Please try again.';
                    }
                } catch {
                    this.errorMsg = 'Network error. Please check your connection and try again.';
                } finally {
                    this.loading = false;
                }
            },

            async submitOtp() {
                if (!this.isComplete || this.loading) return;
                this.errorMsg = '';
                this.loading  = true;
                try {
                    const res  = await fetch('/api/v1/auth/otp/verify', {
                        method:  'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body:    JSON.stringify({ email: this.email.trim(), otp: this.otpCode }),
                    });
                    const json = await res.json();
                    if (res.ok) {
                        this.phase = 'success';
                        // Only follow relative redirect URLs to prevent open-redirect attacks.
                        const raw  = new URLSearchParams(window.location.search).get('redirect') || '';
                        const dest = (raw && raw.startsWith('/')) ? raw : '{{ route('login') }}';
                        setTimeout(() => { window.location.href = dest; }, 1800);
                    } else {
                        this.errorMsg = json.message || 'Invalid code. Please try again.';
                        this.clearDigits();
                    }
                } catch {
                    this.errorMsg = 'Network error. Please check your connection and try again.';
                    this.clearDigits();
                } finally {
                    this.loading = false;
                }
            },

            // ── keyboard / input handlers ─────────────────────────────────

            handleInput(index, event) {
                // Strip non-digits; keep only the last character typed.
                const val = event.target.value.replace(/\D/g, '').slice(-1);
                this.digits[index] = val;
                if (val) {
                    const boxes = this.$el.querySelectorAll('[data-digit]');
                    if (index < 5) boxes[index + 1]?.focus();
                    if (this.isComplete) this.$nextTick(() => this.submitOtp());
                }
            },

            handleKeydown(index, event) {
                const boxes = () => this.$el.querySelectorAll('[data-digit]');
                if (event.key === 'Backspace') {
                    if (this.digits[index]) {
                        this.digits[index] = '';
                    } else if (index > 0) {
                        this.digits[index - 1] = '';
                        boxes()[index - 1]?.focus();
                    }
                } else if (event.key === 'ArrowLeft'  && index > 0) {
                    boxes()[index - 1]?.focus();
                } else if (event.key === 'ArrowRight' && index < 5) {
                    boxes()[index + 1]?.focus();
                }
            },

            handlePaste(event) {
                // Only intercept pastes when we're on the verify phase.
                if (this.phase !== 'verify') return;
                const raw   = (event.clipboardData || window.clipboardData).getData('text');
                const clean = raw.replace(/\D/g, '').slice(0, 6);
                if (!clean) return;
                event.preventDefault();
                [...clean].forEach((char, i) => { if (i < 6) this.digits[i] = char; });
                const boxes = this.$el.querySelectorAll('[data-digit]');
                boxes[Math.min(clean.length - 1, 5)]?.focus();
                if (clean.length >= 6) this.$nextTick(() => this.submitOtp());
            },

            // ── helpers ───────────────────────────────────────────────────

            clearDigits() {
                this.digits = ['', '', '', '', '', ''];
                this.$nextTick(() => this.$el.querySelectorAll('[data-digit]')[0]?.focus());
            },

            startCooldown(secs) {
                clearInterval(this._timer);
                this.cooldown = secs;
                this._timer   = setInterval(() => {
                    if (this.cooldown > 0) this.cooldown--;
                    else clearInterval(this._timer);
                }, 1000);
            },
        };
    }
    </script>

    {{-- Progress-bar keyframe (appended inline so it works without a custom Tailwind plugin) --}}
    <style>
    @keyframes progress {
        from { width: 0%; }
        to   { width: 100%; }
    }
    </style>

</x-guest-layout>
