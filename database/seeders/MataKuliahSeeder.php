<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        $prodis = Prodi::all();

        if ($prodis->isEmpty()) {
            $this->call(ProdiSeeder::class);
            $prodis = Prodi::all();
        }

        $remaining = 30 - MataKuliah::count();

        if ($remaining > 0) {
            MataKuliah::factory()->count($remaining)->create([
                'prodi_id' => fn () => $prodis->random()->id,
            ]);
        }
    }
}
