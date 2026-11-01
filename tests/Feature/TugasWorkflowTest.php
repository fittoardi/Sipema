<?php

use App\Enums\Hari;
use App\Enums\ModePertemuan;
use App\Enums\StatusNilai;
use App\Enums\TipeKelas;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\PengumpulanTugas;
use App\Models\Pertemuan;
use App\Models\Prodi;
use App\Models\TahunAkademik;
use App\Models\Tugas;
use App\Models\User;
use Database\Seeders\PengaturanNilaiSeeder;
use Database\Seeders\SkalaGradeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('private');

    // Seed standard grading weights and grade scales
    $this->seed(PengaturanNilaiSeeder::class);
    $this->seed(SkalaGradeSeeder::class);

    $this->ta = TahunAkademik::factory()->create([
        'is_active' => true,
        'is_krs_open' => true,
    ]);

    $this->prodi = Prodi::factory()->create();

    // Dosen
    $this->dosenUser = User::factory()->dosen()->create();
    $this->dosen = Dosen::factory()->create([
        'user_id' => $this->dosenUser->id,
        'program_studi_id' => $this->prodi->id,
    ]);

    // Mahasiswa 1 (Enrolled)
    $this->mhs1User = User::factory()->mahasiswa()->create();
    $this->mhs1 = Mahasiswa::factory()->create([
        'user_id' => $this->mhs1User->id,
        'program_studi_id' => $this->prodi->id,
    ]);

    // Mahasiswa 2 (Not enrolled)
    $this->mhs2User = User::factory()->mahasiswa()->create();
    $this->mhs2 = Mahasiswa::factory()->create([
        'user_id' => $this->mhs2User->id,
        'program_studi_id' => $this->prodi->id,
    ]);

    // Course & Class
    $this->mk = MataKuliah::factory()->create([
        'program_studi_id' => $this->prodi->id,
    ]);

    $this->kelas = Kelas::factory()->create([
        'mata_kuliah_id' => $this->mk->id,
        'dosen_id' => $this->dosen->id,
        'tahun_akademik_id' => $this->ta->id,
        'nama_kelas' => 'PWL-A',
        'tipe_kelas' => TipeKelas::Offline->value,
        'hari' => Hari::Senin->value,
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '10:30:00',
        'kapasitas' => 40,
    ]);

    $this->km1 = KelasMahasiswa::create([
        'kelas_id' => $this->kelas->id,
        'mahasiswa_id' => $this->mhs1->id,
    ]);

    $this->pertemuan = Pertemuan::create([
        'kelas_id' => $this->kelas->id,
        'pertemuan_ke' => 1,
        'judul' => 'Arsitektur Web Modern',
        'mode' => ModePertemuan::Offline->value,
        'tanggal' => now()->toDateString(),
        'is_published' => true,
    ]);
});

test('non-enrolled student cannot access assignment', function () {
    $tugas = Tugas::create([
        'pertemuan_id' => $this->pertemuan->id,
        'judul' => 'Tugas 1',
        'instruksi' => 'Kerjakan modul 1',
        'batas_waktu' => now()->addDays(3),
    ]);

    $this->actingAs($this->mhs2User)
        ->get(route('mahasiswa.tugas.show', $tugas))
        ->assertStatus(403);
});

test('enrolled student can access assignment details and download attachment', function () {
    // Store fake attachment
    $attachmentPath = 'tugas/soal_tugas_1.pdf';
    Storage::disk('private')->put($attachmentPath, 'SOAL TUGAS DARI DOSEN');

    $tugas = Tugas::create([
        'pertemuan_id' => $this->pertemuan->id,
        'judul' => 'Tugas 1: Framework MVC',
        'instruksi' => 'Jelaskan konsep arsitektur MVC',
        'lampiran_path' => $attachmentPath,
        'batas_waktu' => now()->addDays(3),
    ]);

    $response = $this->actingAs($this->mhs1User)
        ->get(route('mahasiswa.tugas.show', $tugas));

    $response->assertOk()
        ->assertSee('Tugas 1: Framework MVC')
        ->assertSee('Unduh Lampiran Soal');

    // Download attachment
    $download = $this->actingAs($this->mhs1User)
        ->get(route('mahasiswa.tugas.download-lampiran', $tugas));

    $download->assertOk();
});

