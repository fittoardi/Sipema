<?php

namespace App\Models;

use App\Services\NilaiCalculator;
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
        'updated_by',
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

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isLulus(): bool
    {
        return strtolower((string) $this->status) === 'lulus';
    }

    public function recalculate(): self
    {
        $calculator = app(NilaiCalculator::class);

        $result = $calculator->calculate(
            $this->kuis !== null ? (float) $this->kuis : null,
            $this->tugas !== null ? (float) $this->tugas : null,
            $this->uts !== null ? (float) $this->uts : null,
            $this->uas !== null ? (float) $this->uas : null,
        );

        $this->nilai_akhir = $result['nilai_akhir'];
        $this->grade = $result['grade'];
        $this->status = $result['status'];

        return $this;
    }
}
