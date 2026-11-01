<?php

namespace Database\Factories;

use App\Models\Prodi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Prodi>
 */
class ProdiFactory extends Factory
{
    protected $model = Prodi::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => strtoupper(fake()->unique()->bothify('P##')),
            'nama' => fake()->randomElement([
                'Teknik Informatika',
                'Sistem Informasi',
                'Teknik Elektro',
                'Teknik Mesin',
                'Teknik Sipil',
                'Teknik Industri',
                'Manajemen Informatika',
                'Ilmu Komputer',
                'Teknologi Rekayasa Perangkat Lunak',
                'Bisnis Digital',
                'Sains Data',
                'Kecerdasan Buatan',
            ]).' '.fake()->unique()->numberBetween(1, 99),
        ];
    }
}
