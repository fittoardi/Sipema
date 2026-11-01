<?php

namespace Database\Seeders;

use App\Models\TahunAkademik;
use Illuminate\Database\Seeder;

class TahunAkademikSeeder extends Seeder
{
    public function run(): void
    {
        $years = [
            ['tahun' => '2024/2025', 'semester' => 'Ganjil', 'is_active' => false],
            ['tahun' => '2024/2025', 'semester' => 'Genap', 'is_active' => false],
            ['tahun' => '2025/2026', 'semester' => 'Ganjil', 'is_active' => false],
            ['tahun' => '2025/2026', 'semester' => 'Genap', 'is_active' => false],
            ['tahun' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => true],
            ['tahun' => '2026/2027', 'semester' => 'Genap', 'is_active' => false],
        ];

        foreach ($years as $item) {
            TahunAkademik::updateOrCreate(
                ['tahun' => $item['tahun'], 'semester' => $item['semester']],
                ['is_active' => $item['is_active']]
            );
        }
    }
}
