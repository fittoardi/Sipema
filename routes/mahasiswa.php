<?php

use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\JadwalController;
use App\Http\Controllers\Mahasiswa\KelasController;
use App\Http\Controllers\Mahasiswa\KrsController;
use App\Http\Controllers\Mahasiswa\PresensiSayaController;
use App\Http\Controllers\Mahasiswa\TugasController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:mahasiswa'])
    ->prefix('mahasiswa')
    ->as('mahasiswa.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/dashboard/download-nilai', [DashboardController::class, 'downloadNilai'])
            ->name('dashboard.download-nilai');

        // Jadwal Perkuliahan & Kalender
        Route::get('/jadwal', [JadwalController::class, 'index'])
            ->name('jadwal');

        // Mata Kuliah & LMS Ruang Belajar
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/{kela}', [KelasController::class, 'show'])->name('kelas.show');
        Route::post('/materi/{materi}/toggle', [KelasController::class, 'toggleMateri'])->name('materi.toggle');
        Route::get('/materi/{materi}/download', [KelasController::class, 'downloadMateri'])->name('materi.download');

        // Pusat Tugas & Penugasan
        Route::get('/tugas', [TugasController::class, 'index'])->name('tugas.index');
        Route::get('/tugas/{tugas}', [TugasController::class, 'show'])->name('tugas.show');
        Route::post('/tugas/{tugas}/submit', [TugasController::class, 'submit'])->name('tugas.submit');
        Route::get('/tugas/{tugas}/download-lampiran', [TugasController::class, 'downloadLampiran'])->name('tugas.download-lampiran');
        Route::get('/tugas/{tugas}/download-jawaban', [TugasController::class, 'downloadJawaban'])->name('tugas.download-jawaban');

        // Presensi & Rekap Kehadiran
        Route::get('/presensi', [PresensiSayaController::class, 'index'])->name('presensi.index');
        Route::get('/pertemuan/{pertemuan}/presensi', [PresensiSayaController::class, 'show'])->name('presensi.show');
        Route::post('/pertemuan/{pertemuan}/presensi', [PresensiSayaController::class, 'store'])->name('presensi.store');

        // KRS Online
        Route::get('/krs', [KrsController::class, 'index'])->name('krs.index');
        Route::post('/krs', [KrsController::class, 'store'])->name('krs.store');
        Route::delete('/krs/{kela}', [KrsController::class, 'destroy'])->name('krs.destroy');
        Route::post('/krs/submit', [KrsController::class, 'submit'])->name('krs.submit');
        Route::get('/krs/cetak-pdf', [KrsController::class, 'cetakPdf'])->name('krs.cetak-pdf');

        // Presensi Kamera Live & GPS Geofencing
        Route::get('/pertemuan/{pertemuan}/presensi', [PresensiSayaController::class, 'show'])->name('presensi.show');
        Route::post('/pertemuan/{pertemuan}/presensi', [PresensiSayaController::class, 'store'])->name('presensi.store');

        // Tugas & E-Learning
        Route::get('/tugas/{tugas}', [TugasController::class, 'show'])->name('tugas.show');
        Route::post('/tugas/{tugas}/submit', [TugasController::class, 'submit'])->name('tugas.submit');
        Route::get('/tugas/{tugas}/download-lampiran', [TugasController::class, 'downloadLampiran'])->name('tugas.download-lampiran');
        Route::get('/tugas/{tugas}/download-jawaban', [TugasController::class, 'downloadJawaban'])->name('tugas.download-jawaban');

    });
