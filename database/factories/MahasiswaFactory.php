<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    protected $model = Mahasiswa::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'user_id' => User::factory()->mahasiswa()->state(['name' => $name]),
            'nama' => $name,
            'nim' => fake()->unique()->numerify('24######'),
            'prodi_id' => Prodi::factory(),
            'angkatan' => fake()->numberBetween(2021, 2025),
        ];
    }
}
