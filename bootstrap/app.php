<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',       // registers all /api/v1/* routes
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Trust all proxies — required for OpenLiteSpeed/CyberPanel to pass
        // the correct HTTPS scheme so CSRF tokens and session cookies are valid.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'admin'        => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'approved'     => \App\Http\Middleware\EnsureUserIsApproved::class,
            'guest'        => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'api.approved' => \App\Http\Middleware\EnsureApiUserIsApproved::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
