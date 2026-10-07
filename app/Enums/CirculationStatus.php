<?php

namespace App\Enums;

enum CirculationStatus: string
{
    case Borrowed = 'borrowed';
    case Returned = 'returned';

    public function label(): string
    {
        return match ($this) {
            self::Borrowed => 'Dipinjam',
            self::Returned => 'Dikembalikan',
        };
    }
}
