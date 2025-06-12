<?php

use App\Http\Controllers\EbookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('ebook')->group(function () {
        // e-Book
        Route::get('/', [EbookController::class, 'index'])->name('ebook.index');

        Route::middleware('is_admin:Admin')->group(function () {
            Route::post('/store', [EbookController::class, 'store'])->name('ebook.store');
            Route::get('/create', [EbookController::class, 'create'])->name('ebook.create');
            Route::put('/{id}', [EbookController::class, 'update'])->name('ebook.update');
            Route::get('/{slug}/edit', [EbookController::class, 'edit'])->name('ebook.edit');
            Route::delete('/{id}', [EbookController::class, 'destroy'])->name('ebook.destroy');
        });

        Route::get('/{slug}', [EbookController::class, 'show'])->name('ebook.show');

        // Kuis
        Route::prefix('{slug}')->group(function () {
            Route::post('/quiz/store', [QuizController::class, 'store'])->name('quiz.store');
            Route::put('/quiz/{sessionId}/update', [QuizController::class, 'update'])->name('quiz.update');
            Route::get('/quiz/{sessionId}/add-question', [QuizController::class, 'addQuestionShow'])->name('quiz.add-question-index');
            Route::post('/quiz/{sessionId}/add-question', [QuizController::class, 'addQuestionStore'])->name('quiz.add-question-store');


            Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
        });
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
