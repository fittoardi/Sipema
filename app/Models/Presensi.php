<?php

namespace App\Models;

use App\Enums\MetodePresensi;
use App\Enums\StatusPresensi;
use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseEloquentBuilder(Builder::class)]
class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';

    protected $fillable = [
        'pertemuan_id',
        'mahasiswa_id',
        'status',
        'foto_selfie_path',
        'latitude',
        'longitude',
        'akurasi_meter',
        'jarak_meter',
        'is_dalam_radius',
        'metode',
        'ip_address',
        'user_agent',
        'keterangan',
        'dicatat_pada',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusPresensi::class,
            'metode' => MetodePresensi::class,
            'latitude' => 'float',
            'longitude' => 'float',
            'akurasi_meter' => 'float',
            'jarak_meter' => 'float',
            'is_dalam_radius' => 'boolean',
            'dicatat_pada' => 'datetime',
        ];
    }

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id');
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id');
    }
}
