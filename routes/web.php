  <?php

use App\Http\Controllers\AbsensiAdminController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JadwalAbsensiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanSertifikatController;
use App\Http\Controllers\PostTestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\SummernoteController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserCleanupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebviewController;
use App\Models\Absensi;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/tools/{tool}', [WebviewController::class, 'show'])->name('webview.show');

    Route::prefix('post-test')->middleware('auth', 'is_admin:Admin')->group(function () {
        // routes sesi yang sudah kamu punya
        Route::get('/', [QuizController::class, 'index'])->name('posttest.index');
        Route::get('/tambah', [QuizController::class, 'create'])->name('posttest.create');
        Route::post('/', [QuizController::class, 'store'])->name('posttest.store');

        Route::get('/{session}/edit', [QuizController::class, 'edit'])->name('posttest.edit');
        Route::put('/{session}', [QuizController::class, 'update'])->name('posttest.update');
        Route::delete('/{session}', [QuizController::class, 'destroy'])->name('posttest.destroy');
        Route::post('/toggle-status/{slug}', [PostTestController::class, 'toggleStatus'])->name('posttest.toggle');

        // REPORT
        Route::get('/{session:slug}/report', [QuizController::class, 'report'])->name('posttest.report');
        // (opsional) export CSV
        Route::get('/{session:slug}/report/export', [QuizController::class, 'reportExport'])->name('posttest.report.export');
        Route::delete('/{session:slug}/report/delete-all-failed', [QuizController::class, 'deleteAllFailed'])->name('posttest.report.deleteAllFailed');
        Route::delete('/{session:slug}/report/{result}', [QuizController::class, 'deleteResult'])->name('posttest.report.delete');

        // === nested: /post-test/{session}/edit/question ===
        Route::prefix('{session}/edit')->group(function () {
            Route::post('/question', [PostTestController::class, 'questionStore'])
                ->name('question.store');
            Route::put('/question/{question}', [PostTestController::class, 'questionUpdate'])
                ->name('question.update');
        });
        Route::delete('/question/{question}', [PostTestController::class, 'questionDestroy'])
            ->name('quiz.delete');
    });

    Route::prefix('posttest')->name('post-test.')->middleware('profile.complete')->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('index');
        Route::middleware(['absensi', 'CheckPATLAccess'])->group(function () {
            Route::get('/{slug}', [TestController::class, 'showQuiz'])->name('show');
            Route::post('/{slug}/submit', [TestController::class, 'submitQuiz'])->name('submit');
        });
        Route::get('/result/{result}', [TestController::class, 'showResult'])->name('result');
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
            Route::post('/{id}/verify', [UserController::class, 'verify'])->name('trainer.verify');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('trainer.destroy');
        });
    });

    Route::middleware('is_admin:Admin')->group(function () {
        Route::prefix('laporan')->group(function () {
            Route::prefix('post-test')->group(function () {
                Route::get('/', [LaporanController::class, 'index'])->name('laporan.index');
                Route::get('/{id}/show', [LaporanController::class, 'show'])->name('laporan.show');
                Route::delete('/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');
            });

            Route::prefix('absensi')->group(function () {
                Route::get('/', [JadwalAbsensiController::class, 'index'])->name('absensi.index');
                Route::get('/create', [JadwalAbsensiController::class, 'create'])->name('absensi.create');
                Route::post('/tambah', [JadwalAbsensiController::class, 'store'])->name('absensi.store');
                Route::get('/{id}/edit', [JadwalAbsensiController::class, 'edit'])->name('absensi.edit');
                Route::post('/{id}/toggle', [JadwalAbsensiController::class, 'toggle'])->name('absensi.toggle');
                Route::put('/{id}/update', [JadwalAbsensiController::class, 'update'])->name('absensi.update');

                Route::delete('/{id}/hapus', [JadwalAbsensiController::class, 'destroy'])->name('absensi.destroy');

                Route::prefix('{idJadwal}')->group(function () {
                    Route::get('/', [AbsensiAdminController::class, 'indexAdmin'])->name('absensiAdmin.index');
                    Route::get('/pdf', [AbsensiAdminController::class, 'downloadPdf'])->name('absensi.downloadPdf');
                    Route::get('/excel', [AbsensiAdminController::class, 'downloadExcel'])->name('absensi.downloadExcel');
                    Route::get('/pdf-per-cabang', [AbsensiAdminController::class, 'downloadPdfPerCabang'])->name('absensi.downloadPdfPerCabang');
                    Route::get('/excel-per-cabang', [AbsensiAdminController::class, 'downloadExcelPerCabang'])->name('absensi.downloadExcelPerCabang');
                    Route::delete('/{idAbsensi}/delete', [AbsensiAdminController::class, 'delete'])->name('absensiAdmin.delete');
                });
            });

            Route::prefix('sertifikat')->group(function () {
                Route::get('/', [LaporanSertifikatController::class, 'index'])->name('LaporanSertifikat.index');
                Route::get('/export', [LaporanSertifikatController::class, 'export'])->name('LaporanSertifikat.export');
                Route::get('/export-per-cabang', [LaporanSertifikatController::class, 'exportPerCabang'])->name('LaporanSertifikat.exportPerCabang');
                Route::delete('/{id}/delete', [LaporanSertifikatController::class, 'destroy'])->name('LaporanSertifikat.destroy');
            });
        });
    });

    // Absensi
    Route::prefix('absensi')->middleware('profile.complete')->group(function () {
        Route::get('/', [AbsensiController::class, 'indexAbsensi'])->name('AbsensiUser.index');
        Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('AbsensiUser.store');
    });

    Route::get('/sertifikat', [\App\Http\Controllers\SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('/sertifikat/{id}/download', [\App\Http\Controllers\SertifikatController::class, 'generateCertificate'])->name('sertifikat.download');

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

require __DIR__.'/auth.php';
