<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseEloquentBuilder(Builder::class)]
class Ruangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ruangan';

    protected $fillable = [
        'kode',
        'nama',
        'gedung',
        'kapasitas',
        'tipe',
        'latitude',
        'longitude',
        'radius_meter',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'radius_meter' => 'integer',
            'kapasitas' => 'integer',
        ];
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'ruangan_id');
    }

    public function pertemuan(): HasMany
    {
        return $this->hasMany(Pertemuan::class, 'ruangan_id');
    }
}

