<?php

namespace App\Services;

use App\Models\PengaturanNilai;
use App\Models\SkalaGrade;
use Illuminate\Support\Facades\Cache;

class NilaiCalculator
{
    /**
     * Hitung nilai akhir, grade, dan status kelulusan.
     *
     * @return array{nilai_akhir: ?float, grade: ?string, status: string}
     */
    public function hitung(?float $kuis, ?float $tugas, ?float $uts, ?float $uas): array
    {
        return $this->calculate($kuis, $tugas, $uts, $uas);
    }

    /**
     * Hitung nilai akhir, grade, dan status kelulusan (metode utama).
     *
     * @return array{nilai_akhir: ?float, grade: ?string, status: string}
     */
    public function calculate(?float $kuis, ?float $tugas, ?float $uts, ?float $uas): array
    {
        // Jika ada komponen yang belum diisi (null)
        if ($kuis === null || $tugas === null || $uts === null || $uas === null) {
            return [
                'nilai_akhir' => null,
                'grade' => null,
                'status' => 'Belum Lengkap',
            ];
        }

        $weights = $this->getWeights();
        $scales = $this->getGrades();

        $kuisWeight = $weights['kuis'] ?? 10.0;
        $tugasWeight = $weights['tugas'] ?? 20.0;
        $utsWeight = $weights['uts'] ?? 30.0;
        $uasWeight = $weights['uas'] ?? 40.0;

        $nilaiAkhir = round(
            (($kuis * $kuisWeight) + ($tugas * $tugasWeight) + ($uts * $utsWeight) + ($uas * $uasWeight)) / 100,
            2
        );

        $grade = 'E';
        $isLulus = false;

        foreach ($scales as $scale) {
            if ($nilaiAkhir >= $scale['min']) {
                $grade = $scale['grade'];
                $isLulus = (bool) $scale['is_lulus'];
                break;
            }
        }

        return [
            'nilai_akhir' => $nilaiAkhir,
            'grade' => $grade,
            'status' => $isLulus ? 'Lulus' : 'Tidak Lulus',
        ];
    }

    /**
     * @return array<string, float>
     */
    public function getWeights(): array
    {
        return Cache::remember('academic.weights', 3600, function (): array {
            try {
                $settings = PengaturanNilai::all();
                if ($settings->isNotEmpty()) {
                    $weights = [];
                    foreach ($settings as $setting) {
                        $weights[strtolower($setting->komponen)] = (float) $setting->bobot;
                    }

                    return $weights;
                }
            } catch (\Throwable) {
                // fallback to default
            }

            return [
                'kuis' => 10.0,
                'tugas' => 20.0,
                'uts' => 30.0,
                'uas' => 40.0,
            ];
        });
    }

    /**
     * @return array<int, array{grade: string, min: float, is_lulus: bool}>
     */
    public function getGrades(): array
    {
        return Cache::remember('academic.grades', 3600, function (): array {
            try {
                $grades = SkalaGrade::orderByDesc('nilai_min')->get();
                if ($grades->isNotEmpty()) {
                    return $grades->map(fn (SkalaGrade $g): array => [
                        'grade' => $g->grade,
                        'min' => (float) $g->nilai_min,
                        'is_lulus' => (bool) $g->is_lulus,
                    ])->all();
                }
            } catch (\Throwable) {
                // fallback to default
            }

            return [
                ['grade' => 'A', 'min' => 80.0, 'is_lulus' => true],
                ['grade' => 'B', 'min' => 70.0, 'is_lulus' => true],
                ['grade' => 'C', 'min' => 60.0, 'is_lulus' => true],
                ['grade' => 'D', 'min' => 50.0, 'is_lulus' => false],
                ['grade' => 'E', 'min' => 0.0, 'is_lulus' => false],
            ];
        });
    }

    /**
     * Clear cached weights and grades.
     */
    public function clearCache(): void
    {
        Cache::forget('academic.weights');
        Cache::forget('academic.grades');
    }
}
