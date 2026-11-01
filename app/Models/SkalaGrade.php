<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[UseEloquentBuilder(Builder::class)]
class SkalaGrade extends Model
{
    use HasFactory;

    protected $table = 'skala_grade';

    protected $fillable = [
        'grade',
        'nilai_min',
        'is_lulus',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'nilai_min' => 'float',
            'is_lulus' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    public static function determineGrade(float $score): ?static
    {
        $grades = Cache::remember('skala_grade_all_records', 3600, function () {
            return static::orderBy('urutan', 'asc')->get();
        });

        if (! ($grades instanceof \Illuminate\Support\Collection)) {
            $grades = static::orderBy('urutan', 'asc')->get();
        }

        foreach ($grades as $gradeRecord) {
            if (is_object($gradeRecord) && isset($gradeRecord->nilai_min) && $score >= (float) $gradeRecord->nilai_min) {
                return $gradeRecord;
            }
        }

        return $grades instanceof \Illuminate\Support\Collection ? $grades->last() : static::orderBy('urutan', 'desc')->first();
    }

    public static function clearCache(): void
    {
        Cache::forget('skala_grade_all');
    }
}
