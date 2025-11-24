<?php

use App\Http\Middleware\BearerTokenMiddleware;
use App\Http\Middleware\CheckPATLAccess;
use App\Http\Middleware\EnsureProfileIsComplete;
use App\Http\Middleware\EnsureUserHasAbsensi;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'is_admin' => RoleMiddleware::class,
            'profile.complete' => EnsureProfileIsComplete::class,
            'absensi' => EnsureUserHasAbsensi::class,
            'CheckPATLAccess' => CheckPATLAccess::class,
            'bearer.token' => BearerTokenMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
