<?php

namespace Database\Seeders;

use App\Models\SkalaGrade;
use Illuminate\Database\Seeder;

class SkalaGradeSeeder extends Seeder
{
    public function run(): void
    {
        $grades = [
            ['grade' => 'A', 'nilai_min' => 80, 'is_lulus' => true, 'urutan' => 1],
            ['grade' => 'B', 'nilai_min' => 70, 'is_lulus' => true, 'urutan' => 2],
            ['grade' => 'C', 'nilai_min' => 60, 'is_lulus' => true, 'urutan' => 3],
            ['grade' => 'D', 'nilai_min' => 50, 'is_lulus' => false, 'urutan' => 4],
            ['grade' => 'E', 'nilai_min' => 0, 'is_lulus' => false, 'urutan' => 5],
        ];

        foreach ($grades as $item) {
            SkalaGrade::updateOrCreate(
                ['grade' => $item['grade']],
                [
                    'nilai_min' => $item['nilai_min'],
                    'is_lulus' => $item['is_lulus'],
                    'urutan' => $item['urutan'],
                ]
            );
        }
    }
}
