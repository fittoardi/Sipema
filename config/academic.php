<?php

return [
    'weights' => [
        'kuis' => (float) env('ACADEMIC_WEIGHT_KUIS', 0.10),
        'tugas' => (float) env('ACADEMIC_WEIGHT_TUGAS', 0.20),
        'uts' => (float) env('ACADEMIC_WEIGHT_UTS', 0.30),
        'uas' => (float) env('ACADEMIC_WEIGHT_UAS', 0.40),
    ],

    'grades' => [
        'A' => ['min' => 85, 'max' => 100],
        'B' => ['min' => 70, 'max' => 84.99],
        'C' => ['min' => 55, 'max' => 69.99],
        'D' => ['min' => 40, 'max' => 54.99],
        'E' => ['min' => 0, 'max' => 39.99],
    ],

    'passing_grade' => (float) env('ACADEMIC_PASSING_GRADE', 55),
];
