<?php

namespace App\Models;

use App\Enums\ModePertemuan;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseEloquentBuilder(Builder::class)]
class Pertemuan extends Model
{
    use HasFactory;

    protected $table = 'pertemuan';

    protected $fillable = [
        'kelas_id',
        'pertemuan_ke',
        'judul',
        'mode',
        'ruangan_id',
        'tautan_meeting',
        'tanggal',
        'berita_acara',
        'is_published',
        'is_presensi_open',
        'wajib_selfie',
        'wajib_gps',
        'kode_presensi',
    ];

    protected function casts(): array
    {
        return [
            'mode' => ModePertemuan::class,
            'pertemuan_ke' => 'integer',
            'tanggal' => 'date',
            'is_published' => 'boolean',
            'is_presensi_open' => 'boolean',
            'wajib_selfie' => 'boolean',
            'wajib_gps' => 'boolean',
        ];
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'ruangan_id');
    }

    public function materi(): HasMany
    {
        return $this->hasMany(Materi::class, 'pertemuan_id')->orderBy('urutan');
    }

    public function materis(): HasMany
    {
        return $this->materi();
    }

    public function presensi(): HasMany
    {
        return $this->hasMany(Presensi::class, 'pertemuan_id');
    }

    public function presensis(): HasMany
    {
        return $this->presensi();
    }

    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class, 'pertemuan_id');
    }

    public function diskusi(): HasMany
    {
        return $this->hasMany(Diskusi::class, 'pertemuan_id')->whereNull('parent_id');
    }
}
