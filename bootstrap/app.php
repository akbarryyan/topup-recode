<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckMaintenanceMode;
use App\Http\Middleware\RedirectIfAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function() {
            require __DIR__.'/../routes/admin.php';
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(CheckMaintenanceMode::class);
        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitor::class,
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'auth' => Authenticate::class,
            'guest' => RedirectIfAuthenticated::class,
            'duitku.ip' => \App\Http\Middleware\DuitkuIpWhitelist::class,
            'track.visitor' => \App\Http\Middleware\TrackVisitor::class,
        ]);

        // Exclude Duitku callback from CSRF verification (external request)
        $middleware->validateCsrfTokens(except: [
            'payment/duitku/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
