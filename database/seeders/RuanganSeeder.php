<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'kode' => 'LAB-KOMP-01',
                'nama' => 'Laboratorium Komputer 1',
                'gedung' => 'Gedung TI Lantai 2',
                'kapasitas' => 40,
                'tipe' => 'lab',
                'latitude' => -6.200000,
                'longitude' => 106.816666,
                'radius_meter' => 100,
            ],
            [
                'kode' => 'LAB-KOMP-02',
                'nama' => 'Laboratorium Jaringan & Keamanan',
                'gedung' => 'Gedung TI Lantai 2',
                'kapasitas' => 35,
                'tipe' => 'lab',
                'latitude' => -6.200100,
                'longitude' => 106.816700,
                'radius_meter' => 75,
            ],
            [
                'kode' => 'RT-301',
                'nama' => 'Ruang Kuliah Teori 301',
                'gedung' => 'Gedung Kuliah Bersama Lt 3',
                'kapasitas' => 50,
                'tipe' => 'kelas',
                'latitude' => -6.200500,
                'longitude' => 106.816800,
                'radius_meter' => 100,
            ],
            [
                'kode' => 'RT-302',
                'nama' => 'Ruang Kuliah Teori 302',
                'gedung' => 'Gedung Kuliah Bersama Lt 3',
                'kapasitas' => 50,
                'tipe' => 'kelas',
                'latitude' => -6.200600,
                'longitude' => 106.816900,
                'radius_meter' => 100,
            ],
            [
                'kode' => 'AUD-UTAMA',
                'nama' => 'Auditorium Utama Rektorat',
                'gedung' => 'Gedung Rektorat Lt 1',
                'kapasitas' => 200,
                'tipe' => 'auditorium',
                'latitude' => -6.201000,
                'longitude' => 106.817000,
                'radius_meter' => 150,
            ],
        ];

        foreach ($rooms as $room) {
            Ruangan::updateOrCreate(
                ['kode' => $room['kode']],
                $room,
            );
        }
    }
}
