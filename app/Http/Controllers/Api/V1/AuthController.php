<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Issue a Sanctum token for the mobile client.
     *
     * POST /api/v1/auth/login
     * Body: { email, password, device_name }
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'       => ['required', 'email'],
            'password'    => ['required', 'string'],
            'device_name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if (! $user->isApproved()) {
            return response()->json([
                'status'  => 'error',
                'message' => $user->isPlanExpired()
                    ? 'Your plan has expired. Please renew to continue.'
                    : 'Your account is pending admin approval.',
            ], 403);
        }

        // Revoke any existing token for this device (prevents token sprawl)
        $user->tokens()->where('name', $request->device_name)->delete();

        $token = $user->createToken(
            $request->device_name,
            ['*'],
            now()->addDays(30)
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Login successful.',
            'data'    => [
                'user' => [
                    'id'          => $user->id,
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'avatar'      => $user->avatar,
                    'is_admin'    => $user->isAdmin(),
                    'is_approved' => $user->isApproved(),
                ],
                'token'      => $token->plainTextToken,
                'expires_at' => $token->accessToken->expires_at?->toIso8601String(),
            ],
        ], 200);
    }

    /**
     * Revoke the current token (logout from this device only).
     *
     * POST /api/v1/auth/logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully.',
            'data'    => null,
        ], 200);
    }

    /**
     * Return the authenticated user's profile.
     *
     * GET /api/v1/auth/me
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'             => $user->id,
                'name'           => $user->name,
                'email'          => $user->email,
                'avatar'         => $user->avatar,
                'is_admin'       => $user->isAdmin(),
                'is_approved'    => $user->isApproved(),
                'plan_expires_at'=> $user->plan_expires_at?->toDateString(),
            ],
        ], 200);
    }
}
