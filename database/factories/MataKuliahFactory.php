<?php

namespace Database\Factories;

use App\Models\MataKuliah;
use App\Models\Prodi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MataKuliah>
 */
class MataKuliahFactory extends Factory
{
    protected $model = MataKuliah::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prodi_id' => Prodi::factory(),
            'kode' => strtoupper(fake()->unique()->bothify('MK###')),
            'nama' => fake()->randomElement([
                'Pemrograman Berorientasi Objek',
                'Struktur Data & Algoritma',
                'Sistem Basis Data',
                'Jaringan Komputer',
                'Kecerdasan Buatan',
                'Rekayasa Perangkat Lunak',
                'Keamanan Siber',
                'Pemrograman Web Lanjutan',
                'Cloud Computing',
                'Arsitektur Komputer',
                'Interaksi Manusia dan Komputer',
                'Pengolahan Citra Digital',
                'Machine Learning',
                'Analisis Desain Sistem',
                'Sistem Informasi Manajemen',
                'Metodologi Penelitian',
                'Etika Profesi IT',
                'Komputasi Terdistribusi',
                'Pemrograman Mobile',
                'Manajemen Proyek Perangkat Lunak',
            ]).' '.fake()->unique()->numberBetween(1, 999),
            'sks' => fake()->randomElement([2, 3, 4]),
        ];
    }
}
