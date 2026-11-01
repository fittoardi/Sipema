<?php

namespace Database\Factories;

use App\Enums\Hari;
use App\Enums\TipeKelas;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\TahunAkademik;
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
            'tahun_akademik_id' => TahunAkademik::factory(),
            'ruangan_id' => Ruangan::factory(),
            'nama_kelas' => fake()->randomElement(['A', 'B', 'C', 'D', 'E', 'F']).'-'.fake()->unique()->numberBetween(100, 999),
            'tipe_kelas' => fake()->randomElement(TipeKelas::cases()),
            'hari' => fake()->randomElement(Hari::cases()),
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:30:00',
            'kapasitas' => 40,
        ];
    }
}
