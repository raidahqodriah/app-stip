<?php

namespace Database\Seeders;

use App\Models\Bmn\OfficialResidence;
use App\Models\Core\Unit;
use Illuminate\Database\Seeder;

class ResidenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitBmn = Unit::where('code', 'UNIT-BMN')->first();

        $residences = [
            [
                'house_number' => 'RD-A01',
                'address' => 'Komplek Perumahan Dinas STIP Blok A No. 01, Marunda, Jakarta Utara',
                'unit_id' => $unitBmn->id,
                'is_active' => true,
            ],
            [
                'house_number' => 'RD-A02',
                'address' => 'Komplek Perumahan Dinas STIP Blok A No. 02, Marunda, Jakarta Utara',
                'unit_id' => $unitBmn->id,
                'is_active' => true,
            ],
            [
                'house_number' => 'RD-B01',
                'address' => 'Komplek Perumahan Dinas STIP Blok B No. 01, Marunda, Jakarta Utara',
                'unit_id' => $unitBmn->id,
                'is_active' => true,
            ],
            [
                'house_number' => 'RD-B02',
                'address' => 'Komplek Perumahan Dinas STIP Blok B No. 02, Marunda, Jakarta Utara',
                'unit_id' => $unitBmn->id,
                'is_active' => true,
            ],
            [
                'house_number' => 'RD-C01',
                'address' => 'Komplek Perumahan Dinas STIP Blok C No. 01, Marunda, Jakarta Utara',
                'unit_id' => $unitBmn->id,
                'is_active' => true,
            ],
        ];

        foreach ($residences as $residence) {
            OfficialResidence::updateOrCreate(
                ['house_number' => $residence['house_number']],
                $residence
            );
        }
    }
}
