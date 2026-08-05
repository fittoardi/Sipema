<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $user = User::create([
                'name' => 'Andi Pratama',
                'email' => 'andi@sipenma.test',
                'password' => bcrypt('password'),
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => '23110001',
                'prodi_id' => 1,
                'angkatan' => 2023,
            ]);

        });
    }
}
