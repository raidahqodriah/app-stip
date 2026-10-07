<?php

namespace Database\Seeders;

use App\Enums\RoomType;
use App\Models\Core\Employee;
use App\Models\Core\Room;
use App\Models\Core\Unit;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitSpp = Unit::where('code', 'UNIT-SPP')->first();
        $unitBmn = Unit::where('code', 'UNIT-BMN')->first();
        $prodiTek = Unit::where('code', 'PRODI-TEK')->first();
        $prodiNau = Unit::where('code', 'PRODI-NAU')->first();

        $petugasSpp = Employee::where('email', 'petugas.spp@stipjakarta.ac.id')->first();
        $petugasBmn = Employee::where('email', 'petugas.bmn@stipjakarta.ac.id')->first();
        $dosenTek = Employee::where('email', 'dosen.teknika@stipjakarta.ac.id')->first();

        $rooms = [
            // 8 Lab / Simulator Aktif SPP (§3.1)
            [
                'code' => 'CHL',
                'name' => 'Cargo Handling Laboratory',
                'type' => RoomType::Lab,
                'is_bookable' => true,
                'lab_category' => 'nautika',
                'capacity' => 30,
                'unit_id' => $unitSpp->id,
                'pic_employee_id' => $petugasSpp?->id,
                'location' => 'Gedung SPP Lantai 2',
                'description' => 'Simulator penanganan dan pengaturan muatan kapal niaga serta tanker.',
                'operating_hours' => ['start' => '07:30', 'end' => '16:00'],
                'is_active' => true,
            ],
            [
                'code' => 'EEL',
                'name' => 'Electric and Electronic Laboratory',
                'type' => RoomType::Lab,
                'is_bookable' => true,
                'lab_category' => 'teknika',
                'capacity' => 25,
                'unit_id' => $unitSpp->id,
                'pic_employee_id' => $dosenTek?->id,
                'location' => 'Gedung SPP Lantai 1',
                'description' => 'Laboratorium kelistrikan kapal, sistem distribusi daya, dan otomasi.',
                'operating_hours' => ['start' => '07:30', 'end' => '16:00'],
                'is_active' => true,
            ],
            [
                'code' => 'EWS',
                'name' => 'Engineering Workshop',
                'type' => RoomType::Lab,
                'is_bookable' => true,
                'lab_category' => 'teknika',
                'capacity' => 30,
                'unit_id' => $unitSpp->id,
                'pic_employee_id' => $petugasSpp?->id,
                'location' => 'Bengkel Permesinan Kapal',
                'description' => 'Workshop bubut, pengelasan, fabrikasi pipa, dan rekondisi komponen mesin.',
                'operating_hours' => ['start' => '07:30', 'end' => '16:00'],
                'is_active' => true,
            ],
            [
                'code' => 'MEL',
                'name' => 'Marine Engineering Laboratory',
                'type' => RoomType::Lab,
                'is_bookable' => true,
                'lab_category' => 'teknika',
                'capacity' => 25,
                'unit_id' => $unitSpp->id,
                'pic_employee_id' => $petugasSpp?->id,
                'location' => 'Gedung SPP Lantai 1',
                'description' => 'Laboratorium motor diesel, ketel uap, kompresor, dan pompa kapal.',
                'operating_hours' => ['start' => '07:30', 'end' => '16:00'],
                'is_active' => true,
            ],
            [
                'code' => 'CBT',
                'name' => 'Computer Based Training 1 & 2',
                'type' => RoomType::Lab,
                'is_bookable' => true,
                'lab_category' => 'all',
                'capacity' => 40,
                'unit_id' => $unitSpp->id,
                'pic_employee_id' => $petugasSpp?->id,
                'location' => 'Gedung Utama Lantai 3',
                'description' => 'Laboratorium komputer untuk ujian berbasis komputer, ECDIS, dan diklat pelaut.',
                'operating_hours' => ['start' => '07:30', 'end' => '16:00'],
                'is_active' => true,
            ],
            [
                'code' => 'ERCS',
                'name' => 'Engine Room Certification Simulator',
                'type' => RoomType::Lab,
                'is_bookable' => true,
                'lab_category' => 'teknika',
                'capacity' => 20,
                'unit_id' => $unitSpp->id,
                'pic_employee_id' => $dosenTek?->id,
                'location' => 'Gedung Simulator Lantai 2',
                'description' => 'Full-mission engine room simulator untuk sertifikasi perwira mesin kapal.',
                'operating_hours' => ['start' => '07:30', 'end' => '16:00'],
                'is_active' => true,
            ],
            [
                'code' => 'LTL',
                'name' => 'Language Training Laboratory',
                'type' => RoomType::Lab,
                'is_bookable' => true,
                'lab_category' => 'all',
                'capacity' => 35,
                'unit_id' => $unitSpp->id,
                'pic_employee_id' => $petugasSpp?->id,
                'location' => 'Gedung Perpustakaan Lantai 2',
                'description' => 'Laboratorium bahasa Inggris maritim dan komunikasi maritim internasional.',
                'operating_hours' => ['start' => '07:30', 'end' => '16:00'],
                'is_active' => true,
            ],
            [
                'code' => 'ACSL',
                'name' => 'Automatic Control System Laboratory',
                'type' => RoomType::Lab,
                'is_bookable' => true,
                'lab_category' => 'teknika',
                'capacity' => 25,
                'unit_id' => $unitSpp->id,
                'pic_employee_id' => $dosenTek?->id,
                'location' => 'Gedung SPP Lantai 2',
                'description' => 'Laboratorium instrumen kontrol pneumatik, hidrolik, dan PLC maritim.',
                'operating_hours' => ['start' => '07:30', 'end' => '16:00'],
                'is_active' => true,
            ],

            // Ruangan Non-Lab untuk Pengelolaan BMN
            [
                'code' => 'ADM-BMN',
                'name' => 'Ruang Administrasi BMN & Rumah Tangga',
                'type' => RoomType::Office,
                'is_bookable' => false,
                'capacity' => 15,
                'unit_id' => $unitBmn->id,
                'pic_employee_id' => $petugasBmn?->id,
                'location' => 'Gedung Rektorat Lantai 1',
                'description' => 'Kantor layanan administrasi aset dan inventaris negara.',
                'is_active' => true,
            ],
            [
                'code' => 'GUD-ASET',
                'name' => 'Gudang Pusat Aset BMN',
                'type' => RoomType::Warehouse,
                'is_bookable' => false,
                'capacity' => 10,
                'unit_id' => $unitBmn->id,
                'pic_employee_id' => $petugasBmn?->id,
                'location' => 'Area Pergudangan Belakang',
                'description' => 'Penyimpanan barang inventaris dan barang dalam proses penghapusan/mutasi.',
                'is_active' => true,
            ],
            [
                'code' => 'KLS-T101',
                'name' => 'Ruang Kuliah Teori T-101',
                'type' => RoomType::Classroom,
                'is_bookable' => false,
                'capacity' => 35,
                'unit_id' => $prodiTek->id,
                'location' => 'Gedung Kuliah Teknika Lantai 1',
                'description' => 'Ruang perkuliahan tatap muka prodi Teknika.',
                'is_active' => true,
            ],
            [
                'code' => 'KLS-N102',
                'name' => 'Ruang Kuliah Teori N-102',
                'type' => RoomType::Classroom,
                'is_bookable' => false,
                'capacity' => 35,
                'unit_id' => $prodiNau->id,
                'location' => 'Gedung Kuliah Nautika Lantai 1',
                'description' => 'Ruang perkuliahan tatap muka prodi Nautika.',
                'is_active' => true,
            ],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(
                ['code' => $room['code']],
                $room
            );
        }
    }
}
