<?php

namespace App\Enums;

enum BmnItemStatus: string
{
    case Active = 'active';
    case Returned = 'returned';
    case Disposed = 'disposed';

    public function label(): string
    {
        return match ($this) {
            self::Active => 'Aktif',
            self::Returned => 'Dikembalikan',
            self::Disposed => 'Dihapuskan',
        };
    }
}
