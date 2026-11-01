<?php

namespace App\Models;

use App\Enums\Hari;
use App\Enums\TipeKelas;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[UseEloquentBuilder(Builder::class)]
class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'mata_kuliah_id',
        'dosen_id',
        'tahun_akademik_id',
        'ruangan_id',
        'nama_kelas',
        'nama',
        'tipe_kelas',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kapasitas',
    ];

    protected function casts(): array
    {
        return [
            'tipe_kelas' => TipeKelas::class,
            'hari' => Hari::class,
            'kapasitas' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Kelas $kelas): void {
            if (empty($kelas->tahun_akademik_id)) {
                $aktif = TahunAkademik::where('is_active', true)->first()
                    ?? TahunAkademik::first();

                if (! $aktif && app()->environment('testing')) {
                    $aktif = TahunAkademik::factory()->create(['is_active' => true]);
                }

                if ($aktif) {
                    $kelas->tahun_akademik_id = $aktif->id;
                }
            }

            if (empty($kelas->hari)) {
                $kelas->hari = Hari::Senin;
            }

            if (empty($kelas->jam_mulai)) {
                $kelas->jam_mulai = '08:00:00';
            }

            if (empty($kelas->jam_selesai)) {
                $kelas->jam_selesai = '10:00:00';
            }
        });
    }

    protected function nama(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['nama_kelas'] ?? null,
            set: fn ($value) => ['nama_kelas' => $value],
        );
    }

    public function getSksAttribute(): int
    {
        return (int) ($this->mataKuliah?->sks ?? 3);
    }

    public function getNamaLengkapAttribute(): string
    {
        $mkNama = $this->mataKuliah?->nama ?? 'Mata Kuliah';

        return "{$mkNama} ({$this->nama_kelas})";
    }

    public function getJamRentangAttribute(): string
    {
        $start = substr((string) $this->jam_mulai, 0, 5);
        $end = substr((string) $this->jam_selesai, 0, 5);

        return "{$start} - {$end}";
    }

    public function getJadwalsAttribute()
    {
        return collect([
            (object) [
                'hari' => $this->hari instanceof Hari ? $this->hari->value : (string) ($this->hari ?? '-'),
                'jam_mulai' => (string) ($this->jam_mulai ?? '08:00:00'),
                'jam_selesai' => (string) ($this->jam_selesai ?? '10:00:00'),
                'ruangan' => $this->ruangan?->nama ?? ($this->tipe_kelas === TipeKelas::Online ? 'Online / Zoom' : 'Ruang Kelas'),
            ],
        ]);
    }

    public function scopeUntukDosen(Builder $query, int $dosenId): Builder
    {
        return $query->where('dosen_id', $dosenId);
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function krsDetail(): HasMany
    {
        return $this->hasMany(KrsDetail::class, 'kelas_id');
    }

    public function kelasMahasiswa(): HasMany
    {
        return $this->hasMany(KelasMahasiswa::class, 'kelas_id');
    }

    public function kelasMahasiswas(): HasMany
    {
        return $this->kelasMahasiswa();
    }

    public function nilais(): HasManyThrough
    {
        return $this->hasManyThrough(Nilai::class, KelasMahasiswa::class, 'kelas_id', 'kelas_mahasiswa_id');
    }

    public function mahasiswas(): BelongsToMany
    {
        return $this->belongsToMany(Mahasiswa::class, 'kelas_mahasiswa', 'kelas_id', 'mahasiswa_id')
            ->withTimestamps();
    }

    public function pertemuan(): HasMany
    {
        return $this->hasMany(Pertemuan::class, 'kelas_id');
    }
}
