<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isPlanExpired()) {
                return redirect()->route('approval.pending')->with('plan_expired', true);
            }
            if (!$user->isApproved()) {
                return redirect()->route('approval.pending');
            }
        }
        return $next($request);
    }
}
