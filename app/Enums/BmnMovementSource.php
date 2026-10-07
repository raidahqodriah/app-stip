<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BmnMovementSource: string implements HasLabel
{
    case Submission = 'submission';
    case Return = 'return';
    case Manual = 'manual';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Submission => __('enums.bmn_movement_source.submission'),
            self::Return => __('enums.bmn_movement_source.return'),
            self::Manual => __('enums.bmn_movement_source.manual'),
        };
    }

    public function label(): string
    {
        return $this->getLabel() ?? $this->value;
    }
}
