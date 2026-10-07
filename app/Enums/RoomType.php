<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RoomType: string implements HasLabel
{
    case Lab = 'lab';
    case Classroom = 'classroom';
    case Office = 'office';
    case Warehouse = 'warehouse';
    case Other = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Lab => __('enums.room_type.lab'),
            self::Classroom => __('enums.room_type.classroom'),
            self::Office => __('enums.room_type.office'),
            self::Warehouse => __('enums.room_type.warehouse'),
            self::Other => __('enums.room_type.other'),
        };
    }

    public function label(): string
    {
        return $this->getLabel() ?? $this->value;
    }
}
