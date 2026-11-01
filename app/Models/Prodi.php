<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseEloquentBuilder(Builder::class)]
class Prodi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
    ];

    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function dosens(): HasMany
    {
        return $this->hasMany(Dosen::class);
    }

    public function mataKuliahs(): HasMany
    {
        return $this->hasMany(MataKuliah::class);
    }
}
