<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mahasiswa\DashboardController;

Route::middleware(['auth','role:mahasiswa'])
    ->prefix('mahasiswa')
    ->as('mahasiswa.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class,'index'])
            ->name('dashboard');

        Route::get('/dashboard/download-nilai', [DashboardController::class, 'downloadNilai'])
            ->name('dashboard.download-nilai');

    });
