<?php

use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;

it('requires admin role for fakultas resource', function (): void {
    $response = $this->get(route('admin.fakultas.index'));

    $response->assertRedirect(route('login'));
});

it('allows admin to view list of fakultas', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    $fakultas = Fakultas::create([
        'kode' => 'FTI',
        'nama' => 'Fakultas Teknologi Informasi',
        'dekan' => 'Dr. Budi',
    ]);
    Prodi::create([
        'kode_prodi' => 'TI',
        'nama_prodi' => 'Teknik Informatika',
        'fakultas_id' => $fakultas->id,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.fakultas.index'));

    $response->assertOk()
        ->assertViewIs('admin.fakultas.index')
        ->assertViewHas('fakultas')
        ->assertSee('Fakultas Teknologi Informasi')
        ->assertSee('FTI');
});

it('allows admin to view create fakultas page', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.fakultas.create'));

    $response->assertOk()
        ->assertViewIs('admin.fakultas.create');
});

it('allows admin to create a new fakultas', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.fakultas.store'), [
        'kode' => 'FEB',
        'nama' => 'Fakultas Ekonomi dan Bisnis',
        'dekan' => 'Prof. Dr. Siti',
    ]);

    $response->assertRedirect(route('admin.fakultas.index'));
    $this->assertDatabaseHas('fakultas', [
        'kode' => 'FEB',
        'nama' => 'Fakultas Ekonomi dan Bisnis',
        'dekan' => 'Prof. Dr. Siti',
    ]);
});

it('validates unique kode when creating fakultas', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    Fakultas::create([
        'kode' => 'FT',
        'nama' => 'Fakultas Teknik',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.fakultas.store'), [
        'kode' => 'FT',
        'nama' => 'Fakultas Teknik Lainnya',
    ]);

    $response->assertSessionHasErrors('kode');
});

it('allows admin to view edit fakultas page', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    $fakultas = Fakultas::create([
        'kode' => 'FT',
        'nama' => 'Fakultas Teknik',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.fakultas.edit', $fakultas));

    $response->assertOk()
        ->assertViewIs('admin.fakultas.edit')
        ->assertSee('Fakultas Teknik');
});

it('allows admin to update a fakultas', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    $fakultas = Fakultas::create([
        'kode' => 'FT',
        'nama' => 'Fakultas Teknik',
        'dekan' => 'Dekan Lama',
    ]);

    $response = $this->actingAs($admin)->put(route('admin.fakultas.update', $fakultas), [
        'kode' => 'FT',
        'nama' => 'Fakultas Teknik dan Sains',
        'dekan' => 'Dekan Baru',
    ]);

    $response->assertRedirect(route('admin.fakultas.index'));
    $this->assertDatabaseHas('fakultas', [
        'id' => $fakultas->id,
        'nama' => 'Fakultas Teknik dan Sains',
        'dekan' => 'Dekan Baru',
    ]);
});

it('allows admin to delete a fakultas', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    $fakultas = Fakultas::create([
        'kode' => 'FH',
        'nama' => 'Fakultas Hukum',
    ]);

    $response = $this->actingAs($admin)->delete(route('admin.fakultas.destroy', $fakultas));

    $response->assertRedirect(route('admin.fakultas.index'));
    $this->assertDatabaseMissing('fakultas', [
        'id' => $fakultas->id,
        'deleted_at' => null,
    ]);
});

it('allows creating prodi with fakultas_id and verifies relationship', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    $fakultas = Fakultas::create([
        'kode' => 'FIK',
        'nama' => 'Fakultas Ilmu Komunikasi',
    ]);

    $response = $this->actingAs($admin)->post(route('admin.prodi.store'), [
        'kode_prodi' => 'ILKOM',
        'nama_prodi' => 'Ilmu Komunikasi',
        'fakultas_id' => $fakultas->id,
    ]);

    $response->assertRedirect(route('admin.prodi.index'));
    $prodi = Prodi::where('kode', 'ILKOM')->firstOrFail();
    expect($prodi->fakultas_id)->toBe($fakultas->id)
        ->and($prodi->fakultas->kode)->toBe('FIK')
        ->and($fakultas->programStudi->pluck('id'))->toContain($prodi->id);
});

