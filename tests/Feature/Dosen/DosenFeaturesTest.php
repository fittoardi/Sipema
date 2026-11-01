<?php

use App\Models\Dosen;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed([
        \Database\Seeders\PengaturanNilaiSeeder::class,
        \Database\Seeders\SkalaGradeSeeder::class,
    ]);

    $this->prodi = Prodi::create([
        'kode_prodi' => 'TI',
        'nama_prodi' => 'Teknik Informatika',
    ]);

    $this->dosenUser = User::factory()->create([
        'name' => 'Dr. Budi Santoso',
        'email' => 'budi@test.com',
        'role' => 'dosen',
    ]);

    $this->dosen = Dosen::create([
        'user_id' => $this->dosenUser->id,
        'nama' => 'Dr. Budi Santoso',
        'nidn' => '0012345678',
        'email' => 'budi@test.com',
        'nip' => '198501012010011001',
        'prodi_id' => $this->prodi->id,
    ]);

    $this->mataKuliah = MataKuliah::create([
        'kode' => 'MK101',
        'nama' => 'Pemrograman Web',
        'sks' => 3,
        'prodi_id' => $this->prodi->id,
    ]);

    $this->ruangan = \App\Models\Ruangan::create([
        'kode' => 'LAB1',
        'nama' => 'Lab Komputer 1',
        'kapasitas' => 40,
    ]);

    $this->kelas = Kelas::create([
        'mata_kuliah_id' => $this->mataKuliah->id,
        'dosen_id' => $this->dosen->id,
        'ruangan_id' => $this->ruangan->id,
        'nama' => 'TI-3A',
        'tahun_akademik' => '2024/2025',
        'semester' => 'Ganjil',
    ]);

    $this->studentUser = User::factory()->create([
        'name' => 'Andi Pratama',
        'email' => 'andi@test.com',
        'role' => 'mahasiswa',
    ]);

    $this->student = Mahasiswa::create([
        'user_id' => $this->studentUser->id,
        'nama' => 'Andi Pratama',
        'nim' => '23110001',
        'prodi_id' => $this->prodi->id,
        'angkatan' => 2023,
    ]);

    $this->kelas->mahasiswas()->attach($this->student);

    $this->kelas->update([
        'hari' => 'Senin',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '10:30:00',
    ]);
});

test('dosen can view dashboard with accurate statistics and assigned classes', function () {
    $response = $this->actingAs($this->dosenUser)->get(route('dosen.dashboard'));

    $response->assertOk()
        ->assertViewIs('dashboard.dosen.index')
        ->assertViewHasAll([
            'totalMahasiswa',
            'totalMataKuliah',
            'totalJadwal',
            'ringkasanNilai',
            'kelas',
        ])
        ->assertSee('Dr. Budi Santoso')
        ->assertSee('TI-3A')
        ->assertSee('Pemrograman Web');
});

test('dosen can view mata kuliah diampu page', function () {
    $response = $this->actingAs($this->dosenUser)->get(route('dosen.courses'));

    $response->assertOk()
        ->assertViewIs('dashboard.dosen.matakuliah')
        ->assertSee('Pemrograman Web')
        ->assertSee('MK101')
        ->assertSee('TI-3A');
});

test('dosen can view jadwal mengajar page', function () {
    $response = $this->actingAs($this->dosenUser)->get(route('dosen.schedule'));

    $response->assertOk()
        ->assertViewIs('dashboard.dosen.jadwal')
        ->assertSee('Senin')
        ->assertSee('Lab Komputer 1')
        ->assertSee('Pemrograman Web');
});

test('dosen can view input nilai page and filter by class', function () {
    $response = $this->actingAs($this->dosenUser)->get(route('dosen.nilai.index'));

    $response->assertOk()
        ->assertViewIs('dashboard.dosen.nilai')
        ->assertSee('TI-3A')
        ->assertSee('Andi Pratama')
        ->assertSee('23110001');

    $filteredResponse = $this->actingAs($this->dosenUser)->get(route('dosen.nilai.index', ['kelas_id' => $this->kelas->id]));

    $filteredResponse->assertOk()
        ->assertSee('TI-3A');
});

test('dosen can store grade and recalculate score for student', function () {
    $response = $this->actingAs($this->dosenUser)->post(route('dosen.nilai.store'), [
        'kelas_id' => $this->kelas->id,
        'mahasiswa_id' => $this->student->id,
        'kuis' => 80,
        'tugas' => 90,
        'uts' => 85,
        'uas' => 90,
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('status');

    $nilai = Nilai::whereHas('kelasMahasiswa', fn ($q) => $q->where('kelas_id', $this->kelas->id)->where('mahasiswa_id', $this->student->id))
        ->firstOrFail();

    expect((float) $nilai->nilai_akhir)->toBe(87.5)
        ->and($nilai->grade)->toBe('A')
        ->and($nilai->status->label())->toBe('Lulus');
});

test('guest cannot access dosen features', function () {
    $this->get(route('dosen.dashboard'))->assertRedirect(route('login'));
    $this->get(route('dosen.courses'))->assertRedirect(route('login'));
    $this->get(route('dosen.schedule'))->assertRedirect(route('login'));
    $this->get(route('dosen.nilai.index'))->assertRedirect(route('login'));
});

test('student cannot access dosen features', function () {
    $this->actingAs($this->studentUser)->get(route('dosen.dashboard'))->assertForbidden();
    $this->actingAs($this->studentUser)->get(route('dosen.courses'))->assertForbidden();
    $this->actingAs($this->studentUser)->get(route('dosen.schedule'))->assertForbidden();
    $this->actingAs($this->studentUser)->get(route('dosen.nilai.index'))->assertForbidden();
});
