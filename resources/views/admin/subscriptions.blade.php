<x-admin-layout>
<div x-data="{
    modal: false,
    userId: null,
    userName: '',
    userEmail: '',
    planDuration: '1_year',
    openModal(id, name, email) {
        this.userId = id;
        this.userName = name;
        this.userEmail = email;
        this.planDuration = '1_year';
        this.modal = true;
    },
    getRoute(id) {
        return '{{ url('/admin/users') }}/' + id + '/approve';
    }
}">

    {{-- ══════════════ ACTIVATE / APPROVE MODAL ══════════════ --}}
    <div x-show="modal" x-cloak
         style="position:fixed; inset:0; z-index:1000; display:flex; align-items:center; justify-content:center; padding:20px;"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- Backdrop --}}
        <div @click="modal=false"
             style="position:absolute; inset:0; background:rgba(0,0,0,.55); backdrop-filter:blur(4px);"></div>

        {{-- Modal Card --}}
        <div style="position:relative; background:#fff; border-radius:20px; padding:32px; width:100%; max-width:440px; box-shadow:0 25px 60px rgba(0,0,0,.2);"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            {{-- Header --}}
            <div style="display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:24px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:44px; height:44px; border-radius:14px; background:linear-gradient(135deg,#10b981,#059669); display:flex; align-items:center; justify-content:center; box-shadow:0 6px 16px rgba(16,185,129,.35);">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <p style="font-size:1.05rem; font-weight:800; color:#111827;">Approve Subscription</p>
                        <p style="font-size:.75rem; color:#9ca3af; margin-top:1px;">Set plan duration and confirm</p>
                    </div>
                </div>
                <button @click="modal=false"
                        style="width:32px; height:32px; border-radius:8px; border:1px solid #e5e7eb; background:#f9fafb; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#9ca3af; font-size:1.1rem; line-height:1;">
                    ✕
                </button>
            </div>

            {{-- User info --}}
            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:14px 16px; margin-bottom:22px; display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,#10b981,#059669); display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:800; color:#fff; flex-shrink:0;"
                     x-text="userName.slice(0,2).toUpperCase()"></div>
                <div>
                    <p style="font-weight:700; font-size:.88rem; color:#111827;" x-text="userName"></p>
                    <p style="font-size:.75rem; color:#6b7280;" x-text="userEmail"></p>
                </div>
                <span style="margin-left:auto; display:inline-flex; align-items:center; gap:4px; background:#d1fae5; color:#059669; font-size:.7rem; font-weight:700; padding:3px 9px; border-radius:20px;">
                    Subscription
                </span>
            </div>

            {{-- Plan Selection --}}
            <p style="font-size:.78rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.07em; margin-bottom:12px;">Select Plan Duration</p>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:22px;">
                @foreach([
                    ['val'=>'1_month',  'label'=>'1 Month',  'sub'=>'Trial',        'color'=>'#0284c7', 'bg'=>'#e0f2fe'],
                    ['val'=>'6_months', 'label'=>'6 Months', 'sub'=>'Semi-Annual',  'color'=>'#7c3aed', 'bg'=>'#ede9fe'],
                    ['val'=>'1_year',   'label'=>'1 Year',   'sub'=>'Annual',       'color'=>'#10b981', 'bg'=>'#d1fae5'],
                    ['val'=>'lifetime', 'label'=>'Lifetime', 'sub'=>'Unlimited',    'color'=>'#d97706', 'bg'=>'#fef3c7'],
                ] as $p)
                <label @click="planDuration='{{ $p['val'] }}'"
                       :style="planDuration==='{{ $p['val'] }}' ? 'border-color:{{ $p['color'] }}; background:{{ $p['bg'] }};' : ''"
                       style="border:2px solid #e5e7eb; border-radius:12px; padding:12px 14px; cursor:pointer; transition:all .15s; display:block;">
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
                        <div style="width:8px; height:8px; border-radius:50%; background:{{ $p['color'] }};"></div>
                        <span style="font-size:.84rem; font-weight:800; color:#111827;">{{ $p['label'] }}</span>
                    </div>
                    <p style="font-size:.72rem; color:#6b7280; padding-left:16px;">{{ $p['sub'] }}</p>
                </label>
                @endforeach
            </div>

            {{-- Plan expiry preview --}}
            <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:10px 14px; margin-bottom:22px; display:flex; align-items:center; gap:8px;">
                <svg class="w-4 h-4" style="color:#10b981; flex-shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p style="font-size:.8rem; color:#15803d; font-weight:600;">
                    Access expires:
                    <span x-text="planDuration==='lifetime' ? 'Never (Lifetime)' : planDuration==='1_month' ? '~30 days from today' : planDuration==='6_months' ? '~180 days from today' : '~365 days from today'"></span>
                </p>
            </div>

            {{-- Form --}}
            <form method="POST" :action="getRoute(userId)">
                @csrf
                <input type="hidden" name="plan_duration" :value="planDuration">
                <div style="display:flex; gap:10px;">
                    <button type="button" @click="modal=false"
                            style="flex:1; padding:12px; border-radius:12px; border:1px solid #e5e7eb; background:#fff; font-size:.875rem; font-weight:600; color:#374151; cursor:pointer; transition:background .15s;"
                            onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                        Cancel
                    </button>
                    <button type="submit"
                            style="flex:2; padding:12px; border-radius:12px; border:none; background:linear-gradient(135deg,#10b981,#059669); color:#fff; font-size:.875rem; font-weight:700; cursor:pointer; box-shadow:0 4px 16px rgba(16,185,129,.35); display:flex; align-items:center; justify-content:center; gap:8px; transition:opacity .15s;"
                            onmouseover="this.style.opacity='.9'" onmouseout="this.style.opacity='1'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Approve
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ SUBSCRIPTION STATS ══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-7">
        @foreach([
            ['label'=>'Annual (1yr)',   'count'=>$subStats['annual'],  'pill'=>'pill-green',  'icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','color'=>'#059669','bg'=>'#d1fae5'],
            ['label'=>'Semi (6mo)',    'count'=>$subStats['semi'],    'pill'=>'pill-violet', 'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'#7c3aed','bg'=>'#ede9fe'],
            ['label'=>'Trial (1mo)',   'count'=>$subStats['trial'],   'pill'=>'pill-sky',    'icon'=>'M13 10V3L4 14h7v7l9-11h-7z','color'=>'#0284c7','bg'=>'#e0f2fe'],
            ['label'=>'Lifetime',      'count'=>$subStats['life'],    'pill'=>'pill-amber',  'icon'=>'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z','color'=>'#d97706','bg'=>'#fef3c7'],
            ['label'=>'Pending',       'count'=>$subStats['pending'], 'pill'=>'pill-slate',  'icon'=>'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z','color'=>'#6b7280','bg'=>'#f3f4f6'],
        ] as $s)
        <div class="stat-card" style="text-align:center; padding:18px;">
            <div style="width:40px; height:40px; border-radius:12px; background:{{ $s['bg'] }}; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                <svg class="w-5 h-5" style="color:{{ $s['color'] }};" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
            </div>
            <p style="font-size:1.6rem; font-weight:800; color:#111827; margin:0 0 4px;">{{ $s['count'] }}</p>
            <p class="stat-label">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ══ ACTIVE SUBSCRIPTIONS TABLE ══ --}}
    <div class="section-card mb-6">
        <div class="section-card-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p class="section-card-title">Active Subscriptions</p>
                <p class="section-card-sub">Users with approved plans</p>
            </div>
            <a href="{{ route('admin.users') }}"
               style="display:flex; align-items:center; gap:6px; font-size:.8rem; font-weight:600; color:#10b981; text-decoration:none; background:#f0fdf4; border:1px solid #bbf7d0; padding:7px 12px; border-radius:8px;">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add / Manage
            </a>
        </div>
        <div style="overflow-x:auto;">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Plan Type</th>
                        <th>Expires</th>
                        <th>Days Left</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeUsers as $u)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#10b981,#059669); display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:800; color:#fff; flex-shrink:0;">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <span style="font-weight:600; font-size:.84rem; color:#111827;">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td style="color:#6b7280; font-size:.82rem;">{{ $u->email }}</td>
                        <td>
                            @if(!$u->plan_expires_at)
                            <span class="pill pill-amber">Lifetime</span>
                            @elseif($u->plan_expires_at->diffInMonths(now()) < -10)
                            <span class="pill pill-green">Annual</span>
                            @elseif($u->plan_expires_at->diffInMonths(now()) < -4)
                            <span class="pill pill-violet">Semi</span>
                            @else
                            <span class="pill pill-sky">Trial</span>
                            @endif
                        </td>
                        <td style="font-size:.82rem; color:#374151;">
                            {{ $u->plan_expires_at ? $u->plan_expires_at->format('M d, Y') : '∞ Never' }}
                        </td>
                        <td style="font-size:.82rem;">
                            @if(!$u->plan_expires_at)
                            <span style="color:#10b981; font-weight:700;">∞</span>
                            @elseif($u->plan_expires_at->isPast())
                            <span style="color:#ef4444; font-weight:700;">Expired</span>
                            @else
                            @php $days = now()->diffInDays($u->plan_expires_at); @endphp
                            <span style="color:{{ $days < 30 ? '#d97706' : '#059669' }}; font-weight:700;">{{ $days }}d</span>
                            @endif
                        </td>
                        <td>
                            @if($u->plan_expires_at && $u->plan_expires_at->isPast())
                            <span class="pill pill-slate">Expired</span>
                            @elseif($u->plan_expires_at && now()->diffInDays($u->plan_expires_at) < 30)
                            <span class="pill pill-amber">Expiring Soon</span>
                            @else
                            <span class="pill pill-green">Active</span>
                            @endif
                        </td>
                        <td>
                            <button @click="openModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}')"
                                    style="display:inline-flex; align-items:center; gap:5px; background:linear-gradient(135deg,#10b981,#059669); color:#fff; border:none; border-radius:8px; padding:6px 12px; font-size:.76rem; font-weight:700; cursor:pointer; box-shadow:0 2px 8px rgba(16,185,129,.25); transition:opacity .15s;"
                                    onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Approve
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; color:#9ca3af; padding:40px 20px; font-size:.85rem;">
                            No active subscriptions yet. Approve users from the Users tab.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ══ EXPIRED ══ --}}
    @if($expiredUsers->isNotEmpty())
    <div class="section-card" style="border-color:#fecaca;">
        <div class="section-card-header" style="background:#fff5f5;">
            <p class="section-card-title" style="color:#dc2626;">Expired Subscriptions</p>
            <p class="section-card-sub" style="color:#ef4444;">{{ $expiredUsers->count() }} user{{ $expiredUsers->count() > 1 ? 's' : '' }} with expired plans — need renewal</p>
        </div>
        <div style="overflow-x:auto;">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Expired On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiredUsers as $u)
                    <tr style="background:#fff5f5;">
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; border-radius:50%; background:linear-gradient(135deg,#ef4444,#dc2626); display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:800; color:#fff; flex-shrink:0;">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <span style="font-weight:600; font-size:.84rem; color:#111827;">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td style="color:#6b7280; font-size:.82rem;">{{ $u->email }}</td>
                        <td style="color:#ef4444; font-weight:600; font-size:.82rem;">
                            {{ $u->plan_expires_at->format('M d, Y') }}
                            <span style="color:#9ca3af; font-weight:400;">({{ $u->plan_expires_at->diffForHumans() }})</span>
                        </td>
                        <td>
                            <button @click="openModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}')"
                                    style="display:inline-flex; align-items:center; gap:5px; background:linear-gradient(135deg,#10b981,#059669); color:#fff; border:none; border-radius:8px; padding:6px 14px; font-size:.78rem; font-weight:700; cursor:pointer; box-shadow:0 2px 8px rgba(16,185,129,.25); transition:opacity .15s;"
                                    onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                Approve
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>{{-- end x-data --}}
</x-admin-layout>
