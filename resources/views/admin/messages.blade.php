<x-admin-layout>

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
        <div>
            <h2 style="font-size:1.1rem; font-weight:800; color:#111827;">Contact Messages</h2>
            <p style="font-size:.78rem; color:#9ca3af; margin-top:2px;">{{ $messages->total() }} total · {{ $unreadCount }} unread</p>
        </div>
        @if($unreadCount > 0)
        <span class="pill pill-rose">{{ $unreadCount }} new</span>
        @else
        <span class="pill pill-green">All read</span>
        @endif
    </div>

    @if($messages->isEmpty())
    <div class="section-card" style="text-align:center; padding:60px 20px;">
        <div style="width:56px; height:56px; background:#f3f4f6; border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
            <svg class="w-6 h-6" style="color:#d1d5db;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <p style="font-weight:600; color:#9ca3af; font-size:.9rem;">No messages yet</p>
    </div>
    @else
    <div class="section-card">
        <div style="overflow-x:auto;">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $msg)
                    <tr style="{{ !$msg->is_read ? 'background:#f0fdf4;' : '' }}">
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:34px; height:34px; border-radius:50%; background:linear-gradient(135deg,#10b981,#059669); display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:800; color:#fff; flex-shrink:0;">
                                    {{ strtoupper(substr($msg->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p style="font-weight:700; font-size:.84rem; color:#111827; display:flex; align-items:center; gap:6px;">
                                        {{ $msg->name }}
                                        @if(!$msg->is_read)
                                        <span style="width:7px; height:7px; background:#10b981; border-radius:50%; display:inline-block;"></span>
                                        @endif
                                    </p>
                                    <p style="font-size:.74rem; color:#6b7280;">{{ $msg->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.82rem; font-weight:600; color:#374151;">
                            {{ $msg->subject ?: '—' }}
                        </td>
                        <td style="max-width:280px;">
                            <p style="font-size:.82rem; color:#6b7280; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                                {{ $msg->message }}
                            </p>
                        </td>
                        <td style="font-size:.78rem; color:#9ca3af; white-space:nowrap;">
                            {{ $msg->created_at->format('M d, Y') }}<br>
                            <span style="color:#d1d5db;">{{ $msg->created_at->format('H:i') }}</span>
                        </td>
                        <td>
                            <div style="display:flex; gap:6px; align-items:center;">
                                @if(!$msg->is_read)
                                <form method="POST" action="{{ route('admin.messages.read', $msg) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="pill pill-green" style="border:none; cursor:pointer; font-size:.72rem;">✓ Read</button>
                                </form>
                                @endif
                                <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}"
                                      onsubmit="return confirm('Delete this message?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="pill pill-rose" style="border:none; cursor:pointer; font-size:.72rem;">✕ Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
        <div style="padding:14px 22px; border-top:1px solid #f3f4f6; font-size:.82rem;">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
    @endif
</x-admin-layout>
