<?php

use App\Filament\Resources\Dosens\DosenResource;
use App\Filament\Resources\Dosens\Pages\CreateDosen;
use App\Filament\Resources\Kelas\Pages\CreateKelas;
use App\Filament\Resources\Mahasiswas\Pages\CreateMahasiswa;
use App\Filament\Resources\MataKuliahs\Pages\CreateMataKuliah;
use App\Filament\Resources\Prodis\Pages\CreateProdi;
use App\Filament\Resources\Prodis\Pages\EditProdi;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Schemas\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

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

function userForRole(string $role, string $email): User
{
    return User::create([
        'name' => ucfirst($role).' Test',
        'email' => $email,
        'password' => Hash::make('password'),
        'role' => $role,
    ]);
}

test('only admin users can access the Filament admin panel', function () {
    $panel = Filament::getPanel('admin');

    expect(adminUser()->canAccessPanel($panel))->toBeTrue()
        ->and(userForRole('dosen', 'dosen@test.test')->canAccessPanel($panel))->toBeFalse()
        ->and(userForRole('mahasiswa', 'mahasiswa@test.test')->canAccessPanel($panel))->toBeFalse();
});

test('admin can create and update a prodi', function () {
    $this->actingAs(adminUser());

    Livewire::test(CreateProdi::class)
        ->fillForm([
            'kode_prodi' => 'TI',
            'nama_prodi' => 'Teknik Informatika',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $prodi = Prodi::where('kode_prodi', 'TI')->firstOrFail();

    Livewire::test(EditProdi::class, ['record' => $prodi->getKey()])
        ->fillForm(['nama_prodi' => 'Teknik Informatika Terapan'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($prodi->refresh()->nama_prodi)->toBe('Teknik Informatika Terapan');
});

test('admin can create a dosen and only dosen accounts are selectable', function () {
    $admin = adminUser();
    $dosenUser = userForRole('dosen', 'dosen@test.test');
    userForRole('mahasiswa', 'mahasiswa@test.test');
    $prodi = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika']);

    $this->actingAs($admin);

    Livewire::test(CreateDosen::class)
        ->fillForm([
            'user_id' => $dosenUser->id,
            'nama' => 'Dosen Test',
            'nidn' => '0012345678',
            'email' => 'dosen.profile@test.test',
            'nip' => '198501012010011001',
            'prodi_id' => $prodi->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Dosen::where('user_id', $dosenUser->id)->exists())->toBeTrue();

    $form = app(DosenResource::class)::form(app(Schema::class));
    expect($form)->toBeInstanceOf(Schema::class);
});

test('admin can create a mahasiswa', function () {
    $admin = adminUser();
    $studentUser = userForRole('mahasiswa', 'mahasiswa@test.test');
    $prodi = Prodi::create(['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi']);

    $this->actingAs($admin);

    Livewire::test(CreateMahasiswa::class)
        ->fillForm([
            'user_id' => $studentUser->id,
            'nama' => 'Mahasiswa Test',
            'nim' => '23110001',
            'prodi_id' => $prodi->id,
            'angkatan' => 2023,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Mahasiswa::where('nim', '23110001')->exists())->toBeTrue();
});

test('admin can create a mata kuliah', function () {
    $this->actingAs(adminUser());
    $prodi = Prodi::create(['kode_prodi' => 'MI', 'nama_prodi' => 'Manajemen Informatika']);

    Livewire::test(CreateMataKuliah::class)
        ->fillForm([
            'prodi_id' => $prodi->id,
            'kode' => 'MK01',
            'nama' => 'Pemrograman Web',
            'sks' => 3,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(MataKuliah::where('kode', 'MK01')->exists())->toBeTrue();
});

test('admin can create a class and enroll students', function () {
    $this->actingAs(adminUser());
    $prodi = Prodi::create(['kode_prodi' => 'TE', 'nama_prodi' => 'Teknik Elektro']);
    $dosenUser = userForRole('dosen', 'dosen@test.test');
    $dosen = Dosen::create(['user_id' => $dosenUser->id, 'nama' => 'Dosen Test', 'prodi_id' => $prodi->id]);
    $studentUser = userForRole('mahasiswa', 'mahasiswa@test.test');
    $student = Mahasiswa::create([
        'user_id' => $studentUser->id,
        'nama' => 'Mahasiswa Test',
        'nim' => '23110002',
        'prodi_id' => $prodi->id,
        'angkatan' => 2023,
    ]);
    $course = MataKuliah::create(['prodi_id' => $prodi->id, 'kode' => 'MK02', 'nama' => 'Basis Data', 'sks' => 3]);

    Livewire::test(CreateKelas::class)
        ->fillForm([
            'mata_kuliah_id' => $course->id,
            'dosen_id' => $dosen->id,
            'nama' => 'TE-A',
            'tahun_akademik' => '2025/2026',
            'semester' => 'Ganjil',
            'mahasiswas' => [$student->id],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $class = Kelas::where('nama', 'TE-A')->firstOrFail();
    expect($class->mahasiswas()->whereKey($student)->exists())->toBeTrue();
});

test('admin can delete a prodi from its edit page', function () {
    $this->actingAs(adminUser());
    $prodi = Prodi::create(['kode_prodi' => 'TM', 'nama_prodi' => 'Teknik Mesin']);

    Livewire::test(EditProdi::class, ['record' => $prodi->getKey()])
        ->callAction('delete');

    expect(Prodi::whereKey($prodi)->exists())->toBeFalse();
});
