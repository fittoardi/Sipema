<?php

use App\Models\User;

it('requires admin role for laporan', function (): void {
    $response = $this->get(route('admin.laporan.index'));

    $response->assertRedirect(route('login'));
});

it('allows admin to view laporan page', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.laporan.index'));

    $response->assertOk()
        ->assertViewIs('admin.laporan.index')
        ->assertViewHasAll(['nilais', 'prodis', 'kelas']);
});

it('allows admin to download laporan csv', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.laporan.export'));

    $response->assertOk()
        ->assertHeader('content-type', 'text/csv; charset=UTF-8')
        ->assertHeader('content-disposition', 'attachment; filename="laporan-nilai-'.date('Y-m-d').'.csv"');
});

it('allows admin to download laporan excel', function (): void {
    $admin = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($admin)->get(route('admin.laporan.export-excel'));

    $response->assertOk()
        ->assertHeader('content-type', 'application/vnd.ms-excel')
        ->assertHeader('content-disposition', 'attachment; filename=laporan-nilai-'.date('Y-m-d').'.xls');
});
