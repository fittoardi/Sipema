<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $program_studi_id
 * @property int|null $dosen_wali_id
 * @property string $nim
 * @property string $nama
 * @property int $angkatan
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $user
 * @property-read ProgramStudi|null $programStudi
 * @property-read ProgramStudi|null $prodi
 * @property-read Fakultas|null $fakultas
 * @property-read string $fakultas_badge_color
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static> query()
 *
 * @mixin Builder
 */
#[UseEloquentBuilder(Builder::class)]
class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id',
        'program_studi_id',
        'prodi_id',
        'dosen_wali_id',
        'nim',
        'nama',
        'angkatan',
    ];

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

    public function fakultas(): HasOneThrough
    {
        return $this->hasOneThrough(
            Fakultas::class,
            ProgramStudi::class,
            'id',
            'id',
            'program_studi_id',
            'fakultas_id'
        );
    }

    public function scopeFilterByFakultas(Builder $query, ?int $fakultasId): Builder
    {
        return $query->when($fakultasId, function (Builder $q) use ($fakultasId) {
            $q->whereHas('programStudi', fn ($sub) => $sub->where('fakultas_id', $fakultasId));
        });
    }

    public function scopeFilterByProdi(Builder $query, ?int $prodiId): Builder
    {
        return $query->when($prodiId, function (Builder $q) use ($prodiId) {
            $q->where('program_studi_id', $prodiId);
        });
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        return $query->when($search, function (Builder $q) use ($search) {
            $q->where(function (Builder $sub) use ($search) {
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('email', 'like', "%{$search}%"));
            });
        });
    }

    public function getFakultasBadgeColorAttribute(): string
    {
        $kode = strtoupper($this->programStudi?->fakultas?->kode ?? '');

        return match ($kode) {
            'FTI' => 'bg-blue-100 text-signal-blue border-signal-blue/30',
            'FT' => 'bg-amber-100 text-deep-moss border-burnished-gold/40',
            'FEB' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'FIK' => 'bg-purple-100 text-purple-800 border-purple-300',
            'FH' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-soft-linen text-olive-char border-warm-mist',
        };
    }

    public function dosenWali(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_wali_id');
    }

    public function krs(): HasMany
    {
        return $this->hasMany(Krs::class, 'mahasiswa_id');
    }

    public function krsAktif(): HasOne
    {
        return $this->hasOne(Krs::class, 'mahasiswa_id')
            ->whereHas('tahunAkademik', fn ($q) => $q->where('is_active', true));
    }

    public function kelasMahasiswa(): HasMany
    {
        return $this->hasMany(KelasMahasiswa::class, 'mahasiswa_id');
    }

    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'kelas_mahasiswa', 'mahasiswa_id', 'kelas_id')
            ->withTimestamps();
    }

    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'mahasiswa_id');
    }

    public function pengumpulanTugas(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class, 'mahasiswa_id');
    }
}
