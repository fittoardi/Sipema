<?php

namespace Database\Seeders;

use App\Models\PengaturanNilai;
use Illuminate\Database\Seeder;

class PengaturanNilaiSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['komponen' => 'kuis', 'bobot' => 10],
            ['komponen' => 'tugas', 'bobot' => 20],
            ['komponen' => 'uts', 'bobot' => 30],
            ['komponen' => 'uas', 'bobot' => 40],
        ];

        foreach ($settings as $setting) {
            PengaturanNilai::updateOrCreate(
                ['komponen' => $setting['komponen']],
                ['bobot' => $setting['bobot']]
            );
        }
    }
}
