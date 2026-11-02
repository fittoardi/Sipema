<?php

use App\Enums\Hari;
use App\Enums\MetodePresensi;
use App\Enums\ModePertemuan;
use App\Enums\StatusPresensi;
use App\Enums\TipeKelas;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Materi;
use App\Models\Pertemuan;
use App\Models\Presensi;
use App\Models\Prodi;
use App\Models\ProgresMateri;
use App\Models\Ruangan;
use App\Models\TahunAkademik;
use App\Models\Tugas;
use App\Models\User;

beforeEach(function (): void {
    $this->fakultas = Fakultas::create(['kode' => 'FTI', 'nama' => 'Fakultas Teknologi Informasi']);
    $this->prodi = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'fakultas_id' => $this->fakultas->id]);
    $this->ta = TahunAkademik::create(['tahun' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => true, 'is_krs_open' => true]);

    $this->user = User::factory()->create(['role' => 'mahasiswa']);
    $this->mahasiswa = Mahasiswa::create([
        'user_id' => $this->user->id,
        'nama' => 'Andi Pratama',
        'nim' => '23110001',
        'program_studi_id' => $this->prodi->id,
        'angkatan' => 2023,
    ]);

    $this->mk = MataKuliah::create([
        'kode' => 'TIF201',
        'nama' => 'Arsitektur Perangkat Lunak',
        'sks' => 3,
        'program_studi_id' => $this->prodi->id,
    ]);

    $this->ruangan = Ruangan::create([
        'kode' => 'R201',
        'nama' => 'Ruang Teori 201',
        'gedung' => 'Gedung FTI',
        'kapasitas' => 40,
        'latitude' => -6.2000000,
        'longitude' => 106.8166660,
        'radius_meter' => 100,
    ]);

    $dosenUser = User::factory()->create(['role' => 'dosen']);
    $this->dosen = Dosen::create([
        'user_id' => $dosenUser->id,
        'nidn' => '0099887766',
        'nama' => 'Dr. Hendra Dosen',
        'program_studi_id' => $this->prodi->id,
    ]);

    $this->kelas = Kelas::create([
        'mata_kuliah_id' => $this->mk->id,
        'dosen_id' => $this->dosen->id,
        'tahun_akademik_id' => $this->ta->id,
        'ruangan_id' => $this->ruangan->id,
        'nama_kelas' => 'TI-3A',
        'tipe' => TipeKelas::Offline,
        'hari' => Hari::Senin,
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:30',
        'kuota' => 40,
    ]);

    KelasMahasiswa::create([
        'kelas_id' => $this->kelas->id,
        'mahasiswa_id' => $this->mahasiswa->id,
    ]);
});

it('allows mahasiswa to view enrolled classes and 16 sessions LMS room', function (): void {
    $pertemuan = Pertemuan::create([
        'kelas_id' => $this->kelas->id,
        'pertemuan_ke' => 1,
        'judul' => 'Pengenalan Arsitektur Perangkat Lunak',
        'deskripsi' => 'Overview konsep MVC dan Monolith vs Microservices',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:30',
        'mode' => ModePertemuan::Offline,
        'is_presensi_open' => false,
    ]);

    $materi = Materi::create([
        'pertemuan_id' => $pertemuan->id,
        'judul' => 'Slide PDF Pengantar Arsitektur',
        'tipe' => 'file',
        'konten' => 'https://example.com/slide.pdf',
    ]);

    $response = $this->actingAs($this->user)->get(route('mahasiswa.kelas.index'));
    $response->assertSuccessful();
    $response->assertSee('Mata Kuliah & LMS Saya');
    $response->assertSee('Arsitektur Perangkat Lunak');

    $responseDetail = $this->actingAs($this->user)->get(route('mahasiswa.kelas.show', $this->kelas));
    $responseDetail->assertSuccessful();
    $responseDetail->assertSee('Ruang Belajar LMS');
    $responseDetail->assertSee('Pengenalan Arsitektur Perangkat Lunak');
    $responseDetail->assertSee('Slide PDF Pengantar Arsitektur');
});

it('allows mahasiswa to toggle reading progress on learning materials', function (): void {
    $pertemuan = Pertemuan::create([
        'kelas_id' => $this->kelas->id,
        'pertemuan_ke' => 1,
        'judul' => 'Pengenalan',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:30',
        'mode' => ModePertemuan::Offline,
        'is_presensi_open' => false,
    ]);

    $materi = Materi::create([
        'pertemuan_id' => $pertemuan->id,
        'judul' => 'Slide Modul 1',
        'tipe' => 'link',
        'konten' => 'https://example.com/modul1',
    ]);

    $response = $this->actingAs($this->user)->postJson(route('mahasiswa.materi.toggle', $materi));
    $response->assertSuccessful();
    $response->assertJson(['is_selesai' => true]);

    expect(ProgresMateri::where('materi_id', $materi->id)->where('mahasiswa_id', $this->mahasiswa->id)->exists())->toBeTrue();

    // Toggle again should unmark
    $responseAgain = $this->actingAs($this->user)->postJson(route('mahasiswa.materi.toggle', $materi));
    $responseAgain->assertSuccessful();
    $responseAgain->assertJson(['is_selesai' => false]);
});

it('displays student assignment center with status filters', function (): void {
    $pertemuan = Pertemuan::create([
        'kelas_id' => $this->kelas->id,
        'pertemuan_ke' => 2,
        'judul' => 'Design Patterns',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:30',
        'mode' => ModePertemuan::Offline,
        'is_presensi_open' => false,
    ]);

    $tugas = Tugas::create([
        'pertemuan_id' => $pertemuan->id,
        'judul' => 'Tugas 1: Analisis Clean Architecture',
        'instruksi' => 'Analisis implementasi Service Repository di Laravel',
        'batas_waktu' => now()->addDays(3),
    ]);

    $response = $this->actingAs($this->user)->get(route('mahasiswa.tugas.index'));
    $response->assertSuccessful();
    $response->assertSee('Pusat Tugas & Penugasan');
    $response->assertSee('Tugas 1: Analisis Clean Architecture');

    // Filter pending
    $responsePending = $this->actingAs($this->user)->get(route('mahasiswa.tugas.index', ['status' => 'pending']));
    $responsePending->assertSuccessful();
    $responsePending->assertSee('Tugas 1: Analisis Clean Architecture');
});

it('displays student attendance recap with 75 percent UAS threshold and logs', function (): void {
    $pertemuan = Pertemuan::create([
        'kelas_id' => $this->kelas->id,
        'pertemuan_ke' => 1,
        'judul' => 'Pertemuan 1',
        'tanggal' => now()->toDateString(),
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:30',
        'mode' => ModePertemuan::Offline,
        'is_presensi_open' => false,
    ]);

    Presensi::create([
        'pertemuan_id' => $pertemuan->id,
        'mahasiswa_id' => $this->mahasiswa->id,
        'status' => StatusPresensi::Hadir,
        'metode' => MetodePresensi::Mandiri,
        'is_dalam_radius' => true,
        'dicatat_pada' => now(),
        'keterangan' => 'Valid di dalam radius kampus',
    ]);

    $response = $this->actingAs($this->user)->get(route('mahasiswa.presensi.index'));
    $response->assertSuccessful();
    $response->assertSee('Rekapitulasi Kehadiran Kuliah');
    $response->assertSee('Syarat UAS');
    $response->assertSee('Arsitektur Perangkat Lunak');
    $response->assertSee('100%');
    $response->assertSee('Memenuhi Syarat');
});
