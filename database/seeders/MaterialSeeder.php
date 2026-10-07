<?php

namespace Database\Seeders;

use App\Enums\MaterialType;
use App\Models\Core\Room;
use App\Models\Lab\Material;
use App\Models\Lab\MaterialKit;
use App\Models\Lab\Subject;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ews = Room::where('code', 'EWS')->first();
        $eel = Room::where('code', 'EEL')->first();
        $mel = Room::where('code', 'MEL')->first();
        $ercs = Room::where('code', 'ERCS')->first();
        $chl = Room::where('code', 'CHL')->first();

        $subBengkel = Subject::where('code', 'TEK-301')->first();
        $subListrik = Subject::where('code', 'TEK-202')->first();
        $subMotor = Subject::where('code', 'TEK-302')->first();
        $subMuatan = Subject::where('code', 'NAU-201')->first();

        // 1. Materials
        $materials = [
            // EWS Workshop
            [
                'room_id' => $ews->id,
                'name' => 'Elektroda Las Baja E6013 (2.6mm)',
                'type' => MaterialType::Consumable,
                'unit' => 'kg',
                'stock_qty' => 50.00,
                'min_stock' => 10.00,
            ],
            [
                'room_id' => $ews->id,
                'name' => 'Kain Majun Pembersih',
                'type' => MaterialType::Consumable,
                'unit' => 'kg',
                'stock_qty' => 100.00,
                'min_stock' => 20.00,
            ],
            [
                'room_id' => $ews->id,
                'name' => 'Jangka Sorong Digital 150mm',
                'type' => MaterialType::Equipment,
                'unit' => 'unit',
                'stock_qty' => 15.00,
                'min_stock' => 5.00,
            ],

            // EEL Electric Lab
            [
                'room_id' => $eel->id,
                'name' => 'Multimeter Digital Fluke 17B+',
                'type' => MaterialType::Equipment,
                'unit' => 'unit',
                'stock_qty' => 20.00,
                'min_stock' => 5.00,
            ],
            [
                'room_id' => $eel->id,
                'name' => 'Kabel Jumper Terminal Praktik',
                'type' => MaterialType::Consumable,
                'unit' => 'set',
                'stock_qty' => 40.00,
                'min_stock' => 10.00,
            ],

            // MEL Marine Eng Lab
            [
                'room_id' => $mel->id,
                'name' => 'Pelumas Mesin SAE 40 TBN 12',
                'type' => MaterialType::Consumable,
                'unit' => 'liter',
                'stock_qty' => 200.00,
                'min_stock' => 50.00,
            ],
            [
                'room_id' => $mel->id,
                'name' => 'Filter Bahan Bakar Solar Separator',
                'type' => MaterialType::Consumable,
                'unit' => 'buah',
                'stock_qty' => 25.00,
                'min_stock' => 5.00,
            ],

            // CHL Cargo Lab
            [
                'room_id' => $chl->id,
                'name' => 'Modul Penanganan Muatan Berbahaya IMDG Code',
                'type' => MaterialType::Module,
                'unit' => 'eksemplar',
                'stock_qty' => 30.00,
                'min_stock' => 5.00,
            ],
        ];

        foreach ($materials as $item) {
            Material::updateOrCreate(
                ['room_id' => $item['room_id'], 'name' => $item['name']],
                $item
            );
        }

        // 2. Material Kits (Default per sesi/peserta)
        $elektroda = Material::where('name', 'like', '%Elektroda%')->first();
        $majun = Material::where('name', 'like', '%Majun%')->first();
        $jangka = Material::where('name', 'like', '%Jangka Sorong%')->first();
        $multimeter = Material::where('name', 'like', '%Multimeter%')->first();
        $oli = Material::where('name', 'like', '%Pelumas Mesin%')->first();
        $modulImdg = Material::where('name', 'like', '%IMDG Code%')->first();

        $kits = [
            // Praktik Bengkel di EWS
            [
                'subject_id' => $subBengkel->id,
                'room_id' => $ews->id,
                'material_id' => $elektroda->id,
                'qty_per_participant' => 0.50,
                'qty_per_session' => 10.00,
            ],
            [
                'subject_id' => $subBengkel->id,
                'room_id' => $ews->id,
                'material_id' => $majun->id,
                'qty_per_participant' => 0.20,
                'qty_per_session' => 5.00,
            ],
            [
                'subject_id' => $subBengkel->id,
                'room_id' => $ews->id,
                'material_id' => $jangka->id,
                'qty_per_participant' => 1.00,
                'qty_per_session' => 20.00,
            ],

            // Kelistrikan Kapal di EEL
            [
                'subject_id' => $subListrik->id,
                'room_id' => $eel->id,
                'material_id' => $multimeter->id,
                'qty_per_participant' => 1.00,
                'qty_per_session' => 15.00,
            ],

            // Motor Diesel di MEL
            [
                'subject_id' => $subMotor->id,
                'room_id' => $mel->id,
                'material_id' => $oli->id,
                'qty_per_participant' => null,
                'qty_per_session' => 20.00,
            ],

            // Penanganan Muatan di CHL
            [
                'subject_id' => $subMuatan->id,
                'room_id' => $chl->id,
                'material_id' => $modulImdg->id,
                'qty_per_participant' => 1.00,
                'qty_per_session' => 30.00,
            ],
        ];

        foreach ($kits as $kit) {
            MaterialKit::updateOrCreate(
                [
                    'subject_id' => $kit['subject_id'],
                    'room_id' => $kit['room_id'],
                    'material_id' => $kit['material_id'],
                ],
                $kit
            );
        }
    }
}
