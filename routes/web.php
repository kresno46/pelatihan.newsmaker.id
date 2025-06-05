<?php

use App\Http\Controllers\EbookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('ebook')->group(function () {
        Route::get('/', [EbookController::class, 'index'])->name('ebook.index');

        Route::get('/create', [EbookController::class, 'create'])->middleware('is_admin:Admin')->name('ebook.create');
        Route::post('/', [EbookController::class, 'store'])->middleware('is_admin:Admin')->name('ebook.store');
        Route::get('/{ebook}/edit', [EbookController::class, 'edit'])->middleware('is_admin:Admin')->name('ebook.edit');
        Route::put('/{ebook}', [EbookController::class, 'update'])->middleware('is_admin:Admin')->name('ebook.update');
        Route::delete('/{ebook}', [EbookController::class, 'destroy'])->middleware('is_admin:Admin')->name('ebook.destroy');

        Route::get('/{ebook}', [EbookController::class, 'show'])->name('ebook.show');
    });

    Route::prefix('quiz')->group(function () {
        Route::get('/', [QuizController::class, 'index'])->name('quiz.index');
        Route::post('/', [QuizController::class, 'store'])->middleware('is_admin:Admin')->name('quiz.store');
        Route::get('/create', [QuizController::class, 'create'])->middleware('is_admin:Admin')->name('quiz.create');
        Route::put('/{id}', [QuizController::class, 'update'])->middleware('is_admin:Admin')->name('quiz.update');
        Route::get('/{id}/edit', [QuizController::class, 'edit'])->middleware('is_admin:Admin')->name('quiz.edit');
        Route::delete('/{id}', [QuizController::class, 'destroy'])->middleware('is_admin:Admin')->name('quiz.destroy');

        // PENTING: Tambahkan route soal di atas
        Route::get('/{slug}/tambah-soal', [QuestionController::class, 'create'])->middleware('is_admin:Admin')->name('question.create');
        Route::post('/{slug}/soal', [QuestionController::class, 'store'])->middleware('is_admin:Admin')->name('question.store');
        Route::put('/{slug}/soal/{id}/update', [QuestionController::class, 'update'])->middleware('is_admin:Admin')->name('question.update');
        Route::get('/{slug}/soal/{id}', [QuestionController::class, 'edit'])->middleware('is_admin:Admin')->name('question.edit');
        Route::delete('/{slug}/soal/{id}', [QuestionController::class, 'destroy'])->middleware('is_admin:Admin')->name('question.destroy');

        // HARUS DI BAWAH SEMUA
        Route::get('/{slug}', [QuizController::class, 'show'])->middleware('is_admin:Admin')->name('quiz.show');
    });

    Route::get('/cert', function () {
        return view('cert');
    })->name('profile.show');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';
