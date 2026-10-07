<?php

namespace Database\Seeders;

use App\Enums\BmnItemStatus;
use App\Enums\ItemCondition;
use App\Models\Bmn\BmnItem;
use App\Models\Core\Employee;
use App\Models\Core\Room;
use App\Models\Core\Unit;
use Illuminate\Database\Seeder;

class BmnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitBmn = Unit::where('code', 'UNIT-BMN')->first();
        $unitSpp = Unit::where('code', 'UNIT-SPP')->first();
        $prodiTek = Unit::where('code', 'PRODI-TEK')->first();

        $petugasBmn = Employee::where('email', 'petugas.bmn@stipjakarta.ac.id')->first();
        $dosenTek = Employee::where('email', 'dosen.teknika@stipjakarta.ac.id')->first();
        $petugasSpp = Employee::where('email', 'petugas.spp@stipjakarta.ac.id')->first();

        $admBmn = Room::where('code', 'ADM-BMN')->first();
        $ews = Room::where('code', 'EWS')->first();
        $acsl = Room::where('code', 'ACSL')->first();
        $cbt = Room::where('code', 'CBT')->first();
        $klsT101 = Room::where('code', 'KLS-T101')->first();

        $items = [
            [
                'bmn_code' => '3050104001',
                'register_number' => '000001',
                'item_name' => 'Laptop Dell Latitude 5420 i7 16GB',
                'quantity' => 1,
                'brand' => 'Dell',
                'model' => 'Latitude 5420',
                'serial_number' => 'DL-5420-998811',
                'unit_id' => $unitBmn->id,
                'room_id' => $admBmn->id,
                'responsible_employee_id' => $petugasBmn?->id,
                'responsible_name' => $petugasBmn?->name ?? 'Petugas BMN',
                'acquisition_date' => '2023-03-15',
                'acquisition_source' => 'APBN TA 2023',
                'condition' => ItemCondition::Good,
                'requires_decree' => true,
                'status' => BmnItemStatus::Active,
            ],
            [
                'bmn_code' => '3050105002',
                'register_number' => '000001',
                'item_name' => 'Mesin Bubut Presisi Horizontal (Heavy Duty Lathe)',
                'quantity' => 1,
                'brand' => 'Pinacho',
                'model' => 'SC-200',
                'serial_number' => 'PNC-2022-4412',
                'unit_id' => $unitSpp->id,
                'room_id' => $ews->id,
                'responsible_employee_id' => $petugasSpp?->id,
                'responsible_name' => $petugasSpp?->name ?? 'Petugas Bengkel',
                'acquisition_date' => '2022-08-20',
                'acquisition_source' => 'DIPA BLU STIP TA 2022',
                'condition' => ItemCondition::Good,
                'requires_decree' => false,
                'status' => BmnItemStatus::Active,
            ],
            [
                'bmn_code' => '3050105003',
                'register_number' => '000001',
                'item_name' => 'Konsol Trainer PLC dan Sistem Kontrol Otomasi Maritim',
                'quantity' => 2,
                'brand' => 'Festo Didactic',
                'model' => 'MPS-Mar-200',
                'serial_number' => 'FST-MPS-0091',
                'unit_id' => $unitSpp->id,
                'room_id' => $acsl->id,
                'responsible_employee_id' => $dosenTek?->id,
                'responsible_name' => $dosenTek?->name ?? 'Dosen Pengampu Otomasi',
                'acquisition_date' => '2023-11-10',
                'acquisition_source' => 'APBN TA 2023',
                'condition' => ItemCondition::Good,
                'requires_decree' => false,
                'status' => BmnItemStatus::Active,
            ],
            [
                'bmn_code' => '3050104002',
                'register_number' => '000001',
                'item_name' => 'PC All-in-One Workstation Ujian CBT',
                'quantity' => 40,
                'brand' => 'HP',
                'model' => 'ProOne 440 G9',
                'serial_number' => 'HP-CBT-BATCH1',
                'unit_id' => $unitSpp->id,
                'room_id' => $cbt->id,
                'responsible_employee_id' => $petugasSpp?->id,
                'responsible_name' => $petugasSpp?->name ?? 'Petugas CBT',
                'acquisition_date' => '2024-01-18',
                'acquisition_source' => 'DIPA BLU STIP TA 2024',
                'condition' => ItemCondition::Good,
                'requires_decree' => false,
                'status' => BmnItemStatus::Active,
            ],
            [
                'bmn_code' => '3050104003',
                'register_number' => '000001',
                'item_name' => 'Proyektor Ruang Kuliah Epson EB-X500',
                'quantity' => 1,
                'brand' => 'Epson',
                'model' => 'EB-X500',
                'serial_number' => 'EPS-EB-77312',
                'unit_id' => $prodiTek->id,
                'room_id' => $klsT101->id,
                'responsible_employee_id' => $dosenTek?->id,
                'responsible_name' => $dosenTek?->name ?? 'Koor Ruang Kuliah',
                'acquisition_date' => '2023-05-12',
                'acquisition_source' => 'DIPA BLU STIP TA 2023',
                'condition' => ItemCondition::Good,
                'requires_decree' => false,
                'status' => BmnItemStatus::Active,
            ],
        ];

        foreach ($items as $item) {
            BmnItem::updateOrCreate(
                [
                    'unit_id' => $item['unit_id'],
                    'bmn_code' => $item['bmn_code'],
                    'register_number' => $item['register_number'],
                ],
                $item
            );
        }
    }
}
