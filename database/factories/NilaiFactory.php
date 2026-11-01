<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Nilai>
 */
class NilaiFactory extends Factory
{
    protected $model = Nilai::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kuis = fake()->randomFloat(2, 50, 100);
        $tugas = fake()->randomFloat(2, 55, 100);
        $uts = fake()->randomFloat(2, 45, 100);
        $uas = fake()->randomFloat(2, 50, 100);

        return [
            'kelas_id' => Kelas::factory(),
            'mahasiswa_id' => Mahasiswa::factory(),
            'kuis' => $kuis,
            'tugas' => $tugas,
            'uts' => $uts,
            'uas' => $uas,
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Nilai $nilai): void {
            $nilai->recalculate();
        })->afterCreating(function (Nilai $nilai): void {
            $nilai->recalculate()->saveQuietly();
        });
    }
}
