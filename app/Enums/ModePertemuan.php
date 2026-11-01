<?php

namespace App\Enums;

enum ModePertemuan: string
{
    case Offline = 'offline';
    case Online = 'online';

    public function label(): string
    {
        return match ($this) {
            self::Offline => 'Tatap Muka di Ruangan',
            self::Online => 'Daring (Video Conference)',
        };
    }
}

