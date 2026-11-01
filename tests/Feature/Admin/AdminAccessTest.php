<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin visiting /admin redirects to /admin/dashboard', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get('/admin');

    $response->assertRedirect(route('admin.dashboard'));
});

test('admin can access /admin/dashboard directly', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.dashboard'));

    $response->assertOk();
});

test('admin is redirected to /admin/dashboard upon login', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post('/login', [
        'login' => $admin->email,
        'password' => 'password123',
    ]);

    $response->assertRedirect('/admin/dashboard');
    $this->assertAuthenticatedAs($admin);
});

test('guest visiting /admin or /admin/dashboard is redirected to login', function () {
    $this->get('/admin')->assertRedirect(route('login'));
    $this->get('/admin/dashboard')->assertRedirect(route('login'));
});

test('non-admin visiting /admin or /admin/dashboard receives forbidden', function () {
    $student = User::factory()->create(['role' => 'mahasiswa']);

    $this->actingAs($student)->get('/admin')->assertForbidden();
    $this->actingAs($student)->get('/admin/dashboard')->assertForbidden();
});

