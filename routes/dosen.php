<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:dosen'])
    ->prefix('dosen')
    ->as('dosen.')
    ->group(function () {

        Route::view('/dashboard', 'dashboard.dosen.index')
            ->name('dashboard');

    });
