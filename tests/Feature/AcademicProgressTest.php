<?php

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('nilai is calculated from configured weights and grade rules', function () {
    $nilai = new Nilai([
        'kuis' => 100,
        'tugas' => 80,
        'uts' => 70,
        'uas' => 60,
    ]);

    $nilai->recalculate();

    expect((float) $nilai->nilai_akhir)->toBe(71.0)
        ->and($nilai->grade)->toBe('B')
        ->and($nilai->status)->toBe('Lulus');
});

test('dosen can save scores only for enrolled students in owned classes', function () {
    $prodi = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika']);
    $user = User::create([
        'name' => 'Dosen Test',
        'email' => 'dosen@test.test',
        'password' => Hash::make('password'),
        'role' => 'dosen',
    ]);
    $dosen = Dosen::create(['user_id' => $user->id, 'nama' => 'Dosen Test', 'prodi_id' => $prodi->id]);
    $studentUser = User::create([
        'name' => 'Mahasiswa Test',
        'email' => 'mahasiswa@test.test',
        'password' => Hash::make('password'),
        'role' => 'mahasiswa',
    ]);
    $student = Mahasiswa::create([
        'user_id' => $studentUser->id,
        'nama' => 'Mahasiswa Test',
        'nim' => '12345678',
        'prodi_id' => $prodi->id,
        'angkatan' => 2023,
    ]);
    $course = MataKuliah::create(['prodi_id' => $prodi->id, 'kode' => 'MK01', 'nama' => 'Pemrograman', 'sks' => 3]);
    $class = Kelas::create([
        'mata_kuliah_id' => $course->id,
        'dosen_id' => $dosen->id,
        'nama' => 'TI-A',
        'tahun_akademik' => '2025/2026',
        'semester' => 'Ganjil',
    ]);
    $class->mahasiswas()->attach($student);

    $this->actingAs($user)->post(route('dosen.nilai.store'), [
        'kelas_id' => $class->id,
        'mahasiswa_id' => $student->id,
        'kuis' => 90,
        'tugas' => 80,
        'uts' => 70,
        'uas' => 60,
    ])->assertRedirect();

    $nilai = Nilai::firstOrFail();
    expect((float) $nilai->nilai_akhir)->toBe(70.0)
        ->and($nilai->grade)->toBe('B');
});
