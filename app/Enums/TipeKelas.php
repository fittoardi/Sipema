<?php

namespace App\Enums;

enum TipeKelas: string
{
    case Offline = 'offline';
    case Online = 'online';
    case Hybrid = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::Offline => 'Tatap Muka (Offline)',
            self::Online => 'Daring (Online)',
            self::Hybrid => 'Hybrid (Blended)',
        };
    }
}

