<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function index()
    {
        $members = Member::where('user_id', Auth::id())->latest()->paginate(10);
        $stats = [
            'total'  => Member::where('user_id', Auth::id())->count(),
            'active' => Member::where('user_id', Auth::id())->where('status', 'active')->count(),
        ];
        return view('pages.members', compact('members', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:30',
            'zone'      => 'nullable|string|max:100',
        ]);
        $data['user_id']         = Auth::id();
        $data['membership_type'] = 'standard';
        $data['status']          = 'active';
        $data['joined_date']     = now()->toDateString();

        Member::create($data);
        return back()->with('success', 'Member added successfully.');
    }

    public function update(Request $request, Member $member)
    {
        abort_if($member->user_id !== Auth::id(), 403);
        $data = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'nullable|string|max:30',
            'zone'      => 'nullable|string|max:100',
        ]);
        $member->update($data);
        return back()->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        abort_if($member->user_id !== Auth::id(), 403);
        $member->delete();
        return back()->with('success', 'Member removed.');
    }
}
