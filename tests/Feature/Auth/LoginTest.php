<?php

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('login page can be rendered', function (): void {
    $response = $this->get('/login');

    $response->assertOk();
});

test('user can login with valid email credentials', function (): void {
    $user = User::factory()->create([
        'role' => 'admin',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'login' => $user->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('dosen can login with email or nidn', function (): void {
    $user = User::factory()->create([
        'role' => 'dosen',
        'password' => bcrypt('password123'),
    ]);

    $prodi = Prodi::factory()->create();
    Dosen::create([
        'user_id' => $user->id,
        'nidn' => '0012345678',
        'nama' => 'Dosen Test',
        'program_studi_id' => $prodi->id,
    ]);

    $response = $this->post('/login', [
        'login' => '0012345678',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('dosen.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('mahasiswa can login with nim', function (): void {
    $user = User::factory()->create([
        'role' => 'mahasiswa',
        'password' => bcrypt('password123'),
    ]);

    $prodi = Prodi::factory()->create();
    Mahasiswa::create([
        'user_id' => $user->id,
        'nama' => 'Mahasiswa Test',
        'nim' => '23110001',
        'program_studi_id' => $prodi->id,
        'angkatan' => 2023,
    ]);

    $response = $this->post('/login', [
        'login' => '23110001',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('mahasiswa.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('admin can login with username admin', function (): void {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'login' => 'admin',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($admin);
});

test('user cannot login with wrong password', function (): void {
    $user = User::factory()->create([
        'password' => bcrypt('password123'),
    ]);

    $response = $this->from('/login')->post('/login', [
        'login' => $user->email,
        'password' => 'salahpassword',
    ]);

    $response->assertRedirect('/login')
        ->assertSessionHasErrors('login');

    $this->assertGuest();
});

test('login fails if user not found', function (): void {
    $response = $this->from('/login')->post('/login', [
        'login' => 'tidakada@test.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect('/login')
        ->assertSessionHasErrors('login');

    $this->assertGuest();
});

test('login and password fields are required', function (): void {
    $response = $this->from('/login')->post('/login', [
        'login' => '',
        'password' => '',
    ]);

    $response->assertRedirect('/login')
        ->assertSessionHasErrors(['login', 'password']);
});

test('remember me can be toggled during login', function (): void {
    $user = User::factory()->create([
        'role' => 'admin',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'login' => $user->email,
        'password' => 'password123',
        'remember' => '1',
    ]);

    $response->assertRedirect(route('admin.dashboard'));
    $this->assertAuthenticatedAs($user);
});

test('authenticated user cannot access login page', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/login');

    $response->assertRedirect(route('admin.dashboard'));
});

test('authenticated user can logout', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');
    $this->assertGuest();
});
