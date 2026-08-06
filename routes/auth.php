<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication (Mahasiswa & Dosen)
|--------------------------------------------------------------------------
| Admin menggunakan Filament (/admin)
*/

Route::middleware('guest')->group(function () {

    // Login
    Route::controller(AuthenticatedSessionController::class)->group(function () {

        Route::get('/login', 'create')
            ->name('login');

        Route::post('/login', 'store')
            ->name('login.store');

    });

});

Route::middleware('auth')->group(function () {

    Route::controller(AuthenticatedSessionController::class)->group(function () {

        Route::post('/logout', 'destroy')
            ->name('logout');

    });

});
