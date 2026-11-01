<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseEloquentBuilder(Builder::class)]
class Materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'pertemuan_id',
        'judul',
        'tipe',
        'konten_atau_url',
        'url',
        'konten',
        'file_path',
        'urutan',
    ];

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['konten_atau_url'] ?? null,
            set: fn ($val) => ['konten_atau_url' => $val],
        );
    }

    protected function konten(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['konten_atau_url'] ?? null,
            set: fn ($val) => ['konten_atau_url' => $val],
        );
    }

    protected function casts(): array
    {
        return [
            'urutan' => 'integer',
        ];
    }

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id');
    }

    public function progresMateri(): HasMany
    {
        return $this->hasMany(ProgresMateri::class, 'materi_id');
    }
}
