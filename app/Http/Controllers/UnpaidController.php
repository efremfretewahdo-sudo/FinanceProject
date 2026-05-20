<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use App\Models\UnpaidItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnpaidController extends Controller
{
    public function index()
    {
        $items    = UnpaidItem::where('user_id', Auth::id())->with('member')->latest('due_date')->paginate(15);
        $members  = Member::where('user_id', Auth::id())->where('status','active')->get();
        $totalDue     = UnpaidItem::where('user_id', Auth::id())->whereIn('status', ['unpaid', 'partial'])->sum('amount_due');
        $overdueCount = UnpaidItem::where('user_id', Auth::id())->where('status', 'unpaid')->where('due_date', '<', now()->toDateString())->count();

        // Members who haven't had a paid payment in the last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30)->toDateString();
        $recentlyPaidIds = Payment::where('user_id', Auth::id())
            ->where('status', 'paid')
            ->where('payment_date', '>=', $thirtyDaysAgo)
            ->pluck('member_id')
            ->unique();
        $lateMembers = Member::where('user_id', Auth::id())
            ->where('status', 'active')
            ->whereNotIn('id', $recentlyPaidIds)
            ->get();

        return view('pages.unpaid', compact('items', 'members', 'totalDue', 'overdueCount', 'lateMembers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'debtor_name' => 'required|string|max:255',
            'amount_due'  => 'required|numeric|min:0.01',
            'due_date'    => 'required|date',
            'member_id'   => 'nullable|exists:members,id',
            'description' => 'nullable|string',
            'status'      => 'required|in:unpaid,partial,paid',
        ]);
        $data['user_id'] = Auth::id();
        UnpaidItem::create($data);
        return back()->with('success', 'ዘይተኸፈለ ዕዳ ተመዝጊቡ ኣሎ (Unpaid item recorded).');
    }

    public function markPaid(UnpaidItem $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);
        $item->update(['status' => 'paid']);
        return back()->with('success', 'ከፊሉ ኣሎ ተባሂሉ ተሓቢሩ (Marked as paid).');
    }

    public function destroy(UnpaidItem $item)
    {
        abort_if($item->user_id !== Auth::id(), 403);
        $item->delete();
        return back()->with('success', 'ዕዳ ተሰሪዙ ኣሎ (Item deleted).');
    }

    public function approveMember(Request $request, Member $member)
    {
        abort_if($member->user_id !== Auth::id(), 403);
        $data = $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,check,other',
        ]);
        Payment::create([
            'user_id'        => Auth::id(),
            'member_id'      => $member->id,
            'payer_name'     => $member->full_name,
            'amount'         => $data['amount'],
            'payment_date'   => now()->toDateString(),
            'payment_method' => $data['payment_method'],
            'status'         => 'paid',
            'notes'          => 'ፈቒዱ ካብ ናይ ዘይተኸፍለ ዝርዝር (Approved from unpaid list)',
        ]);
        return back()->with('success', $member->full_name . ' — ክፍሊት ተቐቢሉ (Payment approved).');
    }
}
