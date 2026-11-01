<?php

namespace App\Models;

use App\Enums\StatusKrs;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseEloquentBuilder(Builder::class)]
class Krs extends Model
{
    use HasFactory;

    protected $table = 'krs';

    protected $fillable = [
        'mahasiswa_id',
        'tahun_akademik_id',
        'total_sks',
        'status',
        'catatan_wali',
        'disetujui_oleh',
        'disetujui_pada',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusKrs::class,
            'total_sks' => 'integer',
            'disetujui_pada' => 'datetime',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }

    public function tahunAkademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik_id');
    }

    public function disetujuiOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function details(): HasMany
    {
        return $this->hasMany(KrsDetail::class, 'krs_id');
    }

    public function krsDetail(): HasMany
    {
        return $this->details();
    }

    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'krs_detail', 'krs_id', 'kelas_id')
            ->withTimestamps();
    }
}
