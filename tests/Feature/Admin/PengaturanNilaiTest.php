<?php

use App\Models\PengaturanNilai;
use App\Models\User;
use App\Services\NilaiCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

test('guest cannot access pengaturan nilai', function () {
    $response = $this->get(route('admin.pengaturan-nilai.index'));

    $response->assertRedirect(route('login'));
});

test('non-admin user is forbidden from pengaturan nilai', function () {
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

    $response = $this->actingAs($mahasiswa)->get(route('admin.pengaturan-nilai.index'));

    $response->assertForbidden();
});

test('admin can view pengaturan nilai page with weights and grade scales', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.pengaturan-nilai.index'));

    $response->assertOk()
        ->assertViewIs('admin.pengaturan-nilai.index')
        ->assertViewHasAll(['weights', 'grades']);
});

test('admin cannot save weights that do not sum to 100', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->put(route('admin.pengaturan-nilai.update'), [
        'weights' => [
            'kuis' => 10,
            'tugas' => 10,
            'uts' => 10,
            'uas' => 10, // Total = 40 != 100
        ],
        'grades' => [
            ['grade' => 'A', 'min' => 80, 'is_lulus' => 1],
        ],
    ]);

    $response->assertSessionHas('error');
});

test('admin can update weights and grade scales successfully', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->put(route('admin.pengaturan-nilai.update'), [
        'weights' => [
            'kuis' => 15,
            'tugas' => 25,
            'uts' => 25,
            'uas' => 35,
        ],
        'grades' => [
            ['grade' => 'A', 'min' => 85, 'is_lulus' => 1],
            ['grade' => 'B', 'min' => 70, 'is_lulus' => 1],
            ['grade' => 'C', 'min' => 55, 'is_lulus' => 1],
            ['grade' => 'D', 'min' => 40, 'is_lulus' => 0],
            ['grade' => 'E', 'min' => 0, 'is_lulus' => 0],
        ],
    ]);

    $response->assertRedirect(route('admin.pengaturan-nilai.index'))
        ->assertSessionHas('success');

    $calculator = app(NilaiCalculator::class);
    $weights = $calculator->getWeights();
    expect($weights['kuis'])->toBe(15.0)
        ->and($weights['tugas'])->toBe(25.0)
        ->and($weights['uts'])->toBe(25.0)
        ->and($weights['uas'])->toBe(35.0);
});

test('nilai calculator computes correct final score, grade and status with dynamic settings', function () {
    PengaturanNilai::updateOrCreate(['komponen' => 'kuis'], ['bobot' => 10]);
    PengaturanNilai::updateOrCreate(['komponen' => 'tugas'], ['bobot' => 20]);
    PengaturanNilai::updateOrCreate(['komponen' => 'uts'], ['bobot' => 30]);
    PengaturanNilai::updateOrCreate(['komponen' => 'uas'], ['bobot' => 40]);
    app(NilaiCalculator::class)->clearCache();

    $calculator = app(NilaiCalculator::class);
    $result = $calculator->calculate(kuis: 80, tugas: 80, uts: 80, uas: 80);

    expect($result['nilai_akhir'])->toBe(80.0)
        ->and($result['grade'])->toBe('A')
        ->and($result['status'])->toBe('Lulus');
});

test('nilai recalculate artisan command runs without errors', function () {
    $exitCode = Artisan::call('nilai:recalculate', ['--dry-run' => true]);

    expect($exitCode)->toBe(0);
});
