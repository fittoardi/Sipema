<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $mataKuliahs = MataKuliah::all();
        $dosens = Dosen::all();
        $mahasiswas = Mahasiswa::all();

        if ($mataKuliahs->isEmpty()) {
            $this->call(MataKuliahSeeder::class);
            $mataKuliahs = MataKuliah::all();
        }

        if ($dosens->isEmpty()) {
            $this->call(DosenSeeder::class);
            $dosens = Dosen::all();
        }

        if ($mahasiswas->isEmpty()) {
            $this->call(MahasiswaSeeder::class);
            $mahasiswas = Mahasiswa::all();
        }

        $remaining = 30 - Kelas::count();

        if ($remaining > 0) {
            $newKelas = Kelas::factory()->count($remaining)->create([
                'mata_kuliah_id' => fn () => $mataKuliahs->random()->id,
                'dosen_id' => fn () => $dosens->random()->id,
            ]);

            foreach ($newKelas as $kelas) {
                $attachCount = min(fake()->numberBetween(3, 8), $mahasiswas->count());
                $enrolled = $mahasiswas->random($attachCount);
                $kelas->mahasiswas()->syncWithoutDetaching($enrolled->pluck('id')->all());
            }
        }
    }
}
