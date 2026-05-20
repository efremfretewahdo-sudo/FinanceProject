<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || Auth::user()->email !== env('ADMIN_EMAIL', 'efremfretewahdo@gmail.com')) {
            abort(403, 'Access denied. Admin only.');
        }
        return $next($request);
    }
}
