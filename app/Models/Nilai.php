<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseEloquentBuilder(Builder::class)]
class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'mahasiswa_id',
        'kuis',
        'tugas',
        'uts',
        'uas',
        'nilai_akhir',
        'grade',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'kuis' => 'decimal:2',
            'tugas' => 'decimal:2',
            'uts' => 'decimal:2',
            'uas' => 'decimal:2',
            'nilai_akhir' => 'decimal:2',
        ];
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function isLulus(): bool
    {
        return $this->status === 'Lulus';
    }

    public function recalculate(): self
    {
        /** @var array<string, float> $weights */
        $weights = config('academic.weights', [
            'kuis' => 0.1,
            'tugas' => 0.2,
            'uts' => 0.3,
            'uas' => 0.4,
        ]);

        $finalScore = round(
            ((float) $this->kuis * ($weights['kuis'] ?? 0.1))
            + ((float) $this->tugas * ($weights['tugas'] ?? 0.2))
            + ((float) $this->uts * ($weights['uts'] ?? 0.3))
            + ((float) $this->uas * ($weights['uas'] ?? 0.4)),
            2,
        );

        $grade = 'E';
        /** @var array<string, array{min: float|int, max: float|int}> $grades */
        $grades = config('academic.grades', []);

        foreach ($grades as $candidate => $range) {
            if ($finalScore >= $range['min'] && $finalScore <= $range['max']) {
                $grade = $candidate;
                break;
            }
        }

        $passingGrade = (float) config('academic.passing_grade', 55);

        $this->nilai_akhir = $finalScore;
        $this->grade = $grade;
        $this->status = $finalScore >= $passingGrade ? 'Lulus' : 'Tidak Lulus';

        return $this;
    }
}
