<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        ContactMessage::create($data);

        return back()->with('contact_success', true);
    }

    public function markRead(ContactMessage $message)
    {
        $message->update(['is_read' => true]);
        return back();
    }

    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return back()->with('success', 'Message deleted.');
    }
}
