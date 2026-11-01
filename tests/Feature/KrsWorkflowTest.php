<?php

use App\Enums\StatusKrs;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->tahunAktif = TahunAkademik::factory()->create([
        'is_active' => true,
        'is_krs_open' => true,
        'maks_sks_default' => 24,
    ]);

    $this->prodi = Prodi::first() ?? Prodi::factory()->create();

    $this->dosenUser = User::factory()->dosen()->create();
    $this->dosen = Dosen::factory()->create([
        'user_id' => $this->dosenUser->id,
        'program_studi_id' => $this->prodi->id,
    ]);

    $this->mhsUser = User::factory()->mahasiswa()->create();
    $this->mahasiswa = Mahasiswa::factory()->create([
        'user_id' => $this->mhsUser->id,
        'program_studi_id' => $this->prodi->id,
        'dosen_wali_id' => $this->dosen->id,
    ]);

    $this->mk = MataKuliah::factory()->create([
        'program_studi_id' => $this->prodi->id,
        'sks' => 3,
    ]);

    $this->kelas = Kelas::factory()->create([
        'mata_kuliah_id' => $this->mk->id,
        'dosen_id' => $this->dosen->id,
        'tahun_akademik_id' => $this->tahunAktif->id,
        'kapasitas' => 30,
    ]);
});

it('allows mahasiswa to view the KRS index page', function () {
    $this->actingAs($this->mhsUser)
        ->get(route('mahasiswa.krs.index'))
        ->assertSuccessful()
        ->assertSee('Kartu Rencana Studi (KRS)');
});

it('allows mahasiswa to add a class to draft KRS', function () {
    $response = $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.store'), [
            'kelas_id' => $this->kelas->id,
        ]);

    $response->assertSessionHas('success');

    $krs = Krs::where('mahasiswa_id', $this->mahasiswa->id)
        ->where('tahun_akademik_id', $this->tahunAktif->id)
        ->first();

    expect($krs)->not->toBeNull()
        ->and($krs->total_sks)->toBe(3)
        ->and($krs->details()->count())->toBe(1);
});

it('prevents adding the same class twice', function () {
    $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.store'), ['kelas_id' => $this->kelas->id]);

    $duplicateResponse = $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.store'), ['kelas_id' => $this->kelas->id]);

    $duplicateResponse->assertSessionHas('error');
});

it('allows mahasiswa to remove a class from draft KRS', function () {
    $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.store'), ['kelas_id' => $this->kelas->id]);

    $deleteResponse = $this->actingAs($this->mhsUser)
        ->delete(route('mahasiswa.krs.destroy', $this->kelas->id));

    $deleteResponse->assertSessionHas('success');

    $krs = Krs::where('mahasiswa_id', $this->mahasiswa->id)->first();
    expect($krs->total_sks)->toBe(0)
        ->and($krs->details()->count())->toBe(0);
});

it('allows mahasiswa to submit KRS to advisor', function () {
    $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.store'), ['kelas_id' => $this->kelas->id]);

    $submitResponse = $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.submit'));

    $submitResponse->assertSessionHas('success');

    $krs = Krs::where('mahasiswa_id', $this->mahasiswa->id)->first();
    expect($krs->status)->toBe(StatusKrs::Diajukan);
});

it('allows dosen advisor to review and approve KRS', function () {
    // Student submits KRS
    $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.store'), ['kelas_id' => $this->kelas->id]);
    $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.submit'));

    $krs = Krs::where('mahasiswa_id', $this->mahasiswa->id)->first();

    // Dosen views perwalian list
    $this->actingAs($this->dosenUser)
        ->get(route('dosen.perwalian.index'))
        ->assertSuccessful()
        ->assertSee($this->mahasiswa->nama);

    // Dosen views detail
    $this->actingAs($this->dosenUser)
        ->get(route('dosen.perwalian.show', $this->mahasiswa->id))
        ->assertSuccessful()
        ->assertSee($this->kelas->mataKuliah->nama);

    // Dosen approves KRS
    $approveResponse = $this->actingAs($this->dosenUser)
        ->post(route('dosen.perwalian.approve', $krs->id));

    $approveResponse->assertSessionHas('success');

    $krs->refresh();
    expect($krs->status)->toBe(StatusKrs::Disetujui);

    // Enrolled in kelas_mahasiswa automatically
    $enrolled = KelasMahasiswa::where('kelas_id', $this->kelas->id)
        ->where('mahasiswa_id', $this->mahasiswa->id)
        ->exists();

    expect($enrolled)->toBeTrue();
});

it('allows dosen advisor to request revision with notes', function () {
    $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.store'), ['kelas_id' => $this->kelas->id]);
    $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.submit'));

    $krs = Krs::where('mahasiswa_id', $this->mahasiswa->id)->first();

    $revisiResponse = $this->actingAs($this->dosenUser)
        ->post(route('dosen.perwalian.revisi', $krs->id), [
            'catatan' => 'Mohon kurangi SKS dan ganti jadwal hari Selasa.',
        ]);

    $revisiResponse->assertSessionHas('success');

    $krs->refresh();
    expect($krs->status)->toBe(StatusKrs::Revisi)
        ->and($krs->catatan_wali)->toContain('kurangi SKS');
});

it('generates PDF for student KRS', function () {
    $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.krs.store'), ['kelas_id' => $this->kelas->id]);

    $pdfResponse = $this->actingAs($this->mhsUser)
        ->get(route('mahasiswa.krs.cetak-pdf'));

    $pdfResponse->assertSuccessful()
        ->assertHeader('Content-Type', 'application/pdf');
});

