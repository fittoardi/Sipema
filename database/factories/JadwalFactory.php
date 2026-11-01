<?php

namespace Database\Factories;

use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataKuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Jadwal>
 */
class JadwalFactory extends Factory
{
    protected $model = Jadwal::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hour = fake()->randomElement([8, 10, 13, 15]);
        $jamMulai = sprintf('%02d:00:00', $hour);
        $jamSelesai = sprintf('%02d:40:00', $hour + 2);

        return [
            'mata_kuliah_id' => MataKuliah::factory(),
            'kelas_id' => Kelas::factory(),
            'hari' => fake()->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']),
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'ruangan' => fake()->randomElement([
                'Lab Komputer 1',
                'Lab Komputer 2',
                'Lab Multimedia',
                'Lab Jaringan & Siber',
                'Ruang Kuliah 201',
                'Ruang Kuliah 202',
                'Ruang Kuliah 301',
                'Ruang Kuliah 302',
                'Auditorium Utama',
            ]),
        ];
    }
}
