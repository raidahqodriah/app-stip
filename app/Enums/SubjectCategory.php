<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SubjectCategory: string implements HasLabel
{
    case Teknika = 'teknika';
    case Nautika = 'nautika';
    case Kalk = 'kalk';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Teknika => __('enums.subject_category.teknika'),
            self::Nautika => __('enums.subject_category.nautika'),
            self::Kalk => __('enums.subject_category.kalk'),
        };
    }

    public function label(): string
    {
        return $this->getLabel() ?? $this->value;
    }
}
