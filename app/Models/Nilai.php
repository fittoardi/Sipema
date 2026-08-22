<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
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

    public function recalculate(): self
    {
        $weights = config('academic.weights');
        $finalScore = round(
            ((float) $this->kuis * $weights['kuis'])
            + ((float) $this->tugas * $weights['tugas'])
            + ((float) $this->uts * $weights['uts'])
            + ((float) $this->uas * $weights['uas']),
            2,
        );

        $grade = 'E';

        foreach (config('academic.grades') as $candidate => $range) {
            if ($finalScore >= $range['min'] && $finalScore <= $range['max']) {
                $grade = $candidate;
                break;
            }
        }

        $this->nilai_akhir = $finalScore;
        $this->grade = $grade;
        $this->status = $finalScore >= config('academic.passing_grade')
            ? 'Lulus'
            : 'Tidak Lulus';

        return $this;
    }
}
