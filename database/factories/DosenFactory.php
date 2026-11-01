<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dosen>
 */
class DosenFactory extends Factory
{
    protected $model = Dosen::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'user_id' => User::factory()->dosen()->state(['name' => $name]),
            'nama' => $name,
            'image' => null,
            'nidn' => fake()->unique()->numerify('00########'),
            'email' => fake()->unique()->safeEmail(),
            'nip' => fake()->unique()->numerify('198#########00#'),
            'prodi_id' => Prodi::factory(),
        ];
    }
}
