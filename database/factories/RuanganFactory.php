<?php

namespace Database\Factories;

use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ruangan>
 */
class RuanganFactory extends Factory
{
    protected $model = Ruangan::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => 'R-'.fake()->unique()->numerify('###'),
            'nama' => 'Ruang Teori '.fake()->unique()->numberBetween(100, 499),
            'gedung' => 'Gedung '.fake()->randomElement(['A', 'B', 'C', 'D', 'TI', 'Elektro']),
            'kapasitas' => fake()->randomElement([30, 40, 50, 60]),
            'tipe' => fake()->randomElement(['kelas', 'lab', 'auditorium']),
            'latitude' => -6.200000 + (fake()->numberBetween(-50, 50) * 0.0001),
            'longitude' => 106.816666 + (fake()->numberBetween(-50, 50) * 0.0001),
            'radius_meter' => 100,
        ];
    }
}

