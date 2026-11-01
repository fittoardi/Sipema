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
            'kode_prodi' => strtoupper(fake()->unique()->bothify('P##')),
            'nama_prodi' => fake()->randomElement([
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
                'Desain Komunikasi Visual',
                'Rekayasa Keselamatan Kerja',
                'Teknik Mekatronika',
                'Teknik Otomasi',
                'Teknik Telekomunikasi',
                'Teknik Komputer',
                'Bioteknologi',
                'Arsitektur',
                'Teknik Kimia',
                'Teknik Lingkungan',
                'Teknik Pertambangan',
                'Teknik Perminyakan',
                'Teknik Nuklir',
                'Fisika Terapan',
                'Matematika Komputasi',
                'Statistika Terapan',
                'Sistem Cerdas',
                'Teknik Robotika',
            ]).' '.fake()->unique()->numberBetween(1, 999),
        ];
    }
}
