<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Dosen = 'dosen';
    case Mahasiswa = 'mahasiswa';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Dosen => 'Dosen Pengajar',
            self::Mahasiswa => 'Mahasiswa',
        };
    }
}
