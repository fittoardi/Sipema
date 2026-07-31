<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('dosen')
    ->name('dosen.')
    ->group(function () {

        Route::get('/dashboard', function () {

            return view('dashboard.dosen.index');

        })->name('dashboard');

    });
