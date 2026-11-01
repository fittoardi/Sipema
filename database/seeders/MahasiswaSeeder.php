<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $prodi = Prodi::first() ?? Prodi::factory()->create();

            $user = User::updateOrCreate(
                ['email' => 'andi@sipenma.test'],
                [
                    'name' => 'Andi Pratama',
                    'password' => 'password',
                    'role' => 'mahasiswa',
                ],
            );

            Mahasiswa::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => 'Andi Pratama',
                    'nim' => '23110001',
                    'prodi_id' => $prodi->id,
                    'angkatan' => 2023,
                ],
            );

            $prodis = Prodi::all();
            $remaining = 30 - Mahasiswa::count();

            if ($remaining > 0 && $prodis->isNotEmpty()) {
                Mahasiswa::factory()->count($remaining)->create([
                    'prodi_id' => fn () => $prodis->random()->id,
                ]);
            }
        });
    }
}
