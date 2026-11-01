<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $prodi = Prodi::where('kode', 'TI')->first() ?? Prodi::first();

            $user = User::updateOrCreate(
                ['email' => 'budi@sipenma.test'],
                [
                    'name' => 'Dr. Budi Santoso',
                    'password' => 'password',
                    'role' => 'dosen',
                ],
            );

            Dosen::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => 'Dr. Budi Santoso',
                    'nidn' => '0012345678',
                    'email' => 'budi@sipenma.test',
                    'nip' => '198501012010011001',
                    'program_studi_id' => $prodi->id,
                ],
            );

            $prodis = Prodi::all();
            $remaining = 15 - Dosen::count();

            if ($remaining > 0 && $prodis->isNotEmpty()) {
                Dosen::factory()->count($remaining)->create([
                    'prodi_id' => fn () => $prodis->random()->id,
                ]);
            }
        });
    }
}
