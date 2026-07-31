<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');

});

require __DIR__.'/auth.php';
// Route SIPEMA
require __DIR__.'/mahasiswa.php';
require __DIR__.'/dosen.php';
