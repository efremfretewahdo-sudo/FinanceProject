<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OtpWebController extends Controller
{
    /**
     * GET /otp/verify
     *
     * Renders the OTP entry page. The email address is picked up from:
     *   1. ?email= query param (when linked from forgot-password or registration)
     *   2. otp_email session key (set by server-side flows)
     *   3. Empty string → the page shows the email input first
     *
     * The ?redirect= param (relative URLs only) controls where the user lands
     * after a successful verification. Defaults to the login page.
     */
    public function show(Request $request): View
    {
        return view('auth.otp-verify', [
            'email'    => $request->query('email', session('otp_email', '')),
            'redirect' => $request->query('redirect', ''),
        ]);
    }
}
