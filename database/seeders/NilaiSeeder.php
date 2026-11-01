<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        $kelasList = Kelas::with('mahasiswas')->get();
        $mahasiswaList = Mahasiswa::all();

        if ($kelasList->isEmpty() || $mahasiswaList->isEmpty()) {
            $this->call([
                KelasSeeder::class,
                MahasiswaSeeder::class,
            ]);
            $kelasList = Kelas::with('mahasiswas')->get();
            $mahasiswaList = Mahasiswa::all();
        }

        $remaining = 30 - Nilai::count();

        if ($remaining <= 0) {
            return;
        }

        // Collect existing pairs to prevent duplicate (kelas_id, mahasiswa_id)
        $existingPairs = Nilai::query()
            ->select('kelas_id', 'mahasiswa_id')
            ->get()
            ->mapWithKeys(fn (Nilai $n): array => [$n->kelas_id.'-'.$n->mahasiswa_id => true])
            ->all();

        $created = 0;

        // First pass: try from enrolled students in each class
        foreach ($kelasList as $kelas) {
            if ($created >= $remaining) {
                break;
            }

            foreach ($kelas->mahasiswas as $mahasiswa) {
                if ($created >= $remaining) {
                    break;
                }

                $pairKey = $kelas->id.'-'.$mahasiswa->id;
                if (! isset($existingPairs[$pairKey])) {
                    Nilai::factory()->create([
                        'kelas_id' => $kelas->id,
                        'mahasiswa_id' => $mahasiswa->id,
                    ]);
                    $existingPairs[$pairKey] = true;
                    $created++;
                }
            }
        }

        // If still under 30, enroll random students to classes and create nilais
        while ($created < $remaining && $kelasList->isNotEmpty() && $mahasiswaList->isNotEmpty()) {
            $kelas = $kelasList->random();
            $mahasiswa = $mahasiswaList->random();

            $pairKey = $kelas->id.'-'.$mahasiswa->id;
            if (! isset($existingPairs[$pairKey])) {
                $kelas->mahasiswas()->syncWithoutDetaching([$mahasiswa->id]);
                Nilai::factory()->create([
                    'kelas_id' => $kelas->id,
                    'mahasiswa_id' => $mahasiswa->id,
                ]);
                $existingPairs[$pairKey] = true;
                $created++;
            }
        }
    }
}
