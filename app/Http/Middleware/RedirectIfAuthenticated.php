<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Hardcoded: super admin always lands on control center
                if ($user->email === env('ADMIN_EMAIL', 'efremfretewahdo@gmail.com')) {
                    return redirect()->route('admin.dashboard');
                }

                // Approved user → dashboard
                if ($user->is_approved) {
                    return redirect()->route('dashboard');
                }

                // Unapproved → waiting room
                return redirect()->route('approval.pending');
            }
        }

        return $next($request);
    }
}
