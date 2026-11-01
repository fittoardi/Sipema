<?php

namespace App\Models;

use App\Enums\Semester;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseEloquentBuilder(Builder::class)]
class TahunAkademik extends Model
{
    use HasFactory;

    protected $table = 'tahun_akademik';

    protected $fillable = [
        'tahun',
        'semester',
        'is_active',
        'is_krs_open',
        'krs_mulai',
        'krs_selesai',
        'maks_sks_default',
    ];

    protected function casts(): array
    {
        return [
            'semester' => Semester::class,
            'is_active' => 'boolean',
            'is_krs_open' => 'boolean',
            'krs_mulai' => 'date',
            'krs_selesai' => 'date',
            'maks_sks_default' => 'integer',
        ];
    }

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'tahun_akademik_id');
    }

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class, 'tahun_akademik_id');
    }
}
