<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('mahasiswa')
    ->name('mahasiswa.')
    ->group(function () {

        Route::get('/dashboard', function () {

            return view('dashboard.mahasiswa.index');

        })->name('dashboard');

    });
