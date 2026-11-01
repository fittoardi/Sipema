<?php

namespace App\Enums;

enum MetodePresensi: string
{
    case Mandiri = 'mandiri';
    case ManualDosen = 'manual_dosen';

    public function label(): string
    {
        return match ($this) {
            self::Mandiri => 'Mandiri (Selfie & GPS)',
            self::ManualDosen => 'Koreksi Manual Dosen',
        };
    }
}

