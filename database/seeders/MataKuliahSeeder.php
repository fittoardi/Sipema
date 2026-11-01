<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $ti = Prodi::where('kode', 'TI')->first() ?? Prodi::first();

        $courses = [
            ['kode' => 'TI-201', 'nama' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'semester_paket' => 3],
            ['kode' => 'TI-202', 'nama' => 'Basis Data Lanjutan', 'sks' => 3, 'semester_paket' => 3],
            ['kode' => 'TI-203', 'nama' => 'Pemrograman Web Modern', 'sks' => 3, 'semester_paket' => 3],
            ['kode' => 'TI-204', 'nama' => 'Jaringan Komputer', 'sks' => 3, 'semester_paket' => 3],
            ['kode' => 'TI-301', 'nama' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'semester_paket' => 5],
            ['kode' => 'TI-302', 'nama' => 'Kecerdasan Buatan & ML', 'sks' => 3, 'semester_paket' => 5],
            ['kode' => 'TI-303', 'nama' => 'Keamanan Siber & Kriptografi', 'sks' => 3, 'semester_paket' => 5],
            ['kode' => 'TI-401', 'nama' => 'Manajemen Proyek TI', 'sks' => 2, 'semester_paket' => 7],
        ];

        foreach ($courses as $c) {
            MataKuliah::updateOrCreate(
                ['kode' => $c['kode']],
                [
                    'program_studi_id' => $ti->id,
                    'nama' => $c['nama'],
                    'sks' => $c['sks'],
                    'semester_paket' => $c['semester_paket'],
                ],
            );
        }

        $prodis = Prodi::all();
        $remaining = 20 - MataKuliah::count();

        if ($remaining > 0) {
            MataKuliah::factory()->count($remaining)->create([
                'prodi_id' => fn () => $prodis->random()->id,
            ]);
        }
    }
}
