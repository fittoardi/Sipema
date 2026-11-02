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
        $feb = Fakultas::where('kode', 'FEB')->first();
        $fik = Fakultas::where('kode', 'FIK')->first();

        $initialProdis = [
            ['kode' => 'TI', 'nama' => 'Teknik Informatika', 'fakultas_id' => $fti?->id],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi', 'fakultas_id' => $fti?->id],
            ['kode' => 'MI', 'nama' => 'Manajemen Informatika', 'fakultas_id' => $fti?->id],
            ['kode' => 'TE', 'nama' => 'Teknik Elektro', 'fakultas_id' => $ft?->id],
            ['kode' => 'TM', 'nama' => 'Teknik Mesin', 'fakultas_id' => $ft?->id],
            ['kode' => 'TS', 'nama' => 'Teknik Sipil', 'fakultas_id' => $ft?->id],
            ['kode' => 'MN', 'nama' => 'Manajemen Bisnis', 'fakultas_id' => $feb?->id],
            ['kode' => 'AK', 'nama' => 'Akuntansi Keuangan', 'fakultas_id' => $feb?->id],
            ['kode' => 'BD', 'nama' => 'Bisnis Digital', 'fakultas_id' => $feb?->id],
            ['kode' => 'IK', 'nama' => 'Ilmu Komunikasi', 'fakultas_id' => $fik?->id],
            ['kode' => 'PR', 'nama' => 'Hubungan Masyarakat', 'fakultas_id' => $fik?->id],
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

        $allFakultas = Fakultas::all();
        $currentCount = Prodi::count();
        if ($currentCount < 30) {
            for ($i = 0; $i < (30 - $currentCount); $i++) {
                Prodi::factory()->create([
                    'fakultas_id' => $allFakultas->isNotEmpty() ? $allFakultas->random()->id : null,
                ]);
            }
        }
    }
}
