<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run(): void
    {
        $prodis = [
            [
                'kode_prodi' => 'TI',
                'nama_prodi' => 'Teknik Informatika',
            ],
            [
                'kode_prodi' => 'SI',
                'nama_prodi' => 'Sistem Informasi',
            ],
            [
                'kode_prodi' => 'MI',
                'nama_prodi' => 'Manajemen Informatika',
            ],
            [
                'kode_prodi' => 'TE',
                'nama_prodi' => 'Teknik Elektro',
            ],
            [
                'kode_prodi' => 'TM',
                'nama_prodi' => 'Teknik Mesin',
            ],
        ];

        foreach ($prodis as $prodi) {
            Prodi::create($prodi);
        }
    }
}
