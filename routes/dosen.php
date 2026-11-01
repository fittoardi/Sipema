<?php

use App\Http\Controllers\Dosen\DashboardController;
use App\Http\Controllers\Dosen\NilaiController;
use App\Http\Controllers\Dosen\PertemuanController;
use App\Http\Controllers\Dosen\PerwalianKrsController;
use App\Http\Controllers\Dosen\PresensiController;
use App\Http\Controllers\Dosen\TugasController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->as('dosen.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/matakuliah', [DashboardController::class, 'mataKuliah'])->name('courses');
        Route::get('/jadwal', [DashboardController::class, 'jadwal'])->name('schedule');

        Route::get('/dashboard/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::post('/dashboard/nilai', [NilaiController::class, 'store'])->name('nilai.store');

        Route::get('/dashboard/download-nilai', [DashboardController::class, 'downloadNilai'])->name('dashboard.download-nilai');

        // Perwalian & Persetujuan KRS Online
        Route::get('/perwalian', [PerwalianKrsController::class, 'index'])->name('perwalian.index');
        Route::get('/perwalian/{mahasiswa}', [PerwalianKrsController::class, 'show'])->name('perwalian.show');
        Route::post('/perwalian/{krs}/approve', [PerwalianKrsController::class, 'approve'])->name('perwalian.approve');
        Route::post('/perwalian/{krs}/revisi', [PerwalianKrsController::class, 'revisi'])->name('perwalian.revisi');

        // LMS & Perkuliahan Sesi 1-16
        Route::get('/kelas/{kela}/pertemuan', [PertemuanController::class, 'index'])->name('pertemuan.index');
        Route::get('/kelas/{kela}/pertemuan/{pertemuan}', [PertemuanController::class, 'show'])->name('pertemuan.show');
        Route::put('/kelas/{kela}/pertemuan/{pertemuan}', [PertemuanController::class, 'update'])->name('pertemuan.update');
        Route::post('/kelas/{kela}/pertemuan/{pertemuan}/toggle-presensi', [PertemuanController::class, 'togglePresensi'])->name('pertemuan.toggle-presensi');
        Route::post('/kelas/{kela}/pertemuan/{pertemuan}/materi', [PertemuanController::class, 'storeMateri'])->name('pertemuan.materi.store');
        Route::delete('/kelas/{kela}/pertemuan/{pertemuan}/materi/{materi}', [PertemuanController::class, 'destroyMateri'])->name('pertemuan.materi.destroy');
        Route::post('/kelas/{kela}/pertemuan/{pertemuan}/tugas', [PertemuanController::class, 'storeTugas'])->name('pertemuan.tugas.store');

        // Tugas & Penilaian Submission
        Route::get('/kelas/{kela}/pertemuan/{pertemuan}/tugas/{tugas}', [TugasController::class, 'show'])->name('tugas.show');
        Route::post('/kelas/{kela}/pertemuan/{pertemuan}/tugas/{tugas}/nilai/{pengumpulan}', [TugasController::class, 'grade'])->name('tugas.grade');
        Route::get('/kelas/{kela}/pertemuan/{pertemuan}/tugas/{tugas}/download/{pengumpulan}', [TugasController::class, 'downloadSubmission'])->name('tugas.download-submission');
        Route::post('/kelas/{kela}/sinkron-tugas', [TugasController::class, 'syncAll'])->name('tugas.sync-all');

        // Presensi & Galeri Verifikasi Selfie
        Route::get('/kelas/{kela}/pertemuan/{pertemuan}/presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::post('/kelas/{kela}/pertemuan/{pertemuan}/presensi/{mahasiswa}', [PresensiController::class, 'update'])->name('presensi.update');
        Route::get('/kelas/{kela}/pertemuan/{pertemuan}/presensi/export-csv', [PresensiController::class, 'exportCsv'])->name('presensi.export-csv');

    });
