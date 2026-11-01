<?php

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('guest cannot access admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
});

test('non-admin user is forbidden from admin dashboard', function () {
    $user = User::factory()->create(['role' => 'mahasiswa']);

    $response = $this->actingAs($user)->get(route('admin.dashboard'));

    $response->assertForbidden();
});

test('admin can access dashboard and view statistics and grade distribution', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $prodi = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika']);

    $dosenUser = User::factory()->create(['role' => 'dosen']);
    $dosen = Dosen::create([
        'user_id' => $dosenUser->id,
        'nama' => 'Dosen Test',
        'nidn' => '0011223344',
        'email' => 'dosen@test.test',
        'nip' => '199001012015011001',
        'prodi_id' => $prodi->id,
    ]);

    $studentUser = User::factory()->create(['role' => 'mahasiswa']);
    $mahasiswa = Mahasiswa::create([
        'user_id' => $studentUser->id,
        'nama' => 'Mahasiswa Test',
        'nim' => '24110001',
        'prodi_id' => $prodi->id,
        'angkatan' => 2024,
    ]);

    $mk = MataKuliah::create([
        'kode' => 'MK101',
        'nama' => 'Algoritma',
        'sks' => 3,
        'prodi_id' => $prodi->id,
    ]);

    $kelas = Kelas::create([
        'mata_kuliah_id' => $mk->id,
        'dosen_id' => $dosen->id,
        'nama' => 'TI-1A',
        'tahun_akademik' => '2024/2025',
        'semester' => 'Ganjil',
    ]);

    $nilai = new Nilai([
        'kelas_id' => $kelas->id,
        'mahasiswa_id' => $mahasiswa->id,
        'kuis' => 85,
        'tugas' => 90,
        'uts' => 80,
        'uas' => 85,
    ]);
    $nilai->recalculate()->save();

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk()
        ->assertViewIs('admin.dashboard.index')
        ->assertViewHasAll([
            'totalMahasiswa',
            'totalDosen',
            'prodis',
            'distribusiGrade',
            'grafikNorm',
            'lastSemester',
        ]);
});
