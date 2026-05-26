<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Console\Events\CommandStarting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Commands that must never run on a production database.
     * Blocking happens via CommandStarting event — fires before any command
     * body executes and cannot be bypassed with --force or --no-interaction.
     */
    private const PRODUCTION_BLOCKED_COMMANDS = [
        'migrate:fresh',   // drops every table, then re-migrates  → total data wipe
        'migrate:reset',   // rolls back every migration            → total data wipe
        'db:wipe',         // drops all tables without down()       → total data wipe
        'db:seed',         // can overwrite / duplicate live data   → data corruption
    ];

    public function register(): void {}

    public function boot(): void
    {
        $this->guardProductionDatabase();
        $this->configureRateLimiters();
    }

    // ── Production database guard ─────────────────────────────────────────────

    /**
     * Intercept dangerous Artisan commands before they execute on production.
     * Uses exit(1) so the block is absolute — no flag can override it.
     */
    private function guardProductionDatabase(): void
    {
        if (! $this->app->isProduction()) {
            return;
        }

        Event::listen(CommandStarting::class, function (CommandStarting $event): void {
            if (! in_array($event->command, self::PRODUCTION_BLOCKED_COMMANDS, true)) {
                return;
            }

            // Write directly to stderr so the message is visible even when
            // stdout is piped or redirected in CI/CD pipelines.
            $line = str_repeat('═', 62);
            fwrite(STDERR, PHP_EOL);
            fwrite(STDERR, "  ╔{$line}╗" . PHP_EOL);
            fwrite(STDERR, "  ║  🛑  PRODUCTION DATABASE GUARD — COMMAND BLOCKED         ║" . PHP_EOL);
            fwrite(STDERR, "  ╠{$line}╣" . PHP_EOL);
            fwrite(STDERR, "  ║  Command  : {$event->command}" . PHP_EOL);
            fwrite(STDERR, "  ║  Reason   : Permanently prohibited on APP_ENV=production  ║" . PHP_EOL);
            fwrite(STDERR, "  ║  Safe alt : php artisan migrate --force                   ║" . PHP_EOL);
            fwrite(STDERR, "  ╚{$line}╝" . PHP_EOL);
            fwrite(STDERR, PHP_EOL);

            exit(1); // Hard exit — no framework path can bypass this.
        });
    }

    // ── Rate limiters ─────────────────────────────────────────────────────────

    private function configureRateLimiters(): void
    {
        // Auth endpoints (login, OTP, password reset) — 10 req/min per IP
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->ip())
                ->response(fn () => response()->json([
                    'status'  => 'error',
                    'message' => 'Too many attempts. Please wait a minute before trying again.',
                ], 429));
        });

        // Authenticated API calls — 60 req/min per user (30/min per IP fallback)
        RateLimiter::for('api', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(60)->by($request->user()->id)
                : Limit::perMinute(30)->by($request->ip());
        });

        // Transaction writes — tighter cap (20 req/min per user)
        RateLimiter::for('transactions', function (Request $request) {
            return $request->user()
                ? Limit::perMinute(20)->by($request->user()->id)
                : Limit::perMinute(10)->by($request->ip());
        });
    }
}
