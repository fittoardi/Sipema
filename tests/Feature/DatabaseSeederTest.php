<?php

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Pertemuan;
use App\Models\Prodi;
use App\Models\Ruangan;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('database seeder populates at least 30 records for each core academic table', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Prodi::count())->toBeGreaterThanOrEqual(30)
        ->and(Dosen::count())->toBeGreaterThanOrEqual(30)
        ->and(Mahasiswa::count())->toBeGreaterThanOrEqual(30)
        ->and(MataKuliah::count())->toBeGreaterThanOrEqual(30)
        ->and(Kelas::count())->toBeGreaterThanOrEqual(30)
        ->and(Pertemuan::count())->toBeGreaterThanOrEqual(30)
        ->and(Ruangan::count())->toBeGreaterThanOrEqual(5)
        ->and(Nilai::count())->toBeGreaterThanOrEqual(30);

    // Verify demo accounts still exist
    expect(User::where('email', 'admin@sipenma.test')->exists())->toBeTrue()
        ->and(User::where('email', 'budi@sipenma.test')->exists())->toBeTrue()
        ->and(User::where('email', 'andi@sipenma.test')->exists())->toBeTrue();
});
