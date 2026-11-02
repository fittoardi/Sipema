<?php

use App\Models\Fakultas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;

it('allows admin to view mahasiswa list with faculty and prodi details', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    $fakultas = Fakultas::create(['kode' => 'FTI', 'nama' => 'Fakultas Teknologi Informasi']);
    $prodi = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'fakultas_id' => $fakultas->id]);
    $user = User::factory()->create(['role' => 'mahasiswa']);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $user->id,
        'nama' => 'Budi Sanjaya',
        'nim' => '23110099',
        'program_studi_id' => $prodi->id,
        'angkatan' => 2023,
    ]);

    expect($mahasiswa->fakultas->id)->toBe($fakultas->id)
        ->and($mahasiswa->fakultas->kode)->toBe('FTI');

    $response = $this->actingAs($admin)->get(route('admin.mahasiswa.index'));

    $response->assertOk()
        ->assertViewIs('admin.mahasiswa.index')
        ->assertSee('Budi Sanjaya')
        ->assertSee('23110099')
        ->assertSee('Teknik Informatika')
        ->assertSee('FTI');
});

it('filters mahasiswa by faculty', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);

    $fakultasFTI = Fakultas::create(['kode' => 'FTI', 'nama' => 'Fakultas Teknologi Informasi']);
    $prodiTI = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'fakultas_id' => $fakultasFTI->id]);

    $fakultasFT = Fakultas::create(['kode' => 'FT', 'nama' => 'Fakultas Teknik']);
    $prodiTE = Prodi::create(['kode_prodi' => 'TE', 'nama_prodi' => 'Teknik Elektro', 'fakultas_id' => $fakultasFT->id]);

    $user1 = User::factory()->create(['role' => 'mahasiswa']);
    $mhs1 = Mahasiswa::create([
        'user_id' => $user1->id,
        'nama' => 'Mahasiswa Komputer',
        'nim' => '23110001',
        'program_studi_id' => $prodiTI->id,
        'angkatan' => 2023,
    ]);

    $user2 = User::factory()->create(['role' => 'mahasiswa']);
    $mhs2 = Mahasiswa::create([
        'user_id' => $user2->id,
        'nama' => 'Mahasiswa Elektro',
        'nim' => '23210001',
        'program_studi_id' => $prodiTE->id,
        'angkatan' => 2023,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.mahasiswa.index', ['fakultas_id' => $fakultasFTI->id]));

    $response->assertOk()
        ->assertSee('Mahasiswa Komputer')
        ->assertDontSee('Mahasiswa Elektro');
});

it('filters mahasiswa by prodi and searches by query', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);

    $fakultas = Fakultas::create(['kode' => 'FEB', 'nama' => 'Fakultas Ekonomi dan Bisnis']);
    $prodi1 = Prodi::create(['kode_prodi' => 'MN', 'nama_prodi' => 'Manajemen', 'fakultas_id' => $fakultas->id]);
    $prodi2 = Prodi::create(['kode_prodi' => 'AK', 'nama_prodi' => 'Akuntansi', 'fakultas_id' => $fakultas->id]);

    $user1 = User::factory()->create(['role' => 'mahasiswa']);
    Mahasiswa::create([
        'user_id' => $user1->id,
        'nama' => 'Citra Lestari',
        'nim' => '23310001',
        'program_studi_id' => $prodi1->id,
        'angkatan' => 2023,
    ]);

    $user2 = User::factory()->create(['role' => 'mahasiswa']);
    Mahasiswa::create([
        'user_id' => $user2->id,
        'nama' => 'Dedi Suryadi',
        'nim' => '23310002',
        'program_studi_id' => $prodi2->id,
        'angkatan' => 2023,
    ]);

    // Filter by prodi
    $responseProdi = $this->actingAs($admin)->get(route('admin.mahasiswa.index', ['prodi_id' => $prodi1->id]));
    $responseProdi->assertOk()
        ->assertSee('Citra Lestari')
        ->assertDontSee('Dedi Suryadi');

    // Search by NIM
    $responseSearch = $this->actingAs($admin)->get(route('admin.mahasiswa.index', ['search' => '23310002']));
    $responseSearch->assertOk()
        ->assertSee('Dedi Suryadi')
        ->assertDontSee('Citra Lestari');
});

it('displays optgroup organized by faculty on create and edit forms', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    $fakultas = Fakultas::create(['kode' => 'FTI', 'nama' => 'Fakultas Teknologi Informasi']);
    $prodi = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'fakultas_id' => $fakultas->id]);
    $user = User::factory()->create(['role' => 'mahasiswa']);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $user->id,
        'nama' => 'Eka Putra',
        'nim' => '23110055',
        'program_studi_id' => $prodi->id,
        'angkatan' => 2023,
    ]);

    $createResponse = $this->actingAs($admin)->get(route('admin.mahasiswa.create'));
    $createResponse->assertOk()
        ->assertSee('Fakultas Teknologi Informasi (FTI)');

    $editResponse = $this->actingAs($admin)->get(route('admin.mahasiswa.edit', $mahasiswa));
    $editResponse->assertOk()
        ->assertSee('Fakultas Teknologi Informasi (FTI)');
});

it('disables prodis of other faculties when a faculty is selected in search or filter', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);

    $fakultasFTI = Fakultas::create(['kode' => 'FTI', 'nama' => 'Fakultas Teknologi Informasi']);
    $prodiTI = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'fakultas_id' => $fakultasFTI->id]);

    $fakultasFT = Fakultas::create(['kode' => 'FT', 'nama' => 'Fakultas Teknik']);
    $prodiTS = Prodi::create(['kode_prodi' => 'TS', 'nama_prodi' => 'Teknik Sipil', 'fakultas_id' => $fakultasFT->id]);

    // Request with fakultas_id for FTI
    $response = $this->actingAs($admin)->get(route('admin.mahasiswa.index', [
        'fakultas_id' => $fakultasFTI->id,
        'search' => 'Informatika',
    ]));

    $response->assertOk();

    // The FTI prodi should be enabled (not nonaktif)
    $response->assertSee('Teknik Informatika (TI)');
    $response->assertDontSee('Teknik Informatika (TI) [Nonaktif - Beda Fakultas]');

    // The FT prodi should be disabled with nonaktif badge
    $response->assertSee('Teknik Sipil (TS)');
    $response->assertSee('[Nonaktif - Beda Fakultas]');
});
