<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $initialProdis = [
            ['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika'],
            ['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi'],
            ['kode_prodi' => 'MI', 'nama_prodi' => 'Manajemen Informatika'],
            ['kode_prodi' => 'TE', 'nama_prodi' => 'Teknik Elektro'],
            ['kode_prodi' => 'TM', 'nama_prodi' => 'Teknik Mesin'],
        ];

        foreach ($initialProdis as $prodi) {
            Prodi::updateOrCreate(
                ['kode_prodi' => $prodi['kode_prodi']],
                ['nama_prodi' => $prodi['nama_prodi']],
            );
        }

        $currentCount = Prodi::count();
        if ($currentCount < 30) {
            Prodi::factory()->count(30 - $currentCount)->create();
        }
    }
}
