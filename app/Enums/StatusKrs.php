<?php

namespace App\Enums;

enum StatusKrs: string
{
    case Draft = 'draft';
    case Diajukan = 'diajukan';
    case Revisi = 'revisi';
    case Disetujui = 'disetujui';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Diajukan => 'Diajukan ke Pembimbing',
            self::Revisi => 'Perlu Revisi',
            self::Disetujui => 'Disetujui',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Diajukan => 'amber',
            self::Revisi => 'rose',
            self::Disetujui => 'emerald',
        };
    }
}

