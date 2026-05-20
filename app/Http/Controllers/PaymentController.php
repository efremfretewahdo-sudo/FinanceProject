<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $payments    = Payment::where('user_id', Auth::id())->with('member')->latest('payment_date')->paginate(15);
        $members     = Member::where('user_id', Auth::id())->where('status', 'active')->orderBy('full_name')->get();
        $totalPaid   = Payment::where('user_id', Auth::id())->where('status', 'paid')->sum('amount');
        $unpaidCount = Payment::where('user_id', Auth::id())->where('status', 'unpaid')->count();
        return view('pages.payments', compact('payments', 'members', 'totalPaid', 'unpaidCount'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'member_id'      => 'required|exists:members,id',
            'amount'         => 'required|numeric|min:0.01',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,check,other',
            'status'         => 'required|in:paid,unpaid',
            'notes'          => 'nullable|string',
        ]);

        $member             = Member::findOrFail($data['member_id']);
        $data['payer_name'] = $member->full_name;
        $data['user_id']    = Auth::id();

        $payment = Payment::create($data);
        $payment->syncTransaction();

        if ($data['status'] === 'paid') {
            return redirect()->route('payments.receipt', $payment);
        }
        return back()->with('success', 'ክፍሊት ብዓወት ተመዝጊቡ ኣሎ።');
    }

    public function markPaid(Payment $payment)
    {
        abort_if($payment->user_id !== Auth::id(), 403);
        $payment->update(['status' => 'paid']);
        $payment->syncTransaction();
        return redirect()->route('payments.receipt', $payment);
    }

    public function update(Request $request, Payment $payment)
    {
        abort_if($payment->user_id !== Auth::id(), 403);
        $data = $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_date'   => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,check,other',
            'status'         => 'required|in:paid,unpaid',
            'notes'          => 'nullable|string',
        ]);
        $payment->update($data);
        $payment->syncTransaction();
        return back()->with('success', 'ክፍሊት ተሓዲሱ ኣሎ (Payment updated).');
    }

    public function receipt(Payment $payment)
    {
        abort_if($payment->user_id !== Auth::id(), 403);
        $user = Auth::user();
        return view('pages.payment-receipt', compact('payment', 'user'));
    }

    public function destroy(Payment $payment)
    {
        abort_if($payment->user_id !== Auth::id(), 403);
        $payment->removeTransaction();
        $payment->delete();
        return back()->with('success', 'ክፍሊት ተሰሪዙ ኣሎ።');
    }
}
