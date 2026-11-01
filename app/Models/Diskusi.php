<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\UseEloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseEloquentBuilder(Builder::class)]
class Diskusi extends Model
{
    use HasFactory;

    protected $table = 'diskusi';

    protected $fillable = [
        'pertemuan_id',
        'user_id',
        'parent_id',
        'isi',
    ];

    public function pertemuan(): BelongsTo
    {
        return $this->belongsTo(Pertemuan::class, 'pertemuan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Diskusi::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Diskusi::class, 'parent_id')->orderBy('created_at', 'asc');
    }
}

