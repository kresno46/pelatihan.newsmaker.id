  <?php

use App\Http\Controllers\AbsensiAdminController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApupptAbsensiAdminController;
use App\Http\Controllers\ApupptAbsensiUserController;
use App\Http\Controllers\ApupptEbookController;
use App\Http\Controllers\ApupptEbookFolderController;
use App\Http\Controllers\ApupptEdukasiEbookController;
use App\Http\Controllers\ApupptFeatureController;
use App\Http\Controllers\ApupptJadwalAbsensiController;
use App\Http\Controllers\ApupptLaporanSertifikatController;
use App\Http\Controllers\ApupptPostTestController;
use App\Http\Controllers\ApupptQuizController;
use App\Http\Controllers\ApupptSertifikatController;
use App\Http\Controllers\ApupptTestController;
use App\Http\Controllers\EdukasiEbookController;
use App\Http\Controllers\EdukasiOutlookController;
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

    Route::post('/apuppt/feature/toggle', [ApupptFeatureController::class, 'toggle'])
        ->middleware('is_admin:Admin,Admin APUPPT')
        ->name('apuppt.feature.toggle');

    Route::middleware('profile.complete')->group(function () {
        Route::get('/edukasi/ebook', [EdukasiEbookController::class, 'index'])->name('edukasi.ebook');
        Route::get('/edukasi/ebook/{folderSlug}', [EdukasiEbookController::class, 'show'])->name('edukasi.ebook.show');
        Route::get('/edukasi/outlook', [EdukasiOutlookController::class, 'index'])->name('edukasi.outlook');
        Route::get('/edukasi/outlook/{folderSlug}', [EdukasiOutlookController::class, 'show'])->name('edukasi.outlook.show');
    });

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
        });
        Route::put('/question/{question}', [PostTestController::class, 'questionUpdate'])
            ->name('question.update');
        Route::delete('/question/{question}', [PostTestController::class, 'questionDestroy'])
            ->name('quiz.delete');
    });

    Route::prefix('posttest')->name('post-test.')->middleware('profile.complete')->group(function () {
        Route::get('/', [TestController::class, 'index'])->name('index');
        Route::middleware(['absensi', 'CheckPATLAccess'])->group(function () {
            Route::get('/{slug}', [TestController::class, 'showQuiz'])->name('show');
            Route::match(['GET', 'POST'], '/{slug}/question/{number}', [TestController::class, 'showQuestion'])->name('question');
            Route::post('/{slug}/submit', [TestController::class, 'submitQuiz'])->name('submit');
        });
        Route::get('/result/{result}', [TestController::class, 'showResult'])->name('result');
    });

    Route::prefix('apuppt-training')->middleware(['profile.complete', 'apuppt.enabled'])->group(function () {
        Route::prefix('ebook')->name('apuppt.edukasi.ebook.')->group(function () {
            Route::get('/', [ApupptEdukasiEbookController::class, 'index'])->name('index');
            Route::get('/{folderSlug}', [ApupptEdukasiEbookController::class, 'show'])->name('show');
        });

        Route::prefix('absensi')->group(function () {
            Route::get('/', [ApupptAbsensiUserController::class, 'index'])->name('apuppt.absensiUser.index');
            Route::post('/store', [ApupptAbsensiUserController::class, 'store'])->name('apuppt.absensiUser.store');
        });

        Route::prefix('posttest')->name('apuppt.test.')->group(function () {
            Route::get('/', [ApupptTestController::class, 'index'])->name('index');
            Route::middleware('apuppt_absensi')->group(function () {
                Route::get('/{slug}', [ApupptTestController::class, 'showQuiz'])->name('show');
                Route::match(['GET', 'POST'], '/{slug}/question/{number}', [ApupptTestController::class, 'showQuestion'])->name('question');
                Route::post('/{slug}/submit', [ApupptTestController::class, 'submitQuiz'])->name('submit');
            });
            Route::get('/result/{result}', [ApupptTestController::class, 'showResult'])->name('result');
        });

        Route::prefix('sertifikat')->name('apuppt.sertifikatUser.')->group(function () {
            Route::get('/', [ApupptSertifikatController::class, 'index'])->name('index');
            Route::get('/{id}/download', [ApupptSertifikatController::class, 'generateCertificate'])->name('download');
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
            Route::post('/{id}/verify', [UserController::class, 'verify'])->name('trainer.verify');
            Route::post('/{id}/suspend', [UserController::class, 'suspend'])->name('trainer.suspend');
            Route::post('/{id}/unsuspend', [UserController::class, 'unsuspend'])->name('trainer.unsuspend');
            Route::delete('/{id}', [UserController::class, 'destroy'])->name('trainer.destroy');
        });
    });

    Route::middleware(['is_admin:Admin,Admin APUPPT', 'apuppt.enabled'])->group(function () {
        Route::prefix('apuppt')->name('apuppt.')->group(function () {
            Route::prefix('ebook-folder')->group(function () {
                Route::get('/', [ApupptEbookFolderController::class, 'index'])->name('ebookfolder.index');
                Route::get('/create', [ApupptEbookFolderController::class, 'create'])->name('ebookfolder.create');
                Route::post('/store', [ApupptEbookFolderController::class, 'store'])->name('ebookfolder.store');
                Route::get('/{slug}/edit', [ApupptEbookFolderController::class, 'edit'])->name('ebookfolder.edit');
                Route::put('/{id}', [ApupptEbookFolderController::class, 'update'])->name('ebookfolder.update');
                Route::post('/{id}/toggle', [ApupptEbookFolderController::class, 'toggle'])->name('ebookfolder.toggle');
                Route::delete('/{id}', [ApupptEbookFolderController::class, 'destroy'])->name('ebookfolder.destroy');
            });

            Route::prefix('ebook')->group(function () {
                Route::get('/{folderSlug}', [ApupptEbookController::class, 'index'])->name('ebook.index');
                Route::get('/{folderSlug}/create', [ApupptEbookController::class, 'create'])->name('ebook.create');
                Route::post('/{folderSlug}/store', [ApupptEbookController::class, 'store'])->name('ebook.store');
                Route::get('/{folderSlug}/{ebookSlug}', [ApupptEbookController::class, 'show'])->name('ebook.show');
                Route::get('/{folderSlug}/{ebookSlug}/edit', [ApupptEbookController::class, 'edit'])->name('ebook.edit');
                Route::put('/{folderSlug}/{ebookSlug}', [ApupptEbookController::class, 'update'])->name('ebook.update');
                Route::delete('/{folderSlug}/{ebookSlug}', [ApupptEbookController::class, 'destroy'])->name('ebook.destroy');
            });

            Route::prefix('post-test')->group(function () {
                Route::get('/', [ApupptQuizController::class, 'index'])->name('posttest.index');
                Route::get('/tambah', [ApupptQuizController::class, 'create'])->name('posttest.create');
                Route::post('/', [ApupptQuizController::class, 'store'])->name('posttest.store');
                Route::get('/{session}/edit', [ApupptQuizController::class, 'edit'])->name('posttest.edit');
                Route::put('/{session}', [ApupptQuizController::class, 'update'])->name('posttest.update');
                Route::delete('/{session}', [ApupptQuizController::class, 'destroy'])->name('posttest.destroy');
                Route::post('/toggle-status/{slug}', [ApupptPostTestController::class, 'toggleStatus'])->name('posttest.toggle');
                Route::get('/{session:slug}/report', [ApupptQuizController::class, 'report'])->name('posttest.report');
                Route::get('/{session:slug}/report/export', [ApupptQuizController::class, 'reportExport'])->name('posttest.report.export');
                Route::delete('/{session:slug}/report/delete-all-failed', [ApupptQuizController::class, 'deleteAllFailed'])->name('posttest.report.deleteAllFailed');
                Route::delete('/{session:slug}/report/{result}', [ApupptQuizController::class, 'deleteResult'])->name('posttest.report.delete');
                Route::prefix('{session}/edit')->group(function () {
                    Route::post('/question', [ApupptPostTestController::class, 'questionStore'])->name('question.store');
                });
                Route::put('/question/{question}', [ApupptPostTestController::class, 'questionUpdate'])->name('question.update');
                Route::delete('/question/{question}', [ApupptPostTestController::class, 'questionDestroy'])->name('quiz.delete');
            });

            Route::prefix('absensi')->group(function () {
                Route::get('/', [ApupptJadwalAbsensiController::class, 'index'])->name('absensi.index');
                Route::get('/create', [ApupptJadwalAbsensiController::class, 'create'])->name('absensi.create');
                Route::post('/tambah', [ApupptJadwalAbsensiController::class, 'store'])->name('absensi.store');
                Route::get('/{id}/edit', [ApupptJadwalAbsensiController::class, 'edit'])->name('absensi.edit');
                Route::post('/{id}/toggle', [ApupptJadwalAbsensiController::class, 'toggle'])->name('absensi.toggle');
                Route::put('/{id}/update', [ApupptJadwalAbsensiController::class, 'update'])->name('absensi.update');
                Route::delete('/{id}/hapus', [ApupptJadwalAbsensiController::class, 'destroy'])->name('absensi.destroy');
                Route::prefix('{idJadwal}')->group(function () {
                    Route::get('/', [ApupptAbsensiAdminController::class, 'indexAdmin'])->name('absensiAdmin.index');
                    Route::get('/pdf', [ApupptAbsensiAdminController::class, 'downloadPdf'])->name('absensi.downloadPdf');
                    Route::get('/excel', [ApupptAbsensiAdminController::class, 'downloadExcel'])->name('absensi.downloadExcel');
                    Route::get('/pdf-per-cabang', [ApupptAbsensiAdminController::class, 'downloadPdfPerCabang'])->name('absensi.downloadPdfPerCabang');
                    Route::get('/excel-per-cabang', [ApupptAbsensiAdminController::class, 'downloadExcelPerCabang'])->name('absensi.downloadExcelPerCabang');
                    Route::delete('/{idAbsensi}/delete', [ApupptAbsensiAdminController::class, 'delete'])->name('absensiAdmin.delete');
                });
            });

            Route::prefix('sertifikat')->group(function () {
                Route::get('/', [ApupptLaporanSertifikatController::class, 'index'])->name('sertifikat.index');
                Route::get('/export', [ApupptLaporanSertifikatController::class, 'export'])->name('sertifikat.export');
                Route::get('/export-per-cabang', [ApupptLaporanSertifikatController::class, 'exportPerCabang'])->name('sertifikat.exportPerCabang');
                Route::delete('/{id}/delete', [ApupptLaporanSertifikatController::class, 'destroy'])->name('sertifikat.destroy');
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
    });

    // Absensi
    Route::prefix('absensi')->middleware('profile.complete')->group(function () {
        Route::get('/', [AbsensiController::class, 'indexAbsensi'])->name('AbsensiUser.index');
        Route::post('/absensi/store', [AbsensiController::class, 'store'])->name('AbsensiUser.store');
    });

    Route::get('/sertifikat', [\App\Http\Controllers\SertifikatController::class, 'index'])->name('sertifikat.index');
    Route::get('/sertifikat/{id}/download', [\App\Http\Controllers\SertifikatController::class, 'generateCertificate'])->name('sertifikat.download');

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/password', [ProfileController::class, 'password'])->name('profile.password');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('cert', function () {
        return view('emails.certificate');
    });

    Route::get('/hapus-akun-tidak-verifikasi', [UserCleanupController::class, 'deleteUnverifiedUsers'])->name('user.delete');
});

require __DIR__.'/auth.php';
