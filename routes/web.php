<?php

use App\Http\Controllers\FotoPresensiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->get('/foto-presensi/{presensi}', [FotoPresensiController::class, 'show'])
    ->name('foto-presensi.show');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/mahasiswa.php';
require __DIR__.'/dosen.php';
