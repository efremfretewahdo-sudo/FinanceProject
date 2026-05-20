<x-admin-layout>
    @push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @endpush

    {{-- ══ TOP STATS ══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-7">

        {{-- Total Users --}}
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#ede9fe;">
                    <svg class="w-5 h-5" style="color:#7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <p class="stat-label">Total Users</p>
            <p class="stat-value">{{ number_format($totalUsers) }}</p>
            <span class="stat-badge {{ $userGrowthPct >= 0 ? 'up' : 'down' }}">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $userGrowthPct >= 0 ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M17 13l-5 5m0 0l-5-5m5 5V6' }}"/>
                </svg>
                {{ abs($userGrowthPct) }}% this month
            </span>
        </div>

        {{-- Total Messages --}}
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#e0f2fe;">
                    <svg class="w-5 h-5" style="color:#0284c7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <p class="stat-label">Total Messages</p>
            <p class="stat-value">{{ number_format($totalMessages) }}</p>
            <span class="stat-badge {{ $unreadCount > 0 ? 'down' : 'neu' }}">
                {{ $unreadCount > 0 ? $unreadCount . ' unread' : 'All read' }}
            </span>
        </div>

        {{-- Conversations --}}
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#fef3c7;">
                    <svg class="w-5 h-5" style="color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
            </div>
            <p class="stat-label">Conversations</p>
            <p class="stat-value">{{ number_format($totalMessages) }}</p>
            <span class="stat-badge neu">Contact threads</span>
        </div>

        {{-- Monthly Revenue --}}
        <div class="stat-card">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#d1fae5;">
                    <svg class="w-5 h-5" style="color:#059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="stat-label">Monthly Revenue</p>
            <p class="stat-value">${{ number_format($monthlyRevenue, 0) }}</p>
            <span class="stat-badge neu">Platform total</span>
        </div>
    </div>

    {{-- ══ MIDDLE GRID ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">

        {{-- User Growth Chart --}}
        <div class="section-card lg:col-span-1">
            <div class="section-card-header">
                <p class="section-card-title">User Growth</p>
                <p class="section-card-sub">Weekly registrations · last 8 weeks</p>
            </div>
            <div class="section-card-body">
                <div style="height:180px; position:relative;">
                    <canvas id="growthChart"></canvas>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div style="background:#f9fafb; border-radius:10px; padding:10px 12px; text-align:center;">
                        <p class="stat-label">This Week</p>
                        <p style="font-size:1.4rem; font-weight:800; color:#111827; margin:2px 0;">{{ $weeklyUsers }}</p>
                    </div>
                    <div style="background:#f9fafb; border-radius:10px; padding:10px 12px; text-align:center;">
                        <p class="stat-label">Pending</p>
                        <p style="font-size:1.4rem; font-weight:800; color:#d97706; margin:2px 0;">{{ $pendingUsers->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Subscription Analytics --}}
        <div class="section-card lg:col-span-1">
            <div class="section-card-header">
                <p class="section-card-title">Subscription Analytics</p>
                <p class="section-card-sub">User plan distribution</p>
            </div>
            <div class="section-card-body">
                <div style="height:160px; position:relative; display:flex; justify-content:center;">
                    <canvas id="subChart"></canvas>
                </div>
                <div class="mt-5 space-y-2">
                    @foreach([
                        ['label'=>'Annual (1 Year)',  'count'=>$subStats['annual'],  'color'=>'#10b981', 'pill'=>'pill-green'],
                        ['label'=>'Semi (6 Months)', 'count'=>$subStats['semi'],   'color'=>'#7c3aed', 'pill'=>'pill-violet'],
                        ['label'=>'Trial (1 Month)', 'count'=>$subStats['trial'],  'color'=>'#0284c7', 'pill'=>'pill-sky'],
                        ['label'=>'Lifetime',        'count'=>$subStats['life'],   'color'=>'#d97706', 'pill'=>'pill-amber'],
                        ['label'=>'Pending',         'count'=>$subStats['pending'],'color'=>'#9ca3af', 'pill'=>'pill-slate'],
                    ] as $s)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $s['color'] }};"></div>
                            <span style="font-size:.8rem; color:#374151;">{{ $s['label'] }}</span>
                        </div>
                        <span class="pill {{ $s['pill'] }}">{{ $s['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="section-card lg:col-span-1">
            <div class="section-card-header">
                <p class="section-card-title">Quick Actions</p>
                <p class="section-card-sub">System management shortcuts</p>
            </div>
            <div class="section-card-body">
                <a href="{{ route('admin.users') }}" class="action-btn">
                    <div class="action-btn-icon" style="background:#ede9fe;">
                        <svg class="w-4 h-4" style="color:#7c3aed;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    Manage Users
                    @if($pendingUsers->count() > 0)
                    <span class="pill pill-amber ml-auto">{{ $pendingUsers->count() }} pending</span>
                    @endif
                </a>
                <a href="{{ route('admin.subscriptions') }}" class="action-btn">
                    <div class="action-btn-icon" style="background:#d1fae5;">
                        <svg class="w-4 h-4" style="color:#059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    </div>
                    Add Subscription
                </a>
                <a href="{{ route('admin.messages') }}" class="action-btn">
                    <div class="action-btn-icon" style="background:#e0f2fe;">
                        <svg class="w-4 h-4" style="color:#0284c7;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    View Messages
                    @if($unreadCount > 0)
                    <span class="pill pill-rose ml-auto">{{ $unreadCount }} new</span>
                    @endif
                </a>

                {{-- System lock/unlock --}}
                <div style="border-top:1px solid #f3f4f6; margin-top:4px; padding-top:12px;">
                    @if($systemLocked)
                    <form method="POST" action="{{ route('admin.unlock') }}">
                        @csrf
                        <button type="submit" class="action-btn" style="border-color:#bbf7d0; background:#f0fdf4; color:#15803d;">
                            <div class="action-btn-icon" style="background:#d1fae5;">
                                <svg class="w-4 h-4" style="color:#059669;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                            </div>
                            Unlock System
                        </button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.lock') }}">
                        @csrf
                        <button type="submit" class="action-btn" style="border-color:#fecaca; background:#fff5f5; color:#dc2626;">
                            <div class="action-btn-icon" style="background:#fee2e2;">
                                <svg class="w-4 h-4" style="color:#ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            Lock System (24h)
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ══ PLATFORM ANALYTICS ══ --}}
    <div class="section-card mb-7">
        <div class="section-card-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p class="section-card-title">Platform Analytics</p>
                <p class="section-card-sub">Income vs Expenses · {{ now()->year }}</p>
            </div>
            <span class="pill pill-green">Live</span>
        </div>
        <div class="section-card-body">
            <div style="height:200px; position:relative;">
                <canvas id="platformChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ══ RECENT ACTIVITY ══ --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
        <div class="activity-card" style="background:linear-gradient(135deg,#7c3aed,#6d28d9); color:#fff;">
            <p class="activity-card-label">User Growth</p>
            <p class="activity-card-value">+{{ $weeklyUsers }}</p>
            <p class="activity-card-sub">New this week</p>
        </div>
        <div class="activity-card" style="background:linear-gradient(135deg,#0284c7,#0369a1); color:#fff;">
            <p class="activity-card-label">Message Activity</p>
            <p class="activity-card-value">{{ $totalMessages }}</p>
            <p class="activity-card-sub">{{ $unreadCount }} unread</p>
        </div>
        <div class="activity-card" style="background:linear-gradient(135deg,#059669,#047857); color:#fff;">
            <p class="activity-card-label">Revenue</p>
            <p class="activity-card-value">${{ number_format($totalIncome, 0) }}</p>
            <p class="activity-card-sub">Platform total</p>
        </div>
        <div class="activity-card" style="background:linear-gradient(135deg,#d97706,#b45309); color:#fff;">
            <p class="activity-card-label">Conversion</p>
            <p class="activity-card-value">{{ $totalUsers > 0 ? round(($approvedUsers/$totalUsers)*100) : 0 }}%</p>
            <p class="activity-card-sub">Approval rate</p>
        </div>
    </div>

    {{-- ══ SYSTEM HEALTH ══ --}}
    <div class="section-card mb-7">
        <div class="section-card-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p class="section-card-title">System Health</p>
                <p class="section-card-sub">Live platform diagnostics · Guardian mode</p>
            </div>
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="position:relative; display:inline-flex; align-items:center; justify-content:center; width:16px; height:16px;">
                    <span style="position:absolute; width:100%; height:100%; border-radius:50%; background:#10b981; opacity:.5; animation:ping 1.4s cubic-bezier(0,0,.2,1) infinite;"></span>
                    <span style="width:10px; height:10px; border-radius:50%; background:#10b981; display:block;"></span>
                </span>
                <span style="font-size:.75rem; font-weight:700; color:#10b981;">LIVE</span>
            </div>
        </div>

        <div class="section-card-body">

            {{-- Animated ECG / Pulse Line --}}
            <div style="background:#f9fafb; border:1px solid #f3f4f6; border-radius:14px; padding:16px 20px; margin-bottom:18px; overflow:hidden; position:relative;">
                <p style="font-size:.7rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.1em; margin-bottom:10px;">System Pulse</p>
                <svg viewBox="0 0 500 60" style="width:100%; height:60px;" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="ecg-grad" x1="0" y1="0" x2="1" y2="0">
                            <stop offset="0%" stop-color="transparent"/>
                            <stop offset="30%" stop-color="#10b981" stop-opacity=".3"/>
                            <stop offset="70%" stop-color="#10b981"/>
                            <stop offset="100%" stop-color="transparent"/>
                        </linearGradient>
                    </defs>
                    {{-- Flat line segments + heartbeat spike --}}
                    <polyline
                        points="0,30 60,30 80,30 90,10 100,50 110,30 130,30 200,30 210,30 220,8 230,52 240,30 260,30 330,30 340,30 350,10 360,50 370,30 390,30 460,30 470,30 480,10 490,50 500,30"
                        fill="none"
                        stroke="url(#ecg-grad)"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        style="stroke-dasharray:900; stroke-dashoffset:900; animation:ecg-draw 3s linear infinite;"
                    />
                </svg>
                <style>
                    @keyframes ecg-draw {
                        0%   { stroke-dashoffset: 900; }
                        100% { stroke-dashoffset: -900; }
                    }
                    @keyframes ping {
                        75%, 100% { transform: scale(2); opacity: 0; }
                    }
                </style>
            </div>

            {{-- Status Grid --}}
            <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:12px; margin-bottom:18px;">

                {{-- DB Status --}}
                <div style="border-radius:12px; padding:14px 16px; display:flex; align-items:center; gap:12px;
                     background:{{ $dbOk ? '#f0fdf4' : '#fff5f5' }}; border:1px solid {{ $dbOk ? '#bbf7d0' : '#fecaca' }};">
                    <div style="width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
                         background:{{ $dbOk ? 'rgba(16,185,129,.15)' : 'rgba(239,68,68,.15)' }};">
                        <svg class="w-5 h-5" style="color:{{ $dbOk ? '#10b981' : '#ef4444' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:.78rem; font-weight:800; color:{{ $dbOk ? '#15803d' : '#dc2626' }};">Database</p>
                        <p style="font-size:.7rem; color:{{ $dbOk ? '#16a34a' : '#ef4444' }}; margin-top:1px;">
                            {{ $dbOk ? '● Connected' : '● Connection Error' }}
                        </p>
                    </div>
                </div>

                {{-- System Lock Status --}}
                <div style="border-radius:12px; padding:14px 16px; display:flex; align-items:center; gap:12px;
                     background:{{ $systemLocked ? '#fff5f5' : '#f0fdf4' }}; border:1px solid {{ $systemLocked ? '#fecaca' : '#bbf7d0' }};">
                    <div style="width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;
                         background:{{ $systemLocked ? 'rgba(239,68,68,.15)' : 'rgba(16,185,129,.15)' }};">
                        <svg class="w-5 h-5" style="color:{{ $systemLocked ? '#ef4444' : '#10b981' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($systemLocked)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:.78rem; font-weight:800; color:{{ $systemLocked ? '#dc2626' : '#15803d' }};">System Lock</p>
                        <p style="font-size:.7rem; color:{{ $systemLocked ? '#ef4444' : '#16a34a' }}; margin-top:1px;">
                            {{ $systemLocked ? '● LOCKED — Users blocked' : '● Unlocked — Normal access' }}
                        </p>
                    </div>
                </div>

                {{-- Approved Users --}}
                <div style="border-radius:12px; padding:14px 16px; display:flex; align-items:center; gap:12px; background:#eff6ff; border:1px solid #bfdbfe;">
                    <div style="width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:rgba(59,130,246,.12);">
                        <svg class="w-5 h-5" style="color:#3b82f6;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:.78rem; font-weight:800; color:#1d4ed8;">Active Users</p>
                        <p style="font-size:.7rem; color:#3b82f6; margin-top:1px;">● {{ $approvedUsers }} approved accounts</p>
                    </div>
                </div>

                {{-- Cache / Server --}}
                <div style="border-radius:12px; padding:14px 16px; display:flex; align-items:center; gap:12px; background:#fef3c7; border:1px solid #fde68a;">
                    <div style="width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; background:rgba(217,119,6,.12);">
                        <svg class="w-5 h-5" style="color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"/>
                        </svg>
                    </div>
                    <div>
                        <p style="font-size:.78rem; font-weight:800; color:#92400e;">Server</p>
                        <p style="font-size:.7rem; color:#d97706; margin-top:1px;">● PHP {{ PHP_MAJOR_VERSION }}.{{ PHP_MINOR_VERSION }} · Laravel 11</p>
                    </div>
                </div>
            </div>

            {{-- DB Alert banner (only if DB is down) --}}
            @if(!$dbOk)
            <div style="background:#fff5f5; border:1.5px solid #fecaca; border-radius:12px; padding:14px 18px; display:flex; align-items:center; gap:12px;">
                <svg class="w-5 h-5 flex-shrink-0" style="color:#ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.834-1.964-.834-2.732 0L3.07 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <p style="font-size:.82rem; font-weight:800; color:#dc2626;">Database Connection Error</p>
                    <p style="font-size:.75rem; color:#ef4444; margin-top:2px;">Check your .env DB credentials and ensure MySQL is running.</p>
                </div>
            </div>
            @endif

            {{-- Lock / Unlock Toggle --}}
            <div style="border-top:1px solid #f3f4f6; margin-top:16px; padding-top:16px; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <p style="font-size:.82rem; font-weight:700; color:#374151;">Guardian Lock</p>
                    <p style="font-size:.72rem; color:#9ca3af; margin-top:1px;">{{ $systemLocked ? 'All users blocked. Unlock to restore access.' : 'System open. Lock to block all users.' }}</p>
                </div>
                @if($systemLocked)
                <form method="POST" action="{{ route('admin.unlock') }}">
                    @csrf
                    <button type="submit"
                            style="display:flex; align-items:center; gap:6px; background:linear-gradient(135deg,#10b981,#059669); color:#fff; border:none; border-radius:10px; padding:9px 18px; font-size:.8rem; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(16,185,129,.35); transition:opacity .15s;"
                            onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                        Unlock System
                    </button>
                </form>
                @else
                <form method="POST" action="{{ route('admin.lock') }}"
                      onsubmit="return confirm('Lock the system? All users will be blocked.')">
                    @csrf
                    <button type="submit"
                            style="display:flex; align-items:center; gap:6px; background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; border:none; border-radius:10px; padding:9px 18px; font-size:.8rem; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(239,68,68,.35); transition:opacity .15s;"
                            onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Lock System
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    {{-- ══ RECENT USERS ══ --}}
    <div class="section-card">
        <div class="section-card-header" style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <p class="section-card-title">Recent Registrations</p>
                <p class="section-card-sub">Latest users joining the platform</p>
            </div>
            <a href="{{ route('admin.users') }}" style="font-size:.8rem; font-weight:600; color:#10b981; text-decoration:none;">View all →</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Plan</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestUsers as $u)
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white"
                                     style="background:{{ $u->email === env('ADMIN_EMAIL') ? '#ef4444' : ($u->is_approved ? '#10b981' : '#d97706') }}; width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:700; color:#fff; flex-shrink:0;">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </div>
                                <span style="font-weight:600; color:#111827;">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td style="color:#6b7280;">{{ $u->email }}</td>
                        <td>
                            @if($u->email === env('ADMIN_EMAIL'))
                            <span class="pill pill-rose">Super Admin</span>
                            @elseif($u->is_approved && $u->plan_expires_at && $u->plan_expires_at->isPast())
                            <span class="pill pill-slate">Expired</span>
                            @elseif($u->is_approved)
                            <span class="pill pill-green">Active</span>
                            @else
                            <span class="pill pill-amber">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($u->email === env('ADMIN_EMAIL'))
                            <span class="pill" style="background:#fee2e2;color:#dc2626;">Lifetime</span>
                            @elseif($u->plan_expires_at)
                            <span style="font-size:.78rem; color:#6b7280;">Exp: {{ $u->plan_expires_at->format('M d, Y') }}</span>
                            @else
                            <span style="color:#d1d5db; font-size:.78rem;">—</span>
                            @endif
                        </td>
                        <td style="color:#9ca3af; font-size:.78rem;">{{ $u->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
    Chart.defaults.font.family = 'Figtree, sans-serif';
    Chart.defaults.font.size = 11;

    // User growth (weekly - last 8 weeks)
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    const growthGrad = growthCtx.createLinearGradient(0, 0, 0, 180);
    growthGrad.addColorStop(0, 'rgba(124,58,237,.2)');
    growthGrad.addColorStop(1, 'rgba(124,58,237,0)');
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: @json($weeklyLabels),
            datasets: [{
                data: @json($weeklyGrowth),
                borderColor: '#7c3aed',
                backgroundColor: growthGrad,
                borderWidth: 2,
                fill: true,
                tension: 0.45,
                pointBackgroundColor: '#7c3aed',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 3,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#9ca3af' } },
                y: { grid: { color: '#f3f4f6' }, border: { display: false }, ticks: { color: '#9ca3af', stepSize: 1 } }
            }
        }
    });

    // Subscription doughnut
    const subCtx = document.getElementById('subChart').getContext('2d');
    new Chart(subCtx, {
        type: 'doughnut',
        data: {
            labels: ['Annual', 'Semi', 'Trial', 'Lifetime', 'Pending'],
            datasets: [{
                data: [
                    {{ $subStats['annual'] }},
                    {{ $subStats['semi'] }},
                    {{ $subStats['trial'] }},
                    {{ $subStats['life'] }},
                    {{ $subStats['pending'] }}
                ],
                backgroundColor: ['#10b981','#7c3aed','#0284c7','#d97706','#d1d5db'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.raw + ' users' } }
            },
            cutout: '70%',
        }
    });

    // Platform income vs expenses bar chart
    const platCtx = document.getElementById('platformChart').getContext('2d');
    const stats = @json($monthlyStats);
    new Chart(platCtx, {
        type: 'bar',
        data: {
            labels: stats.map(s => s.month),
            datasets: [
                { label: 'Income',   data: stats.map(s => s.income),  backgroundColor: 'rgba(16,185,129,.85)', borderRadius: 5, borderSkipped: false },
                { label: 'Expenses', data: stats.map(s => s.expense), backgroundColor: 'rgba(239,68,68,.75)',   borderRadius: 5, borderSkipped: false }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 16, font: { size: 12 } } } },
            scales: {
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#9ca3af' } },
                y: { grid: { color: '#f9fafb', borderDash: [4,4] }, border: { display: false }, ticks: { color: '#9ca3af', callback: v => '$' + v.toLocaleString() } }
            }
        }
    });
    </script>
    @endpush
</x-admin-layout>
