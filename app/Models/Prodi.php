<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
    ];

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function dosens()
    {
        return $this->hasMany(Dosen::class);
    }
}
