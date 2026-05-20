<x-app-layout>
    <div class="py-8" x-data="{showForm:false, editing:null}">

        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color:#10b981;">ኣባላት · People</p>
                <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800">Members <span class="text-slate-300 font-light text-2xl">ኣባላት</span></h1>
                <p class="text-sm text-slate-400 mt-1">Register and manage your organization's members</p>
            </div>
            <button @click="showForm=!showForm"
                    class="inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all shadow-lg"
                    style="background:#10b981;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Member · ኣባል ወስኽ
            </button>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
                <p class="text-3xl font-extrabold text-slate-800">{{ $stats['total'] }}</p>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide font-semibold">Total · ጠቕላሊ</p>
            </div>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 text-center">
                <p class="text-3xl font-extrabold" style="color:#10b981;">{{ $stats['active'] }}</p>
                <p class="text-xs text-slate-400 mt-1 uppercase tracking-wide font-semibold">Active · ንጡፋት</p>
            </div>
        </div>

        {{-- Add Member Form --}}
        <div x-show="showForm" x-transition class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6" style="display:none;">
            <h3 class="font-bold text-slate-800 mb-1">Register New Member</h3>
            <p class="text-xs text-slate-400 mb-4">ሓድሽ ኣባል ምምዝጋብ</p>
            @if($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3">
                <ul class="text-rose-600 text-sm space-y-1">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
            </div>
            @endif
            <form method="POST" action="{{ route('members.store') }}">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Full Name · ምሉእ ስም *</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                               placeholder="ምሉእ ስምካ ጸሓፍ"
                               class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:border-transparent transition-all"
                               style="--tw-ring-color:#10b981;">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Phone · ቴለፎን</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               placeholder="+291 7xx xxx"
                               class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Zone · ዞባ</label>
                        <input type="text" name="zone" value="{{ old('zone') }}"
                               placeholder="e.g. Zoba Maekel, Debub"
                               class="w-full text-sm border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:border-transparent transition-all">
                    </div>
                </div>
                <div class="flex items-center gap-3 mt-4">
                    <button type="submit"
                            class="text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all"
                            style="background:#10b981;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                        Save Member · ኣቐምጥ
                    </button>
                    <button type="button" @click="showForm=false" class="text-sm text-slate-500 hover:text-slate-700 px-4 py-2.5">Cancel</button>
                </div>
            </form>
        </div>

        {{-- Members Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            @if($members->isNotEmpty())
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100" style="background:#f8fafc;">
                        <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide px-5 py-3.5">Member · ኣባል</th>
                        <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide px-5 py-3.5 hidden md:table-cell">Phone · ቴለፎን</th>
                        <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide px-5 py-3.5 hidden lg:table-cell">Zone · ዞባ</th>
                        <th class="text-left text-xs font-bold text-slate-500 uppercase tracking-wide px-5 py-3.5 hidden lg:table-cell">Joined</th>
                        <th class="px-5 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                @foreach($members as $m)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                 style="background:linear-gradient(135deg,#10b981,#059669);">
                                {{ strtoupper(substr($m->full_name, 0, 2)) }}
                            </div>
                            <p class="font-semibold text-slate-800">{{ $m->full_name }}</p>
                        </div>
                    </td>
                    <td class="px-5 py-4 hidden md:table-cell text-slate-500 text-sm">{{ $m->phone ?? '—' }}</td>
                    <td class="px-5 py-4 hidden lg:table-cell">
                        @if($m->zone)
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full" style="background:#dcfce7;color:#166534;">{{ $m->zone }}</span>
                        @else
                        <span class="text-slate-300 text-xs">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-slate-400 text-xs hidden lg:table-cell">{{ $m->joined_date->format('M d, Y') }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <button @click="editing = editing === {{ $m->id }} ? null : {{ $m->id }}"
                                    class="text-slate-400 hover:text-emerald-500 transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <form method="POST" action="{{ route('members.destroy', $m) }}" onsubmit="return confirm('Remove {{ addslashes($m->full_name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                {{-- Inline edit row --}}
                <tr x-show="editing === {{ $m->id }}" x-cloak class="bg-emerald-50/60 border-b border-emerald-100">
                    <td colspan="5" class="px-5 py-4">
                        <form method="POST" action="{{ route('members.update', $m) }}" class="flex flex-wrap items-end gap-3">
                            @csrf @method('PATCH')
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Full Name *</label>
                                <input type="text" name="full_name" value="{{ $m->full_name }}" required
                                       class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-400 outline-none w-44">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Phone</label>
                                <input type="text" name="phone" value="{{ $m->phone }}"
                                       class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-400 outline-none w-36">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Zone</label>
                                <input type="text" name="zone" value="{{ $m->zone }}"
                                       class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-emerald-400 outline-none w-36">
                            </div>
                            <div class="flex gap-2">
                                <button type="submit" class="text-white text-xs font-bold px-4 py-2 rounded-xl transition-all"
                                        style="background:#10b981;" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                                    Save
                                </button>
                                <button type="button" @click="editing=null" class="text-xs text-slate-500 hover:text-slate-700 px-3 py-2">Cancel</button>
                            </div>
                        </form>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            <div class="px-5 py-4 border-t border-slate-100">{{ $members->links() }}</div>
            @else
            <div class="flex flex-col items-center justify-center py-20">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-4" style="background:#f0fdf4;">
                    <svg class="w-8 h-8" style="color:#10b981;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-500">No members yet · ኣባላት የለዉን</p>
                <button @click="showForm=true" class="mt-3 text-sm font-semibold" style="color:#10b981;">Add your first member →</button>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
