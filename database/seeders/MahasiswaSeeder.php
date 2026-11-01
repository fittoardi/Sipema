<?php

namespace Database\Seeders;

use App\Models\Dosen;
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
            $prodi = Prodi::where('kode', 'TI')->first() ?? Prodi::first();
            $dosenWali = Dosen::first();

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
                    'program_studi_id' => $prodi->id,
                    'dosen_wali_id' => $dosenWali?->id,
                    'angkatan' => 2023,
                ],
            );

            $prodis = Prodi::all();
            $dosens = Dosen::all();
            $remaining = 30 - Mahasiswa::count();

            if ($remaining > 0 && $prodis->isNotEmpty()) {
                Mahasiswa::factory()->count($remaining)->create([
                    'prodi_id' => fn () => $prodis->random()->id,
                    'dosen_wali_id' => $dosens->isNotEmpty() ? fn () => $dosens->random()->id : null,
                ]);
            }
        });
    }
}
