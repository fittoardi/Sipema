<?php

namespace App\Enums;

enum Semester: string
{
    case Ganjil = 'Ganjil';
    case Genap = 'Genap';

    public function label(): string
    {
        return $this->value;
    }
}

