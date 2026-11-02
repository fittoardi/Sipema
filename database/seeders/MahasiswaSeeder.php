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
            $prodiTI = Prodi::where('kode', 'TI')->first() ?? Prodi::first();
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
                    'program_studi_id' => $prodiTI->id,
                    'dosen_wali_id' => $dosenWali?->id,
                    'angkatan' => 2023,
                ],
            );

            $prodis = Prodi::with('fakultas')->get();
            $dosens = Dosen::all();
            $remaining = 30 - Mahasiswa::count();

            if ($remaining > 0 && $prodis->isNotEmpty()) {
                for ($i = 0; $i < $remaining; $i++) {
                    $selectedProdi = $prodis[$i % $prodis->count()];
                    $fakultasKode = $selectedProdi->fakultas?->kode ?? 'UN';
                    $fakPrefix = match ($fakultasKode) {
                        'FTI' => '11',
                        'FT' => '21',
                        'FEB' => '31',
                        'FIK' => '41',
                        default => '51',
                    };
                    $nim = '23'.$fakPrefix.str_pad((string) ($i + 2), 4, '0', STR_PAD_LEFT);

                    $name = fake()->name();
                    $mhsUser = User::create([
                        'name' => $name,
                        'email' => fake()->unique()->safeEmail(),
                        'password' => 'password',
                        'role' => 'mahasiswa',
                    ]);

                    Mahasiswa::create([
                        'user_id' => $mhsUser->id,
                        'nama' => $name,
                        'nim' => $nim,
                        'program_studi_id' => $selectedProdi->id,
                        'dosen_wali_id' => $dosens->isNotEmpty() ? $dosens->random()->id : null,
                        'angkatan' => fake()->numberBetween(2021, 2024),
                    ]);
                }
            }
        });
    }
}
