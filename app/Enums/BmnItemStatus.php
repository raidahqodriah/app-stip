<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BmnItemStatus: string implements HasLabel
{
    case Active = 'active';
    case Returned = 'returned';
    case Disposed = 'disposed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => __('enums.bmn_item_status.active'),
            self::Returned => __('enums.bmn_item_status.returned'),
            self::Disposed => __('enums.bmn_item_status.disposed'),
        };
    }

    public function label(): string
    {
        return $this->getLabel() ?? $this->value;
    }
}
