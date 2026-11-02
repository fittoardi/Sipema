<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PengaturanNilaiSeeder::class,
            SkalaGradeSeeder::class,
            TahunAkademikSeeder::class,
            FakultasSeeder::class,
            ProdiSeeder::class,
            RuanganSeeder::class,
            AdminSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,
            MataKuliahSeeder::class,
            KelasSeeder::class,
            NilaiSeeder::class,
        ]);
    }
}
