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

    {{-- ══════════════ ACTIVATE ACCOUNT MODAL ══════════════ --}}
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
                        <p style="font-size:1.05rem; font-weight:800; color:#111827;">Activate Account</p>
                        <p style="font-size:.75rem; color:#9ca3af; margin-top:1px;">Set plan and grant access</p>
                    </div>
                </div>
                <button @click="modal=false"
                        style="width:32px; height:32px; border-radius:8px; border:1px solid #e5e7eb; background:#f9fafb; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#9ca3af; font-size:1.1rem; line-height:1;">
                    ✕
                </button>
            </div>

            {{-- User info --}}
            <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; padding:14px 16px; margin-bottom:22px; display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,#f59e0b,#d97706); display:flex; align-items:center; justify-content:center; font-size:.8rem; font-weight:800; color:#fff; flex-shrink:0;"
                     x-text="userName.slice(0,2).toUpperCase()"></div>
                <div>
                    <p style="font-weight:700; font-size:.88rem; color:#111827;" x-text="userName"></p>
                    <p style="font-size:.75rem; color:#6b7280;" x-text="userEmail"></p>
                </div>
                <span style="margin-left:auto; display:inline-flex; align-items:center; gap:4px; background:#fef3c7; color:#d97706; font-size:.7rem; font-weight:700; padding:3px 9px; border-radius:20px;">
                    <span style="width:6px; height:6px; background:#f59e0b; border-radius:50%; display:inline-block; animation:pulse 2s infinite;"></span>
                    Pending
                </span>
            </div>

            {{-- Plan Selection --}}
            <p style="font-size:.78rem; font-weight:700; color:#374151; text-transform:uppercase; letter-spacing:.07em; margin-bottom:12px;">Select Subscription Plan</p>
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

            {{-- Plan duration display --}}
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
                        Activate Account
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══ PENDING APPROVALS PANEL ══ --}}
    @if($pendingUsers->isNotEmpty())
    <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:14px; padding:20px 24px; margin-bottom:24px;">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
            <div style="width:36px; height:36px; background:#fef3c7; border-radius:10px; display:flex; align-items:center; justify-content:center;">
                <svg class="w-5 h-5" style="color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p style="font-weight:700; font-size:.9rem; color:#92400e;">{{ $pendingUsers->count() }} User{{ $pendingUsers->count() > 1 ? 's' : '' }} Awaiting Approval</p>
                <p style="font-size:.75rem; color:#b45309; margin-top:1px;">Click Activate to set their plan and grant access</p>
            </div>
        </div>
        <div style="display:flex; flex-direction:column; gap:10px;">
            @foreach($pendingUsers as $pu)
            <div style="background:#fff; border:1px solid #fde68a; border-radius:12px; padding:14px 18px; display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
                <div style="width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg,#f59e0b,#d97706); display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:800; color:#fff; flex-shrink:0;">
                    {{ strtoupper(substr($pu->name, 0, 2)) }}
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-weight:700; font-size:.875rem; color:#111827;">{{ $pu->name }}</p>
                    <p style="font-size:.78rem; color:#6b7280; margin-top:1px;">{{ $pu->email }} · Registered {{ $pu->created_at->diffForHumans() }}</p>
                </div>
                <div style="display:flex; gap:8px; align-items:center; flex-shrink:0;">
                    <button @click="openModal({{ $pu->id }}, '{{ addslashes($pu->name) }}', '{{ addslashes($pu->email) }}')"
                            style="display:flex; align-items:center; gap:6px; background:linear-gradient(135deg,#10b981,#059669); color:#fff; border:none; border-radius:10px; padding:9px 18px; font-size:.82rem; font-weight:700; cursor:pointer; box-shadow:0 4px 12px rgba(16,185,129,.3); transition:opacity .15s;"
                            onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Activate
                    </button>
                    <form method="POST" action="{{ route('admin.users.reject', $pu) }}"
                          onsubmit="return confirm('Permanently delete {{ addslashes($pu->name) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" style="display:flex; align-items:center; gap:5px; background:#fff; color:#ef4444; border:1.5px solid #fecaca; border-radius:10px; padding:9px 14px; font-size:.82rem; font-weight:700; cursor:pointer; transition:all .15s;"
                                onmouseover="this.style.background='#fff5f5'" onmouseout="this.style.background='#fff'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            Reject
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══ ALL USERS TABLE ══ --}}
    <div class="section-card">
        <div class="section-card-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p class="section-card-title">All Users</p>
                <p class="section-card-sub">{{ $allUsers->total() }} total registered accounts</p>
            </div>
            <div style="position:relative;">
                <input type="text" placeholder="Search users…" oninput="filterUsers(this.value)"
                       style="font-size:.8rem; border:1px solid #e5e7eb; border-radius:9px; padding:7px 12px 7px 32px; outline:none; width:200px; color:#374151;">
                <svg class="w-3.5 h-3.5" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#9ca3af;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="adm-table" id="usersTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Plan Expires</th>
                        <th>Days Left</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allUsers as $i => $u)
                    <tr class="user-row" data-name="{{ strtolower($u->name) }}" data-email="{{ strtolower($u->email) }}">
                        <td style="color:#9ca3af; font-size:.78rem;">{{ $allUsers->firstItem() + $i }}</td>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:34px; height:34px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:800; color:#fff; flex-shrink:0;
                                     background:{{ $u->email === env('ADMIN_EMAIL') ? 'linear-gradient(135deg,#ef4444,#dc2626)' : ($u->is_approved ? 'linear-gradient(135deg,#10b981,#059669)' : 'linear-gradient(135deg,#f59e0b,#d97706)') }};">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p style="font-weight:700; font-size:.84rem; color:#111827;">{{ $u->name }}</p>
                                    @if($u->email === env('ADMIN_EMAIL'))
                                    <p style="font-size:.7rem; color:#ef4444; font-weight:600;">Super Admin</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="color:#6b7280; font-size:.82rem;">{{ $u->email }}</td>
                        <td>
                            @if($u->email === env('ADMIN_EMAIL'))
                            <span class="pill pill-rose">Owner</span>
                            @elseif($u->is_approved && $u->plan_expires_at && $u->plan_expires_at->isPast())
                            <span class="pill pill-slate">Expired</span>
                            @elseif($u->is_approved)
                            <span class="pill pill-green">Active</span>
                            @else
                            <span class="pill pill-amber">Pending</span>
                            @endif
                        </td>
                        <td style="font-size:.8rem; color:#6b7280;">
                            @if($u->email === env('ADMIN_EMAIL'))
                            <span style="color:#10b981; font-weight:600;">∞ Lifetime</span>
                            @elseif($u->plan_expires_at)
                                <span style="color:{{ $u->plan_expires_at->isPast() ? '#ef4444' : '#374151' }};">
                                    {{ $u->plan_expires_at->format('M d, Y') }}
                                </span>
                            @else
                            <span style="color:#d1d5db;">—</span>
                            @endif
                        </td>
                        <td style="font-size:.82rem; font-weight:700;">
                            @if($u->email === env('ADMIN_EMAIL'))
                            <span style="color:#10b981;">∞</span>
                            @elseif($u->plan_expires_at)
                                @if($u->plan_expires_at->isPast())
                                <span style="color:#ef4444;">Expired</span>
                                @else
                                @php $d = now()->diffInDays($u->plan_expires_at); @endphp
                                <span style="color:{{ $d < 30 ? '#d97706' : '#059669' }};">{{ $d }}d</span>
                                @endif
                            @else
                            <span style="color:#d1d5db;">—</span>
                            @endif
                        </td>
                        <td style="color:#9ca3af; font-size:.78rem;">{{ $u->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($u->email !== env('ADMIN_EMAIL'))
                            <div style="display:flex; gap:6px; align-items:center;">
                                @if(!$u->is_approved)
                                <button @click="openModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}')"
                                        class="pill pill-green" style="border:none; cursor:pointer; font-size:.72rem; padding:4px 10px;">
                                    ✓ Activate
                                </button>
                                @elseif($u->plan_expires_at && $u->plan_expires_at->isPast())
                                <button @click="openModal({{ $u->id }}, '{{ addslashes($u->name) }}', '{{ addslashes($u->email) }}')"
                                        class="pill pill-amber" style="border:none; cursor:pointer; font-size:.72rem; padding:4px 10px;">
                                    ↺ Renew
                                </button>
                                @else
                                <span style="color:#9ca3af; font-size:.75rem;">Active</span>
                                @endif
                                <form method="POST" action="{{ route('admin.users.reject', $u) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($u->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="pill pill-rose" style="border:none; cursor:pointer; font-size:.72rem;">✕</button>
                                </form>
                            </div>
                            @else
                            <span style="color:#9ca3af; font-size:.75rem;">Protected</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($allUsers->hasPages())
        <div style="padding:14px 22px; border-top:1px solid #f3f4f6; font-size:.82rem;">
            {{ $allUsers->links() }}
        </div>
        @endif
    </div>

</div>{{-- end x-data --}}

@push('scripts')
<script>
function filterUsers(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.user-row').forEach(r => {
        r.style.display = (r.dataset.name.includes(q) || r.dataset.email.includes(q)) ? '' : 'none';
    });
}
</script>
@endpush
</x-admin-layout>
