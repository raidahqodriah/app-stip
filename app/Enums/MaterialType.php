<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MaterialType: string implements HasLabel
{
    case Consumable = 'consumable';
    case Equipment = 'equipment';
    case Module = 'module';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Consumable => __('enums.material_type.consumable'),
            self::Equipment => __('enums.material_type.equipment'),
            self::Module => __('enums.material_type.module'),
        };
    }

    public function label(): string
    {
        return $this->getLabel() ?? $this->value;
    }
}
