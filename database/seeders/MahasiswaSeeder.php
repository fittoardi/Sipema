<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

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
                    'prodi_id' => 1,
                    'angkatan' => 2023,
                ],
            );

        });
    }
}
