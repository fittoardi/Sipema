<?php

use App\Enums\ModePertemuan;
use App\Enums\StatusPresensi;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Pertemuan;
use App\Models\Presensi;
use App\Models\Prodi;
use App\Models\Ruangan;
use App\Models\TahunAkademik;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('private');

    $this->tahunAktif = TahunAkademik::factory()->create([
        'is_active' => true,
        'is_krs_open' => true,
    ]);

    $this->prodi = Prodi::first() ?? Prodi::factory()->create();

    $this->ruangan = Ruangan::factory()->create([
        'latitude' => -6.200000,
        'longitude' => 106.816666,
        'radius_meter' => 100,
    ]);

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
    ]);

    $this->kelas = Kelas::factory()->create([
        'mata_kuliah_id' => $this->mk->id,
        'dosen_id' => $this->dosen->id,
        'tahun_akademik_id' => $this->tahunAktif->id,
        'ruangan_id' => $this->ruangan->id,
    ]);

    // Enroll student in class
    KelasMahasiswa::create([
        'kelas_id' => $this->kelas->id,
        'mahasiswa_id' => $this->mahasiswa->id,
    ]);

    // Create session 1
    $this->pertemuan = Pertemuan::create([
        'kelas_id' => $this->kelas->id,
        'pertemuan_ke' => 1,
        'judul' => 'Pengenalan Mata Kuliah & Kontrak Belajar',
        'mode' => ModePertemuan::Offline,
        'ruangan_id' => $this->ruangan->id,
        'tanggal' => now()->toDateString(),
        'is_published' => true,
        'is_presensi_open' => false,
        'wajib_selfie' => true,
        'wajib_gps' => true,
    ]);
});

it('allows dosen to view sessions and toggle presensi open', function () {
    $this->actingAs($this->dosenUser)
        ->get(route('dosen.pertemuan.index', $this->kelas->id))
        ->assertSuccessful()
        ->assertSee('Pengenalan Mata Kuliah');

    $toggleResponse = $this->actingAs($this->dosenUser)
        ->post(route('dosen.pertemuan.toggle-presensi', [$this->kelas->id, $this->pertemuan->id]));

    $toggleResponse->assertSessionHas('success');

    $this->pertemuan->refresh();
    expect($this->pertemuan->is_presensi_open)->toBeTrue()
        ->and($this->pertemuan->kode_presensi)->not->toBeEmpty();
});

it('allows dosen to add course material', function () {
    $response = $this->actingAs($this->dosenUser)
        ->post(route('dosen.pertemuan.materi.store', [$this->kelas->id, $this->pertemuan->id]), [
            'judul' => 'Slide Modul 1 PDF',
            'tipe' => 'url',
            'url' => 'https://drive.google.com/sample',
        ]);

    $response->assertSessionHas('success');

    expect($this->pertemuan->materis()->count())->toBe(1)
        ->and($this->pertemuan->materis->first()->judul)->toBe('Slide Modul 1 PDF');
});

it('allows student to view attendance page when open and submit presence', function () {
    // Dosen opens attendance
    $this->pertemuan->update([
        'is_presensi_open' => true,
        'kode_presensi' => 'TESTPIN',
    ]);

    // Mahasiswa visits attendance page
    $this->actingAs($this->mhsUser)
        ->get(route('mahasiswa.presensi.show', $this->pertemuan->id))
        ->assertSuccessful()
        ->assertSee('Presensi Perkuliahan Sesi 1');

    // Create a 1x1 dummy jpeg base64
    $dummyBase64 = 'data:image/jpeg;base64,'.base64_encode('fake-image-binary-data');

    // Submit within geofence radius
    $response = $this->actingAs($this->mhsUser)
        ->post(route('mahasiswa.presensi.store', $this->pertemuan->id), [
            'latitude' => -6.200010,
            'longitude' => 106.816670,
            'akurasi_meter' => 5,
            'kode_presensi' => 'TESTPIN',
            'foto_selfie' => $dummyBase64,
        ]);

    $response->assertSessionHas('success');

    $presensi = Presensi::where('pertemuan_id', $this->pertemuan->id)
        ->where('mahasiswa_id', $this->mahasiswa->id)
        ->first();

    expect($presensi)->not->toBeNull()
        ->and($presensi->status)->toBe(StatusPresensi::Hadir)
        ->and($presensi->is_dalam_radius)->toBeTrue()
        ->and($presensi->foto_selfie_path)->not->toBeNull();
});

it('allows dosen to view attendance gallery and manually update status', function () {
    $presensi = Presensi::create([
        'pertemuan_id' => $this->pertemuan->id,
        'mahasiswa_id' => $this->mahasiswa->id,
        'status' => StatusPresensi::Hadir,
        'dicatat_pada' => now(),
    ]);

    $this->actingAs($this->dosenUser)
        ->get(route('dosen.presensi.index', [$this->kelas->id, $this->pertemuan->id]))
        ->assertSuccessful()
        ->assertSee($this->mahasiswa->nama);

    // Dosen changes status to Izin
    $updateResponse = $this->actingAs($this->dosenUser)
        ->post(route('dosen.presensi.update', [$this->kelas->id, $this->pertemuan->id, $this->mahasiswa->id]), [
            'status' => 'izin',
            'keterangan' => 'Mengikuti lomba',
        ]);

    $updateResponse->assertSessionHas('success');

    $presensi->refresh();
    expect($presensi->status)->toBe(StatusPresensi::Izin);
});

it('restricts selfie photo download to authorized parties only', function () {
    $filePath = 'presensi/test_selfie.jpg';
    Storage::disk('private')->put($filePath, 'fake-jpeg-content');

    $presensi = Presensi::create([
        'pertemuan_id' => $this->pertemuan->id,
        'mahasiswa_id' => $this->mahasiswa->id,
        'status' => StatusPresensi::Hadir,
        'foto_selfie_path' => $filePath,
        'dicatat_pada' => now(),
    ]);

    // Authorized student can view their photo
    $this->actingAs($this->mhsUser)
        ->get(route('foto-presensi.show', $presensi->id))
        ->assertSuccessful();

    // Authorized lecturer can view the photo
    $this->actingAs($this->dosenUser)
        ->get(route('foto-presensi.show', $presensi->id))
        ->assertSuccessful();

    // Stranger student cannot view someone else's selfie photo (UU PDP)
    $otherStudent = User::factory()->mahasiswa()->create();
    $this->actingAs($otherStudent)
        ->get(route('foto-presensi.show', $presensi->id))
        ->assertForbidden();
});

