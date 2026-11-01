<?php

namespace Database\Seeders;

use App\Models\TahunAkademik;
use Illuminate\Database\Seeder;

class TahunAkademikSeeder extends Seeder
{
    public function run(): void
    {
        $years = [
            [
                'tahun' => '2024/2025',
                'semester' => 'Ganjil',
                'is_active' => false,
                'is_krs_open' => false,
                'krs_mulai' => '2024-08-01',
                'krs_selesai' => '2024-08-20',
                'maks_sks_default' => 24,
            ],
            [
                'tahun' => '2024/2025',
                'semester' => 'Genap',
                'is_active' => false,
                'is_krs_open' => false,
                'krs_mulai' => '2025-01-10',
                'krs_selesai' => '2025-01-30',
                'maks_sks_default' => 24,
            ],
            [
                'tahun' => '2025/2026',
                'semester' => 'Ganjil',
                'is_active' => false,
                'is_krs_open' => false,
                'krs_mulai' => '2025-08-01',
                'krs_selesai' => '2025-08-20',
                'maks_sks_default' => 24,
            ],
            [
                'tahun' => '2025/2026',
                'semester' => 'Genap',
                'is_active' => false,
                'is_krs_open' => false,
                'krs_mulai' => '2026-01-10',
                'krs_selesai' => '2026-01-30',
                'maks_sks_default' => 24,
            ],
            [
                'tahun' => '2026/2027',
                'semester' => 'Ganjil',
                'is_active' => true,
                'is_krs_open' => true,
                'krs_mulai' => now()->subDays(5)->toDateString(),
                'krs_selesai' => now()->addDays(20)->toDateString(),
                'maks_sks_default' => 24,
            ],
            [
                'tahun' => '2026/2027',
                'semester' => 'Genap',
                'is_active' => false,
                'is_krs_open' => false,
                'krs_mulai' => '2027-01-10',
                'krs_selesai' => '2027-01-30',
                'maks_sks_default' => 24,
            ],
        ];

        foreach ($years as $item) {
            TahunAkademik::updateOrCreate(
                ['tahun' => $item['tahun'], 'semester' => $item['semester']],
                $item,
            );
        }
    }
}
