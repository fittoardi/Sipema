<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kelas>
 */
class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mata_kuliah_id' => MataKuliah::factory(),
            'dosen_id' => Dosen::factory(),
            'nama' => fake()->randomElement(['Kelas A', 'Kelas B', 'Kelas C', 'Kelas D', 'Rombel 1', 'Rombel 2']).' - '.fake()->randomLetter(),
            'tahun_akademik' => fake()->randomElement(['2023/2024', '2024/2025', '2025/2026']),
            'semester' => fake()->randomElement(['Ganjil', 'Genap']),
        ];
    }
}
