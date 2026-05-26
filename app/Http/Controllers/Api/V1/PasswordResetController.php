<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * POST /api/v1/auth/password/email
     *
     * Send a password reset link via the Laravel Password broker.
     * Always returns HTTP 200 regardless of whether the email exists —
     * this prevents email enumeration attacks.
     */
    public function sendLink(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        // Password::sendResetLink() stores a hashed token in password_reset_tokens
        // and dispatches the PasswordResetLinkSent mail — no extra code needed.
        Password::sendResetLink($request->only('email'));

        return response()->json([
            'status'  => 'success',
            'message' => 'If that email address is registered, a password reset link has been sent.',
        ], 200);
    }

    /**
     * POST /api/v1/auth/password/reset
     *
     * Validate the reset token and update the user's password.
     * The token is single-use — the broker deletes it on success.
     */
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'token'                 => ['required', 'string'],
            'email'                 => ['required', 'email', 'max:255'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password): void {
                // The User model's 'hashed' cast automatically bcrypt-hashes
                // the plain-text value — do NOT wrap in Hash::make() here.
                $user->forceFill([
                    'password'       => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Your password has been reset successfully. You may now log in.',
            ], 200);
        }

        $message = match ($status) {
            Password::INVALID_TOKEN   => 'This reset link is invalid or has expired. Please request a new one.',
            Password::INVALID_USER    => 'No account was found with that email address.',
            Password::RESET_THROTTLED => 'Too many reset attempts. Please wait a moment before trying again.',
            default                   => 'Unable to reset password. Please request a new reset link.',
        };

        return response()->json([
            'status'  => 'error',
            'message' => $message,
        ], 422);
    }
}
