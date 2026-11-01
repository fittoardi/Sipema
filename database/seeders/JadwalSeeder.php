<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Kelas;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = Kelas::with('mataKuliah')->get();

        if ($kelasList->isEmpty()) {
            $this->call(KelasSeeder::class);
            $kelasList = Kelas::with('mataKuliah')->get();
        }

        $remaining = 30 - Jadwal::count();

        if ($remaining > 0) {
            foreach ($kelasList->take($remaining) as $kelas) {
                Jadwal::factory()->create([
                    'kelas_id' => $kelas->id,
                    'mata_kuliah_id' => $kelas->mata_kuliah_id,
                ]);
            }

            // If we still need more to reach 30 (in case there were fewer than 30 classes):
            $stillRemaining = 30 - Jadwal::count();
            if ($stillRemaining > 0) {
                for ($i = 0; $i < $stillRemaining; $i++) {
                    $randomKelas = $kelasList->random();
                    Jadwal::factory()->create([
                        'kelas_id' => $randomKelas->id,
                        'mata_kuliah_id' => $randomKelas->mata_kuliah_id,
                    ]);
                }
            }
        }
    }
}
