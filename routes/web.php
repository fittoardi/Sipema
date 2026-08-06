<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

require __DIR__.'/auth.php';
require __DIR__.'/mahasiswa.php';
require __DIR__.'/dosen.php';
