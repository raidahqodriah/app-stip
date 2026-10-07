<?php

namespace App\Enums;

enum RoomType: string
{
    case Lab = 'lab';
    case Classroom = 'classroom';
    case Office = 'office';
    case Warehouse = 'warehouse';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Lab => 'Laboratorium / Simulator',
            self::Classroom => 'Ruang Kelas',
            self::Office => 'Ruang Kantor',
            self::Warehouse => 'Gudang',
            self::Other => 'Lainnya',
        };
    }
}
