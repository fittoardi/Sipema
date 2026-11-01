<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

#[UseEloquentBuilder(Builder::class)]
class PengaturanNilai extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_nilai';

    protected $fillable = [
        'komponen',
        'bobot',
    ];

    protected function casts(): array
    {
        return [
            'bobot' => 'float',
        ];
    }

    public static function getBobot(string $komponen, float $default = 0): float
    {
        return Cache::remember('bobot_nilai_'.$komponen, 3600, function () use ($komponen, $default) {
            $record = static::where('komponen', $komponen)->first();

            return $record ? (float) $record->bobot : $default;
        });
    }

    public static function clearCache(): void
    {
        Cache::forget('bobot_nilai_kuis');
        Cache::forget('bobot_nilai_tugas');
        Cache::forget('bobot_nilai_uts');
        Cache::forget('bobot_nilai_uas');
    }
}
