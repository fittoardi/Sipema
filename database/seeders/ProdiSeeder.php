<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $initialProdis = [
            ['kode' => 'TI', 'nama' => 'Teknik Informatika'],
            ['kode' => 'SI', 'nama' => 'Sistem Informasi'],
            ['kode' => 'MI', 'nama' => 'Manajemen Informatika'],
            ['kode' => 'TE', 'nama' => 'Teknik Elektro'],
            ['kode' => 'TM', 'nama' => 'Teknik Mesin'],
        ];

        foreach ($initialProdis as $prodi) {
            Prodi::updateOrCreate(
                ['kode' => $prodi['kode']],
                ['nama' => $prodi['nama']],
            );
        }

        $currentCount = Prodi::count();
        if ($currentCount < 10) {
            Prodi::factory()->count(10 - $currentCount)->create();
        }
    }
}
