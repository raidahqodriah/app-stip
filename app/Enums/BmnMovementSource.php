<?php

namespace App\Enums;

enum BmnMovementSource: string
{
    case Submission = 'submission';
    case Return = 'return';
    case Manual = 'manual';

    public function label(): string
    {
        return match ($this) {
            self::Submission => 'Pengajuan Baru',
            self::Return => 'Pengembalian',
            self::Manual => 'Manual / Mutasi',
        };
    }
}
