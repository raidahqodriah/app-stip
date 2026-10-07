<?php

namespace App\Enums;

enum MaterialType: string
{
    case Consumable = 'consumable';
    case Equipment = 'equipment';
    case Module = 'module';

    public function label(): string
    {
        return match ($this) {
            self::Consumable => 'Bahan Habis Pakai',
            self::Equipment => 'Peralatan',
            self::Module => 'Modul Praktik',
        };
    }
}
