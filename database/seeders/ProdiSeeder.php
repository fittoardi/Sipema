<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $fti = Fakultas::where('kode', 'FTI')->first();
        $ft = Fakultas::where('kode', 'FT')->first();

        $initialProdis = [
            ['kode' => 'TI', 'nama' => 'Teknik Informatika', 'fakultas_id' => $fti?->id],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi', 'fakultas_id' => $fti?->id],
            ['kode' => 'MI', 'nama' => 'Manajemen Informatika', 'fakultas_id' => $fti?->id],
            ['kode' => 'TE', 'nama' => 'Teknik Elektro', 'fakultas_id' => $ft?->id],
            ['kode' => 'TM', 'nama' => 'Teknik Mesin', 'fakultas_id' => $ft?->id],
        ];

        foreach ($initialProdis as $prodi) {
            Prodi::updateOrCreate(
                ['kode' => $prodi['kode']],
                [
                    'nama' => $prodi['nama'],
                    'fakultas_id' => $prodi['fakultas_id'],
                ],
            );
        }

        $currentCount = Prodi::count();
        if ($currentCount < 30) {
            Prodi::factory()->count(30 - $currentCount)->create();
        }
    }
}
