<?php

namespace Database\Factories;

use App\Enums\Semester;
use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TahunAkademik>
 */
class TahunAkademikFactory extends Factory
{
    protected $model = TahunAkademik::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->unique()->numberBetween(2020, 2035);

        return [
            'tahun' => "{$year}/".($year + 1),
            'semester' => fake()->randomElement(Semester::cases()),
            'is_active' => false,
            'is_krs_open' => false,
            'krs_mulai' => now()->startOfMonth(),
            'krs_selesai' => now()->endOfMonth(),
            'maks_sks_default' => 24,
        ];
    }
}

