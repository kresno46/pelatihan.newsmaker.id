<?php

use App\Http\Controllers\EbookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('ebook')->group(function () {
        Route::get('/', [EbookController::class, 'index'])->name('ebook.index');

        // Route spesifik harus duluan
        Route::get('/create', [EbookController::class, 'create'])->middleware('is_admin:Admin')->name('ebook.create');
        Route::post('/', [EbookController::class, 'store'])->middleware('is_admin:Admin')->name('ebook.store');
        Route::get('/{ebook}/edit', [EbookController::class, 'edit'])->middleware('is_admin:Admin')->name('ebook.edit');
        Route::put('/{ebook}', [EbookController::class, 'update'])->middleware('is_admin:Admin')->name('ebook.update');
        Route::delete('/{ebook}', [EbookController::class, 'destroy'])->middleware('is_admin:Admin')->name('ebook.destroy');

        // Route dinamis paling bawah
        Route::get('/{ebook}', [EbookController::class, 'show'])->name('ebook.show');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
