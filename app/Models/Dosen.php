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
class Dosen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dosen';

    protected $fillable = [
        'user_id',
        'program_studi_id',
        'prodi_id',
        'nidn',
        'nip',
        'nama',
        'email',
    ];

    protected static function booted(): void
    {
        static::creating(function (Dosen $dosen): void {
            if (empty($dosen->nidn)) {
                $dosen->nidn = fake()->unique()->numerify('00########');
            }
        });
    }

    protected function prodiId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['program_studi_id'] ?? null,
            set: fn ($value) => ['program_studi_id' => $value],
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'dosen_id');
    }

    public function mahasiswaBimbingan(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_wali_id');
    }
}