test('enrolled student can submit assignment before deadline', function () {
    $tugas = Tugas::create([
        'pertemuan_id' => $this->pertemuan->id,
        'judul' => 'Tugas 2: Implementasi Eloquent',
        'instruksi' => 'Kirim laporan PDF',
        'batas_waktu' => now()->addDays(2),
    ]);

    $file = UploadedFile::fake()->create('laporan.pdf', 500, 'application/pdf');

    $response = $this->actingAs($this->mhs1User)
        ->post(route('mahasiswa.tugas.submit', $tugas), [
            'file' => $file,
            'catatan_mahasiswa' => 'Berikut tugas laporan saya pak.',
        ]);

    $response->assertRedirect();

    $this->assertDatabaseHas('pengumpulan_tugas', [
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $this->mhs1->id,
        'is_terlambat' => false,
        'catatan_mahasiswa' => 'Berikut tugas laporan saya pak.',
    ]);

    $submission = PengumpulanTugas::where('tugas_id', $tugas->id)->where('mahasiswa_id', $this->mhs1->id)->first();
    expect(Storage::disk('private')->exists($submission->file_path))->toBeTrue();
});

test('enrolled student cannot submit late when allow_late is false', function () {
    $tugas = Tugas::create([
        'pertemuan_id' => $this->pertemuan->id,
        'judul' => 'Tugas Strict Deadline',
        'instruksi' => 'Harus tepat waktu',
        'batas_waktu' => now()->subDay(),
        'allow_late' => false,
    ]);

    $file = UploadedFile::fake()->create('jawaban.pdf', 200, 'application/pdf');

    $response = $this->actingAs($this->mhs1User)
        ->post(route('mahasiswa.tugas.submit', $tugas), [
            'file' => $file,
        ]);

    $response->assertSessionHasErrors('file');
    $this->assertDatabaseMissing('pengumpulan_tugas', [
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $this->mhs1->id,
    ]);
});

test('enrolled student can submit late when allow_late is true and marked as late', function () {
    $tugas = Tugas::create([
        'pertemuan_id' => $this->pertemuan->id,
        'judul' => 'Tugas Flexible Deadline',
        'instruksi' => 'Boleh telat',
        'batas_waktu' => now()->subDay(),
        'allow_late' => true,
    ]);

    $file = UploadedFile::fake()->create('jawaban_telat.pdf', 200, 'application/pdf');

    $response = $this->actingAs($this->mhs1User)
        ->post(route('mahasiswa.tugas.submit', $tugas), [
            'file' => $file,
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('pengumpulan_tugas', [
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $this->mhs1->id,
        'is_terlambat' => true,
    ]);
});

test('dosen can grade submission and SinkronisasiTugasService automatically updates Nilai and recalculates final grade', function () {
    $tugas = Tugas::create([
        'pertemuan_id' => $this->pertemuan->id,
        'judul' => 'Tugas Final Project',
        'instruksi' => 'Kerjakan dengan baik',
        'batas_waktu' => now()->addDays(2),
    ]);

    $submissionPath = 'tugas_submissions/jawaban_mhs.pdf';
    Storage::disk('private')->put($submissionPath, 'JAWABAN TUGAS MAHASISWA');

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $this->mhs1->id,
        'file_path' => $submissionPath,
        'dikumpulkan_pada' => now(),
        'is_terlambat' => false,
    ]);

    // Initial existing Nilai with partial grades
    $nilai = Nilai::create([
        'kelas_mahasiswa_id' => $this->km1->id,
        'kehadiran' => 100,
        'kuis' => 80,
        'tugas' => 0,
        'uts' => 85,
        'uas' => 90,
    ]);

    // Lecturer grades the submission: 90
    $response = $this->actingAs($this->dosenUser)
        ->post(route('dosen.tugas.grade', [$this->kelas, $this->pertemuan, $tugas, $pengumpulan]), [
            'nilai' => 90,
            'umpan_balik' => 'Analisis arsitektur sangat mendalam dan rapi.',
        ]);

    $response->assertRedirect();

    $pengumpulan->refresh();
    expect($pengumpulan->nilai)->toBe(90.0)
        ->and($pengumpulan->umpan_balik)->toBe('Analisis arsitektur sangat mendalam dan rapi.');

    // Verify Nilai::tugas was updated to 90
    $nilai->refresh();
    expect((float) $nilai->tugas)->toBe(90.0);

    // Formula: (kuis*10 + tugas*20 + uts*30 + uas*40) / 100
    // (80*10 + 90*20 + 85*30 + 90*40) / 100 = (800 + 1800 + 2550 + 3600) / 100 = 8750 / 100 = 87.50
    // Grade: A (>= 80)
    expect((float) $nilai->nilai_akhir)->toBe(87.5)
        ->and($nilai->grade)->toBe('A')
        ->and($nilai->status)->toBe(StatusNilai::Lulus);
});

