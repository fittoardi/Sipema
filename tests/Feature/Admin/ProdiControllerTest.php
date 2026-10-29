<?php

use App\Models\Prodi;
use App\Models\User;

it('requires admin role for prodi resource', function (): void {
    $response = $this->get(route('admin.prodi.index'));

    $response->assertRedirect(route('login'));
});

it('allows admin to view list of prodi', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika']);

    $response = $this->actingAs($admin)->get(route('admin.prodi.index'));

    $response->assertOk()
        ->assertViewIs('admin.prodi.index')
        ->assertViewHas('prodis');
});

it('allows admin to create a new prodi', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->post(route('admin.prodi.store'), [
        'kode_prodi' => 'SI',
        'nama_prodi' => 'Sistem Informasi',
    ]);

    $response->assertRedirect(route('admin.prodi.index'));
    $this->assertDatabaseHas('prodis', [
        'kode_prodi' => 'SI',
        'nama_prodi' => 'Sistem Informasi',
    ]);
});

it('validates unique kode_prodi when creating prodi', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika']);

    $response = $this->actingAs($admin)->post(route('admin.prodi.store'), [
        'kode_prodi' => 'TI',
        'nama_prodi' => 'Teknik Industri',
    ]);

    $response->assertSessionHasErrors('kode_prodi');
});

it('allows admin to update a prodi', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    $prodi = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika']);

    $response = $this->actingAs($admin)->put(route('admin.prodi.update', $prodi), [
        'kode_prodi' => 'TI',
        'nama_prodi' => 'Teknik Informatika Update',
    ]);

    $response->assertRedirect(route('admin.prodi.index'));
    $this->assertDatabaseHas('prodis', [
        'id' => $prodi->id,
        'nama_prodi' => 'Teknik Informatika Update',
    ]);
});

it('allows admin to delete a prodi', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);
    $prodi = Prodi::create(['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika']);

    $response = $this->actingAs($admin)->delete(route('admin.prodi.destroy', $prodi));

    $response->assertRedirect(route('admin.prodi.index'));
    $this->assertDatabaseMissing('prodis', [
        'id' => $prodi->id,
    ]);
});
