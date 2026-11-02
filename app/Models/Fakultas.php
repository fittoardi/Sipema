<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseEloquentBuilder(Builder::class)]
class Fakultas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fakultas';

    protected $fillable = [
        'kode',
        'nama',
        'dekan',
    ];

    public function programStudi(): HasMany
    {
        return $this->hasMany(ProgramStudi::class, 'fakultas_id');
    }

    public function prodis(): HasMany
    {
        return $this->hasMany(Prodi::class, 'fakultas_id');
    }
}

