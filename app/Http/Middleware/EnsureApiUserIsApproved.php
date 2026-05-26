<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiUserIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user->isApproved()) {
            $message = $user->isPlanExpired()
                ? 'Your plan has expired. Please renew to continue.'
                : 'Your account is pending admin approval.';

            return response()->json([
                'status'  => 'error',
                'message' => $message,
            ], 403);
        }

        return $next($request);
    }
}
