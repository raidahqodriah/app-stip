<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum UnitType: string implements HasLabel
{
    case StudyProgram = 'study_program';
    case WorkUnit = 'work_unit';
    case ServiceUnit = 'service_unit';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::StudyProgram => __('enums.unit_type.study_program'),
            self::WorkUnit => __('enums.unit_type.work_unit'),
            self::ServiceUnit => __('enums.unit_type.service_unit'),
        };
    }

    public function label(): string
    {
        return $this->getLabel() ?? $this->value;
    }
}
