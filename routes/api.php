<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// API
Route::middleware('bearer.token')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
});
