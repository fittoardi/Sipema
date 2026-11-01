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

function adminUser(): User
{
    return User::create([
        'name' => 'Admin Test',
        'email' => 'admin@test.test',
        'password' => Hash::make('password'),
        'role' => 'admin',
    ]);
}

test('admin can create a prodi', function () {
    $this->actingAs(adminUser());

    $this->post(route('admin.prodi.store'), [
        'kode_prodi' => 'TI',
        'nama_prodi' => 'Teknik Informatika',
    ])->assertRedirect(route('admin.prodi.index'));

    expect(Prodi::where('kode', 'TI')->exists())->toBeTrue();
});

test('admin can create academic resources', function () {
    $admin = adminUser();
    $prodi = Prodi::create(['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi']);
    $dosenUser = User::create(['name' => 'Dosen', 'email' => 'dosen-user@test.test', 'password' => Hash::make('password'), 'role' => 'dosen']);
    $mahasiswaUser = User::create(['name' => 'Mahasiswa', 'email' => 'mahasiswa-user@test.test', 'password' => Hash::make('password'), 'role' => 'mahasiswa']);
    $this->actingAs($admin);

    $this->post(route('admin.dosen.store'), [
        'user_id' => $dosenUser->id, 'nidn' => '0012345678', 'nip' => '198501012010011001', 'nama' => 'Dosen Test', 'email' => 'dosen@test.test', 'prodi_id' => $prodi->id,
    ])->assertRedirect(route('admin.dosen.index'));
    $dosen = Dosen::where('nip', '198501012010011001')->firstOrFail();

    $this->post(route('admin.mahasiswa.store'), [
        'user_id' => $mahasiswaUser->id, 'nim' => '23110001', 'nama' => 'Mahasiswa Test', 'prodi_id' => $prodi->id, 'angkatan' => 2023,
    ])->assertRedirect(route('admin.mahasiswa.index'));
    $mahasiswa = Mahasiswa::where('nim', '23110001')->firstOrFail();

    $this->post(route('admin.matakuliah.store'), [
        'prodi_id' => $prodi->id, 'kode' => 'MK01', 'nama' => 'Pemrograman Web', 'sks' => 3,
    ])->assertRedirect(route('admin.matakuliah.index'));
    $mataKuliah = MataKuliah::where('kode', 'MK01')->firstOrFail();

    $this->post(route('admin.kelas.store'), [
        'mata_kuliah_id' => $mataKuliah->id, 'dosen_id' => $dosen->id, 'nama' => 'SI-A',
        'tahun_akademik' => '2025/2026', 'semester' => 'Ganjil',
    ])->assertRedirect(route('admin.kelas.index'));
    $kelas = Kelas::where('nama_kelas', 'SI-A')->first() ?? Kelas::latest('id')->firstOrFail();

    $this->post(route('admin.nilai.store'), [
        'kelas_id' => $kelas->id, 'mahasiswa_id' => $mahasiswa->id,
        'kuis' => 80, 'tugas' => 85, 'uts' => 75, 'uas' => 88,
    ])->assertRedirect(route('admin.nilai.index'));

    expect(Nilai::count())->toBeGreaterThan(0);

    // Update and delete Dosen
    $this->put(route('admin.dosen.update', $dosen), [
        'user_id' => $dosenUser->id, 'nidn' => '0012345678', 'nip' => '198501012010011001', 'nama' => 'Dosen Updated', 'email' => 'dosen-updated@test.test', 'prodi_id' => $prodi->id,
    ])->assertRedirect(route('admin.dosen.index'));
    expect($dosen->refresh()->nama)->toBe('Dosen Updated');

    // Update and delete Mahasiswa
    $this->put(route('admin.mahasiswa.update', $mahasiswa), [
        'user_id' => $mahasiswaUser->id, 'nim' => '23110001', 'nama' => 'Mahasiswa Updated', 'prodi_id' => $prodi->id, 'angkatan' => 2024,
    ])->assertRedirect(route('admin.mahasiswa.index'));
    expect($mahasiswa->refresh()->nama)->toBe('Mahasiswa Updated');

    // Update and delete Mata Kuliah
    $this->put(route('admin.matakuliah.update', $mataKuliah), [
        'prodi_id' => $prodi->id, 'kode' => 'MK01', 'nama' => 'Pemrograman Web Lanjutan', 'sks' => 4,
    ])->assertRedirect(route('admin.matakuliah.index'));
    expect($mataKuliah->refresh()->nama)->toBe('Pemrograman Web Lanjutan');

    // Update and delete Kelas
    $this->put(route('admin.kelas.update', $kelas), [
        'mata_kuliah_id' => $mataKuliah->id, 'dosen_id' => $dosen->id, 'nama' => 'SI-B',
        'tahun_akademik' => '2025/2026', 'semester' => 'Genap',
    ])->assertRedirect(route('admin.kelas.index'));
    expect($kelas->refresh()->nama)->toBe('SI-B');

    // Update and delete Nilai
    $nilai = Nilai::latest('id')->firstOrFail();
    $this->put(route('admin.nilai.update', $nilai), [
        'kelas_id' => $kelas->id, 'mahasiswa_id' => $mahasiswa->id,
        'kuis' => 90, 'tugas' => 90, 'uts' => 90, 'uas' => 90,
    ])->assertRedirect(route('admin.nilai.index'));
    expect((float) $nilai->refresh()->nilai_akhir)->toBe(90.0);

    $this->delete(route('admin.nilai.destroy', $nilai))->assertRedirect(route('admin.nilai.index'));
    expect(Nilai::whereKey($nilai)->exists())->toBeFalse();

    $this->delete(route('admin.kelas.destroy', $kelas))->assertRedirect(route('admin.kelas.index'));
    expect(Kelas::whereKey($kelas)->exists())->toBeFalse();

    $this->delete(route('admin.matakuliah.destroy', $mataKuliah))->assertRedirect(route('admin.matakuliah.index'));
    expect(MataKuliah::whereKey($mataKuliah)->exists())->toBeFalse();

    $this->delete(route('admin.mahasiswa.destroy', $mahasiswa))->assertRedirect(route('admin.mahasiswa.index'));
    expect(Mahasiswa::whereKey($mahasiswa)->exists())->toBeFalse();

    $this->delete(route('admin.dosen.destroy', $dosen))->assertRedirect(route('admin.dosen.index'));
    expect(Dosen::whereKey($dosen)->exists())->toBeFalse();
});

test('admin can update and delete prodi', function () {
    $this->actingAs(adminUser());
    $prodi = Prodi::create(['kode_prodi' => 'TM', 'nama_prodi' => 'Teknik Mesin']);

    $this->put(route('admin.prodi.update', $prodi), [
        'kode_prodi' => 'TM', 'nama_prodi' => 'Teknik Mesin Terapan',
    ])->assertRedirect(route('admin.prodi.index'));

    expect($prodi->refresh()->nama_prodi)->toBe('Teknik Mesin Terapan');

    $this->delete(route('admin.prodi.destroy', $prodi))
        ->assertRedirect(route('admin.prodi.index'));

    expect(Prodi::whereKey($prodi)->exists())->toBeFalse();
});
