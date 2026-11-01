<?php

namespace App\Enums;

enum StatusNilai: string
{
    case Lulus = 'lulus';
    case TidakLulus = 'tidak_lulus';
    case BelumLengkap = 'belum_lengkap';

    public function label(): string
    {
        return match ($this) {
            self::Lulus => 'Lulus',
            self::TidakLulus => 'Tidak Lulus',
            self::BelumLengkap => 'Belum Lengkap',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Lulus => 'emerald',
            self::TidakLulus => 'rose',
            self::BelumLengkap => 'amber',
        };
    }
}
