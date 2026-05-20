<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        try { \Illuminate\Support\Facades\DB::connection()->getPdo(); $dbOk = true; } catch (\Exception $e) { $dbOk = false; }

        $totalUsers        = User::count();
        $totalMessages     = ContactMessage::count();
        $unreadCount       = ContactMessage::where('is_read', false)->count();
        $totalIncome       = (float) Transaction::where('type', 'income')->sum('amount');
        $totalExpenses     = (float) Transaction::where('type', 'expense')->sum('amount');
        $approvedUsers     = User::where('is_approved', true)->count();
        $latestUsers       = User::latest()->take(8)->get();
        $pendingUsers      = User::where('is_approved', false)->latest()->get();
        $monthlyRevenue    = (float) Transaction::where('type', 'income')
                                ->whereMonth('transaction_date', now()->month)
                                ->whereYear('transaction_date', now()->year)
                                ->sum('amount');
        $systemLocked      = (bool) cache('system_locked');

        // User growth this month vs last month
        $thisMonth  = User::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();
        $prevMonth  = now()->subMonth();
        $lastMonth  = User::whereYear('created_at', $prevMonth->year)->whereMonth('created_at', $prevMonth->month)->count();
        $userGrowthPct = $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100) : ($thisMonth > 0 ? 100 : 0);

        // Weekly growth (last 8 weeks)
        $weeklyLabels = [];
        $weeklyGrowth = [];
        for ($w = 7; $w >= 0; $w--) {
            $start = now()->startOfWeek()->subWeeks($w);
            $end   = (clone $start)->endOfWeek();
            $weeklyLabels[] = 'W' . $start->weekOfYear;
            $weeklyGrowth[] = User::whereBetween('created_at', [$start, $end])->count();
        }
        $weeklyUsers = $weeklyGrowth[count($weeklyGrowth) - 1];

        // Monthly stats for bar chart
        $monthlyStats = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyStats[] = [
                'month'   => date('M', mktime(0, 0, 0, $m, 1)),
                'income'  => (float) Transaction::where('type', 'income')->whereYear('transaction_date', now()->year)->whereMonth('transaction_date', $m)->sum('amount'),
                'expense' => (float) Transaction::where('type', 'expense')->whereYear('transaction_date', now()->year)->whereMonth('transaction_date', $m)->sum('amount'),
            ];
        }

        // Subscription stats
        $subStats = $this->subscriptionStats();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalMessages', 'unreadCount', 'totalIncome', 'totalExpenses',
            'approvedUsers', 'latestUsers', 'pendingUsers', 'monthlyRevenue', 'systemLocked',
            'userGrowthPct', 'weeklyLabels', 'weeklyGrowth', 'weeklyUsers', 'monthlyStats', 'subStats',
            'dbOk'
        ));
    }

    public function users()
    {
        $pendingUsers = User::where('is_approved', false)->latest()->get();
        $allUsers     = User::latest()->paginate(20);

        return view('admin.users', compact('pendingUsers', 'allUsers'));
    }

    public function messages()
    {
        $messages    = ContactMessage::latest()->paginate(20);
        $unreadCount = ContactMessage::where('is_read', false)->count();

        return view('admin.messages', compact('messages', 'unreadCount'));
    }

    public function subscriptions()
    {
        $subStats     = $this->subscriptionStats();
        $adminEmail = env('ADMIN_EMAIL', 'efremfretewahdo@gmail.com');
        $activeUsers  = User::where('is_approved', true)
                            ->where('email', '!=', $adminEmail)
                            ->where(fn($q) => $q->whereNull('plan_expires_at')->orWhere('plan_expires_at', '>', now()))
                            ->orderBy('plan_expires_at')
                            ->get();
        $expiredUsers = User::where('is_approved', true)
                            ->where('plan_expires_at', '<', now())
                            ->where('email', '!=', $adminEmail)
                            ->latest('plan_expires_at')
                            ->get();

        return view('admin.subscriptions', compact('subStats', 'activeUsers', 'expiredUsers'));
    }

    public function approveUser(Request $request, User $user)
    {
        $request->validate(['plan_duration' => 'required|in:1_month,6_months,1_year,lifetime']);

        $expiresAt = match ($request->plan_duration) {
            '1_month'  => now()->addMonth(),
            '6_months' => now()->addMonths(6),
            '1_year'   => now()->addYear(),
            'lifetime' => null,
        };

        $user->update(['is_approved' => true, 'plan_expires_at' => $expiresAt]);

        $label = match ($request->plan_duration) {
            '1_month'  => '1 Month',
            '6_months' => '6 Months',
            '1_year'   => '1 Year',
            'lifetime' => 'Lifetime',
        };

        return back()->with('success', "{$user->name} approved with {$label} plan.");
    }

    public function rejectUser(User $user)
    {
        $user->delete();
        return back()->with('success', 'User rejected and removed.');
    }

    public function lockSystem()
    {
        cache()->put('system_locked', true, now()->addHours(24));
        return back()->with('success', 'System locked for 24 hours.');
    }

    public function unlockSystem()
    {
        cache()->forget('system_locked');
        return back()->with('success', 'System unlocked.');
    }

    private function subscriptionStats(): array
    {
        $adminEmail = env('ADMIN_EMAIL', 'efremfretewahdo@gmail.com');
        $approved   = User::where('is_approved', true)->where('email', '!=', $adminEmail)->get();

        return [
            'annual'  => $approved->filter(fn($u) => $u->plan_expires_at && !$u->plan_expires_at->isPast() && now()->diffInDays($u->plan_expires_at) > 180)->count(),
            'semi'    => $approved->filter(fn($u) => $u->plan_expires_at && !$u->plan_expires_at->isPast() && now()->diffInDays($u->plan_expires_at) > 30 && now()->diffInDays($u->plan_expires_at) <= 180)->count(),
            'trial'   => $approved->filter(fn($u) => $u->plan_expires_at && !$u->plan_expires_at->isPast() && now()->diffInDays($u->plan_expires_at) <= 30)->count(),
            'life'    => $approved->filter(fn($u) => is_null($u->plan_expires_at))->count(),
            'pending' => User::where('is_approved', false)->count(),
        ];
    }
}
