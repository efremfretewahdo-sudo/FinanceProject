<?php

use App\Http\Controllers\Api\V1\AiChatController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\OtpController;
use App\Http\Controllers\Api\V1\PasswordResetController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes  —  /api/v1/*
|--------------------------------------------------------------------------
|
| All routes are automatically prefixed with /api by bootstrap/app.php.
| We add a further /v1 group for explicit versioning.
|
| Authentication : Laravel Sanctum  — Bearer token in Authorization header
| Rate limiters  : Defined in AppServiceProvider::configureRateLimiters()
|
| Mobile client must include on every request:
|   Authorization: Bearer <token>
|   Accept: application/json
|   Content-Type: application/json
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // =========================================================================
    // PUBLIC ENDPOINTS — no Bearer token required
    // Shared 'throttle:auth' limiter: 10 req/min per IP.
    // Guards against brute-force on login, password reset, and OTP flows.
    // =========================================================================

    Route::middleware('throttle:auth')->group(function () {

        // ── Core authentication ───────────────────────────────────────────────
        Route::post('auth/login', [AuthController::class, 'login'])
             ->name('auth.login');

        // ── Password reset — two-step flow ────────────────────────────────────
        //
        //  Step 1 — POST /api/v1/auth/password/email
        //    Body:  { "email": "user@example.com" }
        //    Sends a signed reset link to the address (Laravel Password broker).
        //    Always responds 200 to prevent email enumeration.
        //
        //  Step 2 — POST /api/v1/auth/password/reset
        //    Body:  { "token": "...", "email": "...",
        //             "password": "...", "password_confirmation": "..." }
        //    Validates the token, updates the password, invalidates the token.

        Route::post('auth/password/email', [PasswordResetController::class, 'sendLink'])
             ->name('auth.password.email');

        Route::post('auth/password/reset', [PasswordResetController::class, 'reset'])
             ->name('auth.password.reset');

        // ── OTP / Two-Factor verification ─────────────────────────────────────
        //
        //  Step 1 — POST /api/v1/auth/otp/send
        //    Body:  { "email": "user@example.com" }
        //    Generates a secure 6-digit code, caches its HMAC hash for 10 min,
        //    and emails it via OtpNotification. A 60-second per-user cooldown
        //    is enforced independently of the outer rate limiter.
        //
        //  Step 2 — POST /api/v1/auth/otp/verify
        //    Body:  { "email": "user@example.com", "otp": "123456" }
        //    Verifies the HMAC hash against the cache. Strictly one-time use;
        //    the entry is deleted on success. Locked out after 5 failed attempts.

        Route::post('auth/otp/send',   [OtpController::class, 'send'])
             ->name('auth.otp.send');

        Route::post('auth/otp/verify', [OtpController::class, 'verify'])
             ->name('auth.otp.verify');

    });

    // =========================================================================
    // PROTECTED ENDPOINTS — valid Sanctum Bearer token required
    //
    // Middleware stack:
    //   auth:sanctum  — validates token, binds $request->user()
    //   api.approved  — rejects accounts that are pending or have expired plans
    //   throttle:api  — 60 req/min per authenticated user (30/min per IP fallback)
    // =========================================================================

    Route::middleware(['auth:sanctum', 'api.approved', 'throttle:api'])->group(function () {

        // ── Auth management ───────────────────────────────────────────────────
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('auth/me',      [AuthController::class, 'me'])->name('auth.me');

        // ── Dashboard — balance, income, expenses, recent transactions ─────────
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ── Transactions — tighter write cap (20 req/min per user) ────────────
        Route::middleware('throttle:transactions')->group(function () {
            Route::get('transactions',  [TransactionController::class, 'index'])
                 ->name('transactions.index');
            Route::post('transactions', [TransactionController::class, 'store'])
                 ->name('transactions.store');
        });

        // ── AI Chat ───────────────────────────────────────────────────────────
        // TEMPORARILY DISABLED FOR LIVE VPS DEPLOYMENT
        // The AI chat endpoint is a rule-based stub that has not been validated
        // for production load or latency SLAs. Re-enable after stress testing.
        //
        // Route::post('ai/chat', [AiChatController::class, 'chat'])->name('ai.chat');

    });

});
