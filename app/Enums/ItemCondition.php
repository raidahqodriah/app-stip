<?php

namespace App\Enums;

enum ItemCondition: string
{
    case Good = 'good';
    case MinorDamage = 'minor_damage';
    case MajorDamage = 'major_damage';
    case Lost = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::Good => 'Baik',
            self::MinorDamage => 'Rusak Ringan',
            self::MajorDamage => 'Rusak Berat',
            self::Lost => 'Hilang',
        };
    }
}
