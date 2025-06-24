<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PostTestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SummernoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('ebook')->middleware('profile.complete')->group(function () {
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
            Route::middleware('is_admin:Admin')->group(function () {
                Route::get('/quiz', [QuizController::class, 'index'])->name('quiz.index');
                Route::post('/quiz/store', [QuizController::class, 'store'])->name('quiz.store');
                Route::put('/quiz/{sessionId}/update', [QuizController::class, 'update'])->name('quiz.update');
                Route::get('/quiz/{sessionId}/add-question', [QuizController::class, 'addQuestionShow'])->name('quiz.add-question-index');
                Route::post('/quiz/{sessionId}/add-question', [QuizController::class, 'addQuestionStore'])->name('quiz.add-question-store');
                Route::delete('quiz/{sessionId}/question/{questionId}', [QuizController::class, 'deleteQuestion'])->name('quiz.delete-question');
                Route::get('quiz/{sessionId}/question/{questionId}/edit', [QuizController::class, 'editQuestion'])
                    ->name('quiz.edit-question');
                Route::put('quiz/{sessionId}/question/{questionId}', [QuizController::class, 'updateQuestion'])
                    ->name('quiz.update-question');
                Route::post('/summernote/upload', [SummernoteController::class, 'upload'])->name('summernote.upload');
                Route::post('/summernote/delete', [SummernoteController::class, 'delete'])->name('summernote.delete');
            });
            Route::post('/post-test/{session}/submit', [PostTestController::class, 'submitQuiz'])->name('posttest.submit');
            Route::get('/post-test/{session}', [PostTestController::class, 'showQuiz'])->name('posttest.show');
        });
    });

    // Summmernote Controller
    Route::middleware('is_admin:Admin', 'profile.complete')->group(function () {
        Route::post('/summernote/upload', [SummernoteController::class, 'upload'])->name('summernote.upload');
        Route::post('/summernote/delete', [SummernoteController::class, 'delete'])->name('summernote.delete');
    });

    // Hasil Post Test
    Route::get('/post-test/result/{result}', [PostTestController::class, 'showResult'])->name('posttest.result');

    // Riwayat
    Route::prefix('riwayat')->group(function () {
        Route::get('/', [RiwayatController::class, 'index'])->name('riwayat.index');
        Route::get('/{id}', [RiwayatController::class, 'show'])->name('riwayat.show');
    });

    // Admin
    Route::middleware('is_admin:Admin')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('admin.index');
            Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
            Route::post('/store', [AdminController::class, 'store'])->name('admin.store');
            Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
            Route::put('/{id}', [AdminController::class, 'update'])->name('admin.update');
            Route::delete('/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
        });
    });

    // Trainer
    Route::middleware('is_admin:Admin')->group(function () {
        Route::prefix('trainer')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('trainer.index');
            Route::get('/create', [UserController::class, 'create'])->name('trainer.create');
            Route::post('/store', [UserController::class, 'store'])->name('trainer.store');
            Route::get('/{id}/edit', [UserController::class, 'edit'])->name('trainer.edit');
            Route::put('/{id}', [UserController::class, 'update'])->name('trainer.update');
            Route::get('/{id}/show', [UserController::class, 'show'])->name('trainer.show');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('trainer.destroy');
        });
    });

    Route::middleware('is_admin:Admin')->group(function () {
        Route::prefix('laporan')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
            Route::get('/{id}/show', [LaporanController::class, 'show'])->name('laporan.show');
            Route::delete('/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
        });
    });

    Route::prefix('email')->middleware('is_admin:Admin')->group(function () {
        Route::get('/', [EmailController::class, 'index'])->name('email.index');
    });

    Route::prefix('sertifikat')->group(function () {
        Route::get('/', [SertifikatController::class, 'index'])->name('sertifikat.index');
        Route::get('/sertifikat/unduh/{batch}', [SertifikatController::class, 'download'])->name('sertifikat.download');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('cert', function () {
        return view('sertifikat.certificate');
    });
});

require __DIR__ . '/auth.php';
