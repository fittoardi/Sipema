<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[UseEloquentBuilder(Builder::class)]
class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'mata_kuliah_id',
        'kelas_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}
