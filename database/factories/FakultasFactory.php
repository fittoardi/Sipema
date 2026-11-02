<?php

namespace Database\Factories;

use App\Models\Fakultas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fakultas>
 */
class FakultasFactory extends Factory
{
    protected $model = Fakultas::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => strtoupper(fake()->unique()->bothify('F?#')),
            'nama' => 'Fakultas '.fake()->unique()->words(2, true),
            'dekan' => fake()->name(),
        ];
    }
}

