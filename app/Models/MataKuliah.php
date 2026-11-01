<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseEloquentBuilder(Builder::class)]
class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = [
        'prodi_id',
        'kode',
        'nama',
        'sks',
    ];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class);
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }
}
