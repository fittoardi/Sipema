<?php

use App\Http\Controllers\FotoPresensiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isDosen()) {
            return redirect()->route('dosen.dashboard');
        }
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        // Unknown role fallback: logout and redirect to login
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    return redirect()->route('login');
});

Route::get('/dashboard', function (Request $request) {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isDosen()) {
            return redirect()->route('dosen.dashboard');
        }
        if ($user->isMahasiswa()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    return redirect()->route('login');
})->name('dashboard');

Route::middleware('auth')->get('/foto-presensi/{presensi}', [FotoPresensiController::class, 'show'])
    ->name('foto-presensi.show');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/mahasiswa.php';
require __DIR__.'/dosen.php';
