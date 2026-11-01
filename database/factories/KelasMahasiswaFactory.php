<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KelasMahasiswa>
 */
class KelasMahasiswaFactory extends Factory
{
    protected $model = KelasMahasiswa::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kelas_id' => Kelas::factory(),
            'mahasiswa_id' => Mahasiswa::factory(),
            'krs_id' => null,
        ];
    }
}

