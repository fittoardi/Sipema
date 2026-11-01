<?php

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Prodi;
use App\Models\User;

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

    $this->dosenUser = User::factory()->create([
        'name' => 'Test Dosen',
        'email' => 'testdosen@test.com',
        'role' => 'dosen',
    ]);

    $this->dosen = Dosen::create([
        'user_id' => $this->dosenUser->id,
        'nidn' => '0012345678',
        'nama' => 'Test Dosen',
        'prodi_id' => $this->prodi->id,
    ]);

    $this->mahasiswaUser = User::factory()->create([
        'name' => 'Test Mahasiswa',
        'email' => 'testmahasiswa@test.com',
        'role' => 'mahasiswa',
    ]);

    $this->mahasiswa = Mahasiswa::create([
        'user_id' => $this->mahasiswaUser->id,
        'nim' => '23110001',
        'nama' => 'Test Mahasiswa',
        'prodi_id' => $this->prodi->id,
        'angkatan' => 2023,
    ]);

    $this->kelas = Kelas::create([
        'mata_kuliah_id' => $this->mataKuliah->id,
        'dosen_id' => $this->dosen->id,
        'nama' => 'Kelas A',
        'tahun_akademik' => '2023/2024',
        'semester' => 'Ganjil',
    ]);
});

test('dosen can download nilai for owned classes as csv', function () {
    Nilai::create([
        'mahasiswa_id' => $this->mahasiswa->id,
        'kelas_id' => $this->kelas->id,
        'kuis' => 80,
        'tugas' => 85,
        'uts' => 75,
        'uas' => 90,
        'nilai_akhir' => 82.5,
        'grade' => 'A',
        'status' => 'Lulus',
    ]);

    $response = $this->actingAs($this->dosenUser)
        ->get(route('dosen.dashboard.download-nilai'));

    $response->assertOk();
    $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    $response->assertHeader('Content-Disposition', 'attachment; filename="nilai_dosen_0012345678.csv"');

    $content = ltrim($response->streamedContent(), "\xEF\xBB\xBF");

    expect($content)->toContain('NIM,"Nama Mahasiswa","Mata Kuliah",Kuis,Tugas,UTS,UAS,"Nilai Akhir",Grade,Status');
    expect($content)->toContain('23110001,"Test Mahasiswa","Pemrograman Web",80.00,85.00,75.00,90.00,82.50,A,Lulus');
});

test('unauthenticated dosen cannot download nilai', function () {
    $response = $this->get(route('dosen.dashboard.download-nilai'));

    $response->assertRedirect(route('login'));
});
