<?php

use App\Enums\Hari;
use App\Enums\ModePertemuan;
use App\Enums\TipeKelas;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Pertemuan;
use App\Models\Prodi;
use App\Models\Ruangan;
use App\Models\TahunAkademik;
use App\Models\User;

it('allows mahasiswa to view their lecture schedule and calendar', function (): void {
    $fakultas = Fakultas::create(['kode' => 'FTI', 'nama' => 'Fakultas Teknologi Informasi']);
    $prodi = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'fakultas_id' => $fakultas->id]);
    $ta = TahunAkademik::create(['tahun' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => true, 'is_krs_open' => true]);

    $user = User::factory()->create(['role' => 'mahasiswa']);
    $mahasiswa = Mahasiswa::create([
        'user_id' => $user->id,
        'nama' => 'Andi Pratama',
        'nim' => '23110001',
        'program_studi_id' => $prodi->id,
        'angkatan' => 2023,
    ]);

    $mk = MataKuliah::create([
        'kode' => 'TIF101',
        'nama' => 'Pemrograman Web Modern',
        'sks' => 3,
        'program_studi_id' => $prodi->id,
    ]);

    $ruangan = Ruangan::create([
        'kode' => 'R301',
        'nama' => 'Laboratorium Komputer 301',
        'gedung' => 'Gedung FTI',
        'kapasitas' => 40,
        'latitude' => -6.2000000,
        'longitude' => 106.8166660,
        'radius_meter' => 100,
    ]);

    $dosenUser = User::factory()->create(['role' => 'dosen']);
    $dosen = Dosen::create([
        'user_id' => $dosenUser->id,
        'nidn' => '0011223344',
        'nama' => 'Dr. Budi Dosen',
        'program_studi_id' => $prodi->id,
    ]);

    $kelas = Kelas::create([
        'mata_kuliah_id' => $mk->id,
        'dosen_id' => $dosen->id,
        'tahun_akademik_id' => $ta->id,
        'ruangan_id' => $ruangan->id,
        'nama_kelas' => 'IF-A',
        'tipe_kelas' => TipeKelas::Offline,
        'hari' => Hari::Senin,
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '10:00:00',
        'kapasitas' => 40,
    ]);

    // Enroll student into class
    KelasMahasiswa::create([
        'kelas_id' => $kelas->id,
        'mahasiswa_id' => $mahasiswa->id,
    ]);

    // Create a meeting
    $pertemuan = Pertemuan::create([
        'kelas_id' => $kelas->id,
        'pertemuan_ke' => 1,
        'judul' => 'Pengenalan Arsitektur Web',
        'mode' => ModePertemuan::Offline,
        'ruangan_id' => $ruangan->id,
        'tanggal' => now()->toDateString(),
        'is_published' => true,
        'is_presensi_open' => true,
    ]);

    $response = $this->actingAs($user)->get(route('mahasiswa.jadwal'));

    $response->assertOk()
        ->assertViewIs('dashboard.mahasiswa.jadwal')
        ->assertSee('Jadwal Perkuliahan')
        ->assertSee('Kalender Akademik')
        ->assertSee('Pemrograman Web Modern')
        ->assertSee('TIF101')
        ->assertSee('IF-A')
        ->assertSee('Laboratorium Komputer 301')
        ->assertSee('Senin')
        ->assertSee(route('mahasiswa.presensi.show', $pertemuan->id));
});

it('displays schedule access from mahasiswa dashboard', function (): void {
    $fakultas = Fakultas::create(['kode' => 'FTI', 'nama' => 'Fakultas Teknologi Informasi']);
    $prodi = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'fakultas_id' => $fakultas->id]);

    $user = User::factory()->create(['role' => 'mahasiswa']);
    Mahasiswa::create([
        'user_id' => $user->id,
        'nama' => 'Andi Pratama',
        'nim' => '23110001',
        'program_studi_id' => $prodi->id,
        'angkatan' => 2023,
    ]);

    $response = $this->actingAs($user)->get(route('mahasiswa.dashboard'));

    $response->assertOk()
        ->assertSee(route('mahasiswa.jadwal'))
        ->assertSee('Jadwal');
});

it('prevents guests from accessing mahasiswa jadwal', function (): void {
    $response = $this->get(route('mahasiswa.jadwal'));

    $response->assertRedirect(route('login'));
});

it('prevents non-mahasiswa from accessing mahasiswa jadwal', function (): void {
    $dosenUser = User::factory()->create(['role' => 'dosen']);

    $response = $this->actingAs($dosenUser)->get(route('mahasiswa.jadwal'));

    $response->assertForbidden();
});
