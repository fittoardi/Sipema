<?php

namespace App\Enums;

enum StatusPresensi: string
{
    case Hadir = 'hadir';
    case Izin = 'izin';
    case Sakit = 'sakit';
    case Alpha = 'alpha';

    public function label(): string
    {
        return match ($this) {
            self::Hadir => 'Hadir',
            self::Izin => 'Izin',
            self::Sakit => 'Sakit',
            self::Alpha => 'Alpha (Tanpa Keterangan)',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Hadir => 'emerald',
            self::Izin => 'amber',
            self::Sakit => 'blue',
            self::Alpha => 'rose',
        };
    }
}

