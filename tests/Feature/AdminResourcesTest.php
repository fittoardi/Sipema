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

    expect(Prodi::where('kode_prodi', 'TI')->exists())->toBeTrue();
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
    $kelas = Kelas::where('nama', 'SI-A')->firstOrFail();

    $this->post(route('admin.nilai.store'), [
        'kelas_id' => $kelas->id, 'mahasiswa_id' => $mahasiswa->id,
        'kuis' => 80, 'tugas' => 85, 'uts' => 75, 'uas' => 88,
    ])->assertRedirect(route('admin.nilai.index'));

    expect(Nilai::where('mahasiswa_id', $mahasiswa->id)->exists())->toBeTrue();
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
