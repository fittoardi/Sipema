<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[UseEloquentBuilder(Builder::class)]
class SkalaGrade extends Model
{
    use HasFactory;

    protected $table = 'skala_grades';

    protected $fillable = [
        'grade',
        'nilai_min',
        'is_lulus',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'nilai_min' => 'decimal:2',
            'is_lulus' => 'boolean',
            'urutan' => 'integer',
        ];
    }
}
