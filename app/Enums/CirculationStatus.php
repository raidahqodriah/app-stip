<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CirculationStatus: string implements HasLabel
{
    case Borrowed = 'borrowed';
    case Returned = 'returned';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Borrowed => __('enums.circulation_status.borrowed'),
            self::Returned => __('enums.circulation_status.returned'),
        };
    }

    public function label(): string
    {
        return $this->getLabel() ?? $this->value;
    }
}
