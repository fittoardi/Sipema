<?php

namespace Tests\Feature\Auth;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'login' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    /** @test */
    public function dosen_user_is_redirected_to_dosen_dashboard_after_login()
    {
        $user = User::factory()->create([
            'role' => 'dosen',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'login' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dosen/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function mahasiswa_can_login_with_nim()
    {
        $user = User::factory()->create([
            'role' => 'mahasiswa',
            'password' => bcrypt('password123'),
        ]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => '23110001',
            'prodi_id' => 1,
            'angkatan' => 2023,
        ]);

        $response = $this->post('/login', [
            'login' => '23110001',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/mahasiswa/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->from('/login')->post('/login', [
            'login' => $user->email,
            'password' => 'salahpassword',
        ]);

        $response
            ->assertRedirect('/login')
            ->assertSessionHasErrors();

        $this->assertGuest();
    }

    /** @test */
    public function login_fails_if_user_not_found()
    {
        $response = $this->from('/login')->post('/login', [
            'login' => 'user@test.com',
            'password' => 'password123',
        ]);

        $response
            ->assertRedirect('/login')
            ->assertSessionHasErrors();

        $this->assertGuest();
    }

    /** @test */
    public function login_field_is_required()
    {
        $response = $this->from('/login')->post('/login', [
            'login' => '',
            'password' => 'password123',
        ]);

        $response
            ->assertRedirect('/login')
            ->assertSessionHasErrors('login');
    }

    /** @test */
    public function password_field_is_required()
    {
        $response = $this->from('/login')->post('/login', [
            'login' => 'admin@test.com',
            'password' => '',
        ]);

        $response
            ->assertRedirect('/login')
            ->assertSessionHasErrors('password');
    }

    /** @test */
    public function remember_me_login()
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'login' => $user->email,
            'password' => 'password123',
            'remember' => true,
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertAuthenticated();
    }

    /** @test */
    public function authenticated_user_cannot_access_login_page()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/login');

        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');

        $this->assertGuest();
    }
}
