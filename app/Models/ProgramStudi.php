<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[UseEloquentBuilder(Builder::class)]
class ProgramStudi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'program_studi';

    protected $fillable = [
        'fakultas_id',
        'kode',
        'nama',
        'kode_prodi',
        'nama_prodi',
    ];

    protected function kodeProdi(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['kode'] ?? null,
            set: fn ($value) => ['kode' => $value],
        );
    }

    protected function namaProdi(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['nama'] ?? null,
            set: fn ($value) => ['nama' => $value],
        );
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    public function mahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'program_studi_id');
    }

    public function dosens(): HasMany
    {
        return $this->hasMany(Dosen::class, 'program_studi_id');
    }

    public function mataKuliahs(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'program_studi_id');
    }
}
