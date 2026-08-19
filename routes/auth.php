<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetCodeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication (Mahasiswa & Dosen)
|--------------------------------------------------------------------------
| Admin menggunakan Filament (/admin)
*/

Route::middleware('guest')->group(function () {

    Route::controller(PasswordResetCodeController::class)->group(function () {
        Route::get('/forgot-password', 'create')->name('password.code.request');
        Route::post('/forgot-password', 'send')->name('password.code.send');
        Route::get('/verify-reset-code', 'verifyForm')->name('password.code.verify');
        Route::post('/verify-reset-code', 'verify')->name('password.code.verify.submit');
        Route::get('/reset-password/code', 'resetForm')->name('password.code.reset');
        Route::post('/reset-password/code', 'reset')->name('password.code.update');
    });

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
