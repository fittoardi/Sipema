<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = [

        'user_id',
        'nim',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto',
        'angkatan',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
