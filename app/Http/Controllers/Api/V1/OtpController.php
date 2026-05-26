<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\OtpNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class OtpController extends Controller
{
    private const OTP_TTL      = 600; // seconds — 10-minute validity window
    private const COOLDOWN_TTL = 60;  // seconds — minimum gap between resends
    private const MAX_ATTEMPTS = 5;   // failed attempts before OTP is invalidated

    /**
     * POST /api/v1/auth/otp/send
     *
     * Generate a cryptographically random 6-digit OTP, store its HMAC-SHA256
     * fingerprint in the cache, and email the plain code to the user.
     *
     * A 60-second per-user cooldown prevents OTP-bombing even if the caller
     * bypasses the global auth rate limiter via different IPs.
     */
    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user         = User::where('email', $request->email)->firstOrFail();
        $baseKey      = "otp:{$user->id}";
        $cooldownKey  = "{$baseKey}:cooldown";

        if (Cache::has($cooldownKey)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'A code was recently sent. Please wait 60 seconds before requesting another.',
            ], 429);
        }

        // random_int() is cryptographically secure; str_pad ensures leading zeros.
        $otp     = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpHash = hash_hmac('sha256', $otp, config('app.key'));

        Cache::put($baseKey,               $otpHash, self::OTP_TTL);
        Cache::put("{$baseKey}:attempts",  0,        self::OTP_TTL);
        Cache::put($cooldownKey,           true,     self::COOLDOWN_TTL);

        $user->notify(new OtpNotification($otp));

        return response()->json([
            'status'  => 'success',
            'message' => 'A 6-digit verification code has been sent to your email address.',
            'data'    => ['expires_in' => self::OTP_TTL],
        ], 200);
    }

    /**
     * POST /api/v1/auth/otp/verify
     *
     * Validate the submitted code against the cached HMAC fingerprint.
     * Uses hash_equals() to prevent timing-based side-channel attacks.
     * The OTP is consumed (deleted) on first successful verification.
     */
    public function verify(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp'   => ['required', 'string', 'digits:6'],
        ]);

        $user       = User::where('email', $request->email)->firstOrFail();
        $baseKey    = "otp:{$user->id}";
        $attemptsKey = "{$baseKey}:attempts";

        $storedHash = Cache::get($baseKey);
        if (! $storedHash) {
            return response()->json([
                'status'  => 'error',
                'message' => 'The verification code has expired or was never issued. Please request a new one.',
            ], 422);
        }

        $attempts = (int) Cache::get($attemptsKey, 0);
        if ($attempts >= self::MAX_ATTEMPTS) {
            Cache::forget($baseKey);
            Cache::forget($attemptsKey);
            return response()->json([
                'status'  => 'error',
                'message' => 'Too many failed attempts. This code has been invalidated. Please request a new one.',
            ], 429);
        }

        $providedHash = hash_hmac('sha256', $request->otp, config('app.key'));

        // hash_equals() prevents timing attacks during comparison.
        if (! hash_equals($storedHash, $providedHash)) {
            Cache::put($attemptsKey, $attempts + 1, self::OTP_TTL);
            $remaining = self::MAX_ATTEMPTS - ($attempts + 1);
            return response()->json([
                'status'  => 'error',
                'message' => "Invalid code. {$remaining} attempt(s) remaining.",
            ], 422);
        }

        // Consume the OTP — it is strictly single-use.
        Cache::forget($baseKey);
        Cache::forget($attemptsKey);

        return response()->json([
            'status'  => 'success',
            'message' => 'Verification successful.',
            'data'    => ['verified' => true, 'email' => $user->email],
        ], 200);
    }
}
