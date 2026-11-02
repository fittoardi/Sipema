<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use Illuminate\Database\Seeder;

class FakultasSeeder extends Seeder
{
    public function run(): void
    {
        $fakultasData = [
            [
                'kode' => 'FTI',
                'nama' => 'Fakultas Teknologi Informasi',
                'dekan' => 'Prof. Dr. Ir. H. Bambang Sudarsono, M.Kom.',
            ],
            [
                'kode' => 'FT',
                'nama' => 'Fakultas Teknik',
                'dekan' => 'Dr. Eng. Ir. Hendra Gunawan, S.T., M.T.',
            ],
            [
                'kode' => 'FEB',
                'nama' => 'Fakultas Ekonomi dan Bisnis',
                'dekan' => 'Prof. Dr. Hj. Nurul Hidayati, S.E., M.Si.',
            ],
            [
                'kode' => 'FIK',
                'nama' => 'Fakultas Ilmu Komunikasi',
                'dekan' => 'Dr. Rahmat Hidayat, S.Sos., M.I.Kom.',
            ],
        ];

        foreach ($fakultasData as $data) {
            Fakultas::updateOrCreate(
                ['kode' => $data['kode']],
                [
                    'nama' => $data['nama'],
                    'dekan' => $data['dekan'],
                ]
            );
        }
    }
}

