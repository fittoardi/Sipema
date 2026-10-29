<?php

use App\Http\Controllers\Dosen\DashboardController;
use App\Http\Controllers\Dosen\NilaiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->as('dosen.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('dashboard/nilai', [NilaiController::class, 'index'])->name('nilai.index');
        Route::post('dashboard/nilai', [NilaiController::class, 'store'])->name('nilai.store');

        Route::get('dashboard/download-nilai', [DashboardController::class, 'downloadNilai'])->name('dashboard.download-nilai');

    });
