<?php

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->prodi = Prodi::create([
        'kode_prodi' => 'TI',
        'nama_prodi' => 'Teknik Informatika',
    ]);

    $this->mataKuliah = MataKuliah::create([
        'kode' => 'MK001',
        'nama' => 'Pemrograman Web',
        'sks' => 3,
        'prodi_id' => $this->prodi->id,
    ]);

    $this->user = User::factory()->create([
        'name' => 'Test Mahasiswa',
        'email' => 'testmahasiswa@test.com',
        'role' => 'mahasiswa',
    ]);

    $this->mahasiswa = Mahasiswa::create([
        'user_id' => $this->user->id,
        'nim' => '23110001',
        'nama' => 'Test Mahasiswa',
        'prodi_id' => $this->prodi->id,
        'angkatan' => 2023,
    ]);

    $this->dosenUser = User::factory()->create([
        'name' => 'Test Dosen',
        'email' => 'testdosen@test.com',
        'role' => 'dosen',
    ]);

    $this->dosen = \App\Models\Dosen::create([
        'user_id' => $this->dosenUser->id,
        'nidn' => '0012345678',
        'nama' => 'Test Dosen',
        'prodi_id' => $this->prodi->id,
    ]);

    $this->kelas = Kelas::create([
        'mata_kuliah_id' => $this->mataKuliah->id,
        'dosen_id' => $this->dosen->id,
        'nama' => 'Kelas A',
        'tahun_akademik' => '2023/2024',
        'semester' => 'Ganjil',
    ]);
});

test('mahasiswa can download nilai as csv', function () {
    Nilai::create([
        'mahasiswa_id' => $this->mahasiswa->id,
        'kelas_id' => $this->kelas->id,
        'kuis' => 80.00,
        'tugas' => 85.00,
        'uts' => 75.00,
        'uas' => 90.00,
        'nilai_akhir' => 82.50,
        'grade' => 'A',
        'status' => 'Lulus',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('mahasiswa.dashboard.download-nilai'));

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

    $content = ltrim($response->streamedContent(), "\xEF\xBB\xBF");

    expect($content)->toContain('"Mata Kuliah",Kuis,Tugas,UTS,UAS,"Nilai Akhir",Grade,Status');
    expect($content)->toContain('"Pemrograman Web",80.00,85.00,75.00,90.00,82.50,A,Lulus');
});

test('csv contains correct columns for multiple nilai', function () {
    Nilai::create([
        'mahasiswa_id' => $this->mahasiswa->id,
        'kelas_id' => $this->kelas->id,
        'kuis' => 80.00,
        'tugas' => 85.00,
        'uts' => 75.00,
        'uas' => 90.00,
        'nilai_akhir' => 82.50,
        'grade' => 'A',
        'status' => 'Lulus',
    ]);

    $mataKuliah2 = MataKuliah::create([
        'kode' => 'MK002',
        'nama' => 'Basis Data',
        'sks' => 3,
        'prodi_id' => $this->prodi->id,
    ]);

    $kelas2 = Kelas::create([
        'mata_kuliah_id' => $mataKuliah2->id,
        'dosen_id' => $this->dosen->id,
        'nama' => 'Kelas B',
        'tahun_akademik' => '2023/2024',
        'semester' => 'Ganjil',
    ]);

    Nilai::create([
        'mahasiswa_id' => $this->mahasiswa->id,
        'kelas_id' => $kelas2->id,
        'kuis' => 70.00,
        'tugas' => 75.00,
        'uts' => 65.00,
        'uas' => 80.00,
        'nilai_akhir' => 72.50,
        'grade' => 'B',
        'status' => 'Lulus',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('mahasiswa.dashboard.download-nilai'));

    $content = ltrim($response->streamedContent(), "\xEF\xBB\xBF");

    expect($content)->toContain('"Mata Kuliah",Kuis,Tugas,UTS,UAS,"Nilai Akhir",Grade,Status');
    expect($content)->toContain('"Pemrograman Web",80.00,85.00,75.00,90.00,82.50,A,Lulus');
    expect($content)->toContain('"Basis Data",70.00,75.00,65.00,80.00,72.50,B,Lulus');
});

test('unauthenticated user cannot download nilai', function () {
    $response = $this->get(route('mahasiswa.dashboard.download-nilai'));

    $response->assertRedirect(route('login'));
});

test('csv filename uses mahasiswa nim', function () {
    Nilai::create([
        'mahasiswa_id' => $this->mahasiswa->id,
        'kelas_id' => $this->kelas->id,
        'kuis' => 80.00,
        'tugas' => 85.00,
        'uts' => 75.00,
        'uas' => 90.00,
        'nilai_akhir' => 82.50,
        'grade' => 'A',
        'status' => 'Lulus',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('mahasiswa.dashboard.download-nilai'));

    $response->assertHeader('Content-Disposition', 'attachment; filename="nilai_23110001.csv"');
});
