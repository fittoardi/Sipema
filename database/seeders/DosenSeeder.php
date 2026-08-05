<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $user = User::create([
                'name' => 'Dr. Budi Santoso',
                'email' => 'budi@sipenma.test',
                'password' => bcrypt('password'),
                'role' => 'dosen',
            ]);

            Dosen::create([
                'user_id' => $user->id,
                'nidn' => '0012345678',
                'nip' => '198501012010011001',
                'prodi_id' => 1,
            ]);

        });
    }
}
