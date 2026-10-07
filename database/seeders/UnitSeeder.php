<?php

namespace Database\Seeders;

use App\Enums\UnitType;
use App\Models\Core\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            [
                'code' => 'PIMPINAN',
                'name' => 'Pimpinan dan Sekretariat STIP',
                'type' => UnitType::WorkUnit,
                'is_active' => true,
            ],
            [
                'code' => 'PRODI-NAU',
                'name' => 'Program Studi Nautika',
                'type' => UnitType::StudyProgram,
                'is_active' => true,
            ],
            [
                'code' => 'PRODI-TEK',
                'name' => 'Program Studi Teknika',
                'type' => UnitType::StudyProgram,
                'is_active' => true,
            ],
            [
                'code' => 'PRODI-KALK',
                'name' => 'Program Studi Ketatalaksanaan Angkutan Laut dan Kepelabuhanan',
                'type' => UnitType::StudyProgram,
                'is_active' => true,
            ],
            [
                'code' => 'UNIT-SPP',
                'name' => 'Unit Sarana Praktik Pelaut (SPP)',
                'type' => UnitType::ServiceUnit,
                'is_active' => true,
            ],
            [
                'code' => 'UNIT-BMN',
                'name' => 'Unit Pengelolaan BMN dan Rumah Tangga',
                'type' => UnitType::WorkUnit,
                'is_active' => true,
            ],
            [
                'code' => 'UNIT-PERPUS',
                'name' => 'Unit Perpustakaan',
                'type' => UnitType::ServiceUnit,
                'is_active' => true,
            ],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['code' => $unit['code']],
                $unit
            );
        }
    }
}
