<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PostTestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SertifikatController;
use App\Http\Controllers\SummernoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserCleanupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::prefix('ebook')->middleware(['auth', 'verified'])->group(function () {

        // 📁 Folder Routes
        Route::get('/', [FolderController::class, 'index'])->name('folder.index');

        Route::middleware('is_admin:Admin')->group(function () {
            Route::get('/create', [FolderController::class, 'create'])->name('folder.create');
            Route::post('/store', [FolderController::class, 'store'])->name('folder.store');
            Route::get('/{folderSlug}/edit', [FolderController::class, 'edit'])->name('folder.edit');
            Route::put('/{folderSlug}', [FolderController::class, 'update'])->name('folder.update');
            Route::delete('/{folderSlug}', [FolderController::class, 'destroy'])->name('folder.destroy');
        });

        // 📚 Ebook & Quiz Routes
        Route::prefix('{folderSlug}')->middleware('profile.complete')->group(function () {

            // 📄 List eBook dalam Folder
            Route::get('/', [EbookController::class, 'index'])->name('ebook.index');

            // ➕ Admin - Kelola eBook
            Route::middleware('is_admin:Admin')->group(function () {
                Route::get('/create', [EbookController::class, 'create'])->name('ebook.create');
                Route::post('/store', [EbookController::class, 'store'])->name('ebook.store');
                Route::get('/{ebookSlug}/edit', [EbookController::class, 'edit'])->name('ebook.edit');
                Route::put('/{ebookSlug}/update', [EbookController::class, 'update'])->name('ebook.update');
                Route::delete('/{ebookSlug}/delete', [EbookController::class, 'destroy'])->name('ebook.destroy');
            });

            // 📄 Tampil Detail eBook
            Route::get('/{ebookSlug}', [EbookController::class, 'show'])->name('ebook.show');

            // 📝 Quiz & Post-Test (langsung di bawah {folderSlug}/{ebookSlug})
            Route::middleware('is_admin:Admin')->group(function () {
                Route::get('/{ebookSlug}/quiz', [QuizController::class, 'index'])->name('quiz.index');
                Route::post('/{ebookSlug}/quiz/store', [QuizController::class, 'store'])->name('quiz.store');
                Route::put('/{ebookSlug}/quiz/{sessionId}/update', [QuizController::class, 'update'])->name('quiz.update');
                Route::get('/{ebookSlug}/quiz/{sessionId}/add-question', [QuizController::class, 'addQuestionShow'])->name('quiz.add-question-index');
                Route::post('/{ebookSlug}/quiz/{sessionId}/add-question', [QuizController::class, 'addQuestionStore'])->name('quiz.add-question-store');
                Route::delete('/{ebookSlug}/quiz/{sessionId}/question/{questionId}', [QuizController::class, 'deleteQuestion'])->name('quiz.delete-question');
                Route::get('/{ebookSlug}/quiz/{sessionId}/question/{questionId}/edit', [QuizController::class, 'editQuestion'])->name('quiz.edit-question');
                Route::put('/{ebookSlug}/quiz/{sessionId}/question/{questionId}', [QuizController::class, 'updateQuestion'])->name('quiz.update-question');
            });

            // 📝 Post-Test (User)
            Route::post('/{ebookSlug}/post-test/{session}/submit', [PostTestController::class, 'submitQuiz'])->name('posttest.submit');
            Route::get('/{ebookSlug}/post-test/{session}', [PostTestController::class, 'showQuiz'])->name('posttest.show');
            Route::get('/{ebookSlug}/post-test/result/{resultId}', [PostTestController::class, 'showResult'])->name('posttest.result');
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
        Route::get('/unduh/{folderSlug}', [SertifikatController::class, 'generateCertificate'])->name('sertifikat.generate');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('cert', function () {
        return view('emails.certificate');
    });

    Route::get('/hapus-akun-tidak-verifikasi', [UserCleanupController::class, 'deleteUnverifiedUsers'])->name('user.delete');
});

require __DIR__ . '/auth.php';
