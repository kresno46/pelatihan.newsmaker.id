<?php

use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\EnsureProfileIsComplete;
use App\Http\Middleware\EnsureUserHasAbsensi;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_admin' => RoleMiddleware::class,
            'profile.complete' => EnsureProfileIsComplete::class,
            'absensi' => EnsureUserHasAbsensi::class,
            'bearer.token' => \App\Http\Middleware\BearerTokenMiddleware::class,
            'check.patl' => \App\Http\Middleware\CheckPATLAccess::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
