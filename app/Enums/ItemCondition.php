<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ItemCondition: string implements HasLabel
{
    case Good = 'good';
    case MinorDamage = 'minor_damage';
    case MajorDamage = 'major_damage';
    case Lost = 'lost';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Good => __('enums.item_condition.good'),
            self::MinorDamage => __('enums.item_condition.minor_damage'),
            self::MajorDamage => __('enums.item_condition.major_damage'),
            self::Lost => __('enums.item_condition.lost'),
        };
    }

    public function label(): string
    {
        return $this->getLabel() ?? $this->value;
    }
}