test('dosen can download student submission file from private disk', function () {
    $tugas = Tugas::create([
        'pertemuan_id' => $this->pertemuan->id,
        'judul' => 'Tugas 3',
        'instruksi' => 'Unggah file',
        'batas_waktu' => now()->addDays(2),
    ]);

    $submissionPath = 'tugas_submissions/doc.pdf';
    Storage::disk('private')->put($submissionPath, 'ISI FILE MAHASISWA');

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $this->mhs1->id,
        'file_path' => $submissionPath,
        'dikumpulkan_pada' => now(),
        'is_terlambat' => false,
    ]);

    $response = $this->actingAs($this->dosenUser)
        ->get(route('dosen.tugas.download-submission', [$this->kelas, $this->pertemuan, $tugas, $pengumpulan]));

    $response->assertOk();
});

test('syncAll synchronizes average task scores across all enrolled students', function () {
    // Enroll second student
    $km2 = KelasMahasiswa::create([
        'kelas_id' => $this->kelas->id,
        'mahasiswa_id' => $this->mhs2->id,
    ]);

    $tugas1 = Tugas::create([
        'pertemuan_id' => $this->pertemuan->id,
        'judul' => 'Tugas 1',
        'instruksi' => 'Soal 1',
        'batas_waktu' => now()->addDays(2),
    ]);

    $tugas2 = Tugas::create([
        'pertemuan_id' => $this->pertemuan->id,
        'judul' => 'Tugas 2',
        'instruksi' => 'Soal 2',
        'batas_waktu' => now()->addDays(2),
    ]);

    // Student 1 got 80 on Tugas 1, 100 on Tugas 2 -> avg 90
    PengumpulanTugas::create([
        'tugas_id' => $tugas1->id,
        'mahasiswa_id' => $this->mhs1->id,
        'file_path' => 'tugas_submissions/fake1.pdf',
        'dikumpulkan_pada' => now(),
        'nilai' => 80,
    ]);
    PengumpulanTugas::create([
        'tugas_id' => $tugas2->id,
        'mahasiswa_id' => $this->mhs1->id,
        'file_path' => 'tugas_submissions/fake2.pdf',
        'dikumpulkan_pada' => now(),
        'nilai' => 100,
    ]);

    // Student 2 got 70 on Tugas 1 -> avg 70
    PengumpulanTugas::create([
        'tugas_id' => $tugas1->id,
        'mahasiswa_id' => $this->mhs2->id,
        'file_path' => 'tugas_submissions/fake3.pdf',
        'dikumpulkan_pada' => now(),
        'nilai' => 70,
    ]);

    $response = $this->actingAs($this->dosenUser)
        ->post(route('dosen.tugas.sync-all', $this->kelas));

    $response->assertRedirect();

    $nilai1 = Nilai::where('kelas_mahasiswa_id', $this->km1->id)->first();
    $nilai2 = Nilai::where('kelas_mahasiswa_id', $km2->id)->first();

    expect((float) $nilai1->tugas)->toBe(90.0)
        ->and((float) $nilai2->tugas)->toBe(70.0);
});
