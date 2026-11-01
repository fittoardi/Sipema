<?php

namespace Database\Seeders;

use App\Models\KelasMahasiswa;
use App\Models\Nilai;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        $enrolled = KelasMahasiswa::with(['kelas', 'mahasiswa'])->get();

        foreach ($enrolled as $km) {
            $kuis = fake()->randomFloat(2, 60, 95);
            $tugas = fake()->randomFloat(2, 65, 98);
            $uts = fake()->randomFloat(2, 60, 90);
            $uas = fake()->randomFloat(2, 65, 95);

            $nilai = Nilai::firstOrNew([
                'kelas_mahasiswa_id' => $km->id,
            ]);

            $nilai->kuis = $kuis;
            $nilai->tugas = $tugas;
            $nilai->uts = $uts;
            $nilai->uas = $uas;
            $nilai->recalculate();
            $nilai->save();
        }
    }
}
