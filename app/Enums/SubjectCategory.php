<?php

namespace App\Enums;

enum SubjectCategory: string
{
    case Teknika = 'teknika';
    case Nautika = 'nautika';
    case Kalk = 'kalk';

    public function label(): string
    {
        return match ($this) {
            self::Teknika => 'Teknika',
            self::Nautika => 'Nautika',
            self::Kalk => 'KALK',
        };
    }
}
