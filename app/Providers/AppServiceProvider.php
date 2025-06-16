<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\PostTestSession;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Binding route parameter 'session' ke model PostTestSession
        // Route::model('session', PostTestSession::class);
    }
}
