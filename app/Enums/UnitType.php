<?php

namespace App\Enums;

enum UnitType: string
{
    case StudyProgram = 'study_program';
    case WorkUnit = 'work_unit';
    case ServiceUnit = 'service_unit';

    public function label(): string
    {
        return match ($this) {
            self::StudyProgram => 'Program Studi',
            self::WorkUnit => 'Unit Kerja',
            self::ServiceUnit => 'Unit Layanan',
        };
    }
}
