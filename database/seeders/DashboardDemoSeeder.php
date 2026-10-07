<?php

namespace Database\Seeders;

use App\Enums\BmnItemStatus;
use App\Enums\CirculationStatus;
use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Models\Bmn\BmnItem;
use App\Models\Bmn\BmnReturn;
use App\Models\Bmn\BmnSubmission;
use App\Models\Bmn\OfficialResidence;
use App\Models\Bmn\ResidencePermit;
use App\Models\Core\Employee;
use App\Models\Core\Room;
use App\Models\Core\Student;
use App\Models\Core\Unit;
use App\Models\Lab\Booking;
use App\Models\Lab\BookingRealization;
use App\Models\Lab\BookingSlot;
use App\Models\Lab\Competence;
use App\Models\Lab\Material;
use App\Models\Lab\Subject;
use App\Models\Library\Book;
use App\Models\Library\Circulation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DashboardDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // 1. Ambil data master pendukung
        $unitSpp = Unit::where('code', 'UNIT-SPP')->first() ?? Unit::first();
        $unitBmn = Unit::where('code', 'UNIT-BMN')->first() ?? Unit::first();
        $prodiTek = Unit::where('code', 'PRODI-TEK')->first() ?? Unit::first();
        $prodiNau = Unit::where('code', 'PRODI-NAU')->first() ?? Unit::first();
        $prodiKalk = Unit::where('code', 'PRODI-KALK')->first() ?? Unit::first();

        $dosenTek = Employee::where('email', 'dosen.teknika@stipjakarta.ac.id')->first() ?? Employee::first();
        $dosenNau = Employee::where('email', 'dosen.nautika@stipjakarta.ac.id')->first() ?? $dosenTek;
        $petugasSpp = Employee::where('email', 'petugas.spp@stipjakarta.ac.id')->first() ?? $dosenTek;
        $petugasBmn = Employee::where('email', 'petugas.bmn@stipjakarta.ac.id')->first() ?? $dosenTek;
        $petugasPerpus = Employee::where('email', 'petugas.perpus@stipjakarta.ac.id')->first() ?? $dosenTek;
        $petugasRt = Employee::where('email', 'petugas.rt@stipjakarta.ac.id')->first() ?? $petugasBmn;
        $ketuaStip = Employee::where('email', 'ketua@stipjakarta.ac.id')->first() ?? $dosenTek;

        $studentNau = Student::where('email', 'taruna.nautika1@student.stipjakarta.ac.id')->first() ?? Student::first();
        $studentTek = Student::where('email', 'taruna.teknika1@student.stipjakarta.ac.id')->first() ?? Student::first();
        $studentKalk = Student::where('email', 'taruna.kalk1@student.stipjakarta.ac.id')->first() ?? Student::first();

        $rooms = Room::where('is_bookable', true)->get()->keyBy('code');
        $subjects = Subject::all()->keyBy('code');
        $competences = Competence::all();

        // ==========================================
        // SEEDING MODUL A: LAB & SIMULATOR SPP
        // ==========================================

        // Pastikan ada beberapa material dengan stok menipis (Low Stock)
        if ($ercs = $rooms->get('ERCS')) {
            Material::updateOrCreate(
                ['room_id' => $ercs->id, 'name' => 'Kertas Chart Recorder & Logging Simulator'],
                [
                    'type' => 'consumable',
                    'unit' => 'Roll',
                    'stock_qty' => 3,
                    'min_stock' => 10,
                ]
            );
        }

        if ($ews = $rooms->get('EWS')) {
            Material::updateOrCreate(
                ['room_id' => $ews->id, 'name' => 'Kawat Las SMAW E6013 3.2mm'],
                [
                    'type' => 'consumable',
                    'unit' => 'Kg',
                    'stock_qty' => 4,
                    'min_stock' => 15,
                ]
            );
            Material::updateOrCreate(
                ['room_id' => $ews->id, 'name' => 'Batu Gerinda Potong 4 inch'],
                [
                    'type' => 'consumable',
                    'unit' => 'Pcs',
                    'stock_qty' => 0, // Kritis habis!
                    'min_stock' => 20,
                ]
            );
        }

        if ($chl = $rooms->get('CHL')) {
            Material::updateOrCreate(
                ['room_id' => $chl->id, 'name' => 'Tinta Plotter Cargo Manifest & Loading Computer'],
                [
                    'type' => 'consumable',
                    'unit' => 'Cartridge',
                    'stock_qty' => 1,
                    'min_stock' => 4,
                ]
            );
        }

        // Buat Sesi Lab Hari Ini (2026-10-07)
        $todaySchedules = [
            [
                'room' => 'ERCS',
                'subject' => $subjects->first()?->code ?? 'MK-TEK-001',
                'start' => $now->copy()->setTime(8, 0),
                'end' => $now->copy()->setTime(11, 0),
                'status' => RequestStatus::InUse,
                'lecturer' => $dosenTek,
                'class' => 'Teknika VI-A',
                'purpose' => 'Simulasi Engine Room Maneuvering & Troubleshooting Blackout',
                'participants' => 22,
            ],
            [
                'room' => 'CHL',
                'subject' => 'MK-NAU-001',
                'start' => $now->copy()->setTime(9, 30),
                'end' => $now->copy()->setTime(12, 30),
                'status' => RequestStatus::InUse,
                'lecturer' => $dosenNau,
                'class' => 'Nautika IV-B',
                'purpose' => 'Simulasi Penanganan Muatan Curah Gas Cair (LPG/LNG Carrier)',
                'participants' => 25,
            ],
            [
                'room' => 'MEL',
                'subject' => 'MK-TEK-001',
                'start' => $now->copy()->setTime(13, 0),
                'end' => $now->copy()->setTime(15, 30),
                'status' => RequestStatus::Approved,
                'lecturer' => $dosenTek,
                'class' => 'Teknika IV-B',
                'purpose' => 'Praktik Overhaul Injector dan Governor Mesin Induk',
                'participants' => 20,
            ],
            [
                'room' => 'CBT',
                'subject' => 'MK-UMM-001',
                'start' => $now->copy()->setTime(13, 30),
                'end' => $now->copy()->setTime(16, 0),
                'status' => RequestStatus::Approved,
                'lecturer' => $dosenNau,
                'class' => 'Pra-Prala Angkatan 61',
                'purpose' => 'Uji Coba Tryout Mandiri CBT Ujian Keahlian Pelaut (UKP)',
                'participants' => 35,
            ],
        ];

        foreach ($todaySchedules as $idx => $ts) {
            $roomModel = $rooms->get($ts['room']) ?? $rooms->first();
            $subjectModel = $subjects->get($ts['subject']) ?? $subjects->first();
            if (! $roomModel || ! $subjectModel) {
                continue;
            }

            $bookingNum = sprintf('BK-%s-TODAY%02d', $now->format('Ym'), $idx + 1);
            $booking = Booking::updateOrCreate(
                ['booking_number' => $bookingNum],
                [
                    'room_id' => $roomModel->id,
                    'subject_id' => $subjectModel->id,
                    'unit_id' => $roomModel->unit_id ?? $unitSpp->id,
                    'requester_type' => Employee::class,
                    'requester_id' => $ts['lecturer']->id,
                    'responsible_lecturer_id' => $ts['lecturer']->id,
                    'start_at' => $ts['start'],
                    'end_at' => $ts['end'],
                    'purpose' => $ts['purpose'],
                    'participant_count' => $ts['participants'],
                    'class_group' => $ts['class'],
                    'status' => $ts['status']->value,
                    'submitted_at' => $ts['start']->copy()->subDays(2),
                    'notes' => 'Praktikum terjadwal hari ini.',
                ]
            );

            // Hubungkan kompetensi IMO jika ada
            if ($comp = $competences->random()) {
                $booking->competences()->syncWithoutDetaching([$comp->id]);
            }

            // Slot penahan
            $slotCursor = $ts['start']->copy();
            while ($slotCursor < $ts['end']) {
                BookingSlot::firstOrCreate(
                    ['room_id' => $roomModel->id, 'slot_start' => $slotCursor->copy()],
                    ['booking_id' => $booking->id]
                );
                $slotCursor->addMinutes(30);
            }
        }

        // Buat Antrean Pending Bookings (Submitted & Verified)
        $pendingBookings = [
            [
                'room' => 'EWS',
                'subject' => 'MK-TEK-001',
                'start' => $now->copy()->addDays(2)->setTime(8, 0),
                'end' => $now->copy()->addDays(2)->setTime(11, 30),
                'status' => RequestStatus::Submitted, // Menunggu Verifikasi Petugas
                'submitted_offset_hours' => 5,
                'lecturer' => $dosenTek,
                'class' => 'Teknika II-A',
                'purpose' => 'Pelatihan Dasar Pengelasan Bawah Air & Bubut Tabung Poros Baling-baling',
                'participants' => 24,
            ],
            [
                'room' => 'LTL',
                'subject' => 'MK-UMM-001',
                'start' => $now->copy()->addDays(3)->setTime(10, 0),
                'end' => $now->copy()->addDays(3)->setTime(12, 30),
                'status' => RequestStatus::Submitted, // Menunggu Verifikasi Petugas
                'submitted_offset_hours' => 14,
                'lecturer' => $dosenNau,
                'class' => 'KALK IV-A',
                'purpose' => 'Standard Marine Communication Phrases (SMCP) Port Clearance Simulator',
                'participants' => 30,
            ],
            [
                'room' => 'ERCS',
                'subject' => 'MK-TEK-001',
                'start' => $now->copy()->addDays(4)->setTime(8, 30),
                'end' => $now->copy()->addDays(4)->setTime(12, 0),
                'status' => RequestStatus::Verified, // Menunggu Approval Kepala Unit SPP
                'submitted_offset_hours' => 22,
                'lecturer' => $dosenTek,
                'class' => 'Teknika VI-B',
                'purpose' => 'Ujian Praktik Simulator Penanganan Gangguan Main Engine Scavenge Fire',
                'participants' => 18,
            ],
            [
                'room' => 'ACSL',
                'subject' => 'MK-TEK-001',
                'start' => $now->copy()->addDays(5)->setTime(13, 0),
                'end' => $now->copy()->addDays(5)->setTime(16, 0),
                'status' => RequestStatus::Verified, // Menunggu Approval Kepala Unit SPP
                'submitted_offset_hours' => 26,
                'lecturer' => $dosenTek,
                'class' => 'Teknika VI-A',
                'purpose' => 'Uji Otomasi Closed Loop PID Controller Temperatur Air Pendingin',
                'participants' => 20,
            ],
        ];

        foreach ($pendingBookings as $idx => $pb) {
            $roomModel = $rooms->get($pb['room']) ?? $rooms->first();
            $subjectModel = $subjects->get($pb['subject']) ?? $subjects->first();
            if (! $roomModel || ! $subjectModel) {
                continue;
            }

            $bookingNum = sprintf('BK-%s-PEND%02d', $now->format('Ym'), $idx + 1);
            $booking = Booking::updateOrCreate(
                ['booking_number' => $bookingNum],
                [
                    'room_id' => $roomModel->id,
                    'subject_id' => $subjectModel->id,
                    'unit_id' => $roomModel->unit_id ?? $unitSpp->id,
                    'requester_type' => Employee::class,
                    'requester_id' => $pb['lecturer']->id,
                    'responsible_lecturer_id' => $pb['lecturer']->id,
                    'start_at' => $pb['start'],
                    'end_at' => $pb['end'],
                    'purpose' => $pb['purpose'],
                    'participant_count' => $pb['participants'],
                    'class_group' => $pb['class'],
                    'status' => $pb['status']->value,
                    'submitted_at' => $now->copy()->subHours($pb['submitted_offset_hours']),
                    'notes' => 'Pengajuan booking memerlukan verifikasi / persetujuan bertahap.',
                ]
            );

            if ($comp = $competences->random()) {
                $booking->competences()->syncWithoutDetaching([$comp->id]);
            }
        }

        // Buat Data Historis Booking Lab (Juli, Agustus, September, Oktober 2026) untuk Grafik Tren Utilisasi
        for ($m = 3; $m >= 0; $m--) {
            $monthDate = $now->copy()->subMonths($m)->startOfMonth();
            $bookingsPerMonth = rand(10, 16);

            for ($i = 1; $i <= $bookingsPerMonth; $i++) {
                $room = $rooms->random();
                $subject = $subjects->random();
                $day = rand(1, min(28, $monthDate->daysInMonth));
                $startHour = [8, 10, 13][rand(0, 2)];
                $start = $monthDate->copy()->addDays($day - 1)->setTime($startHour, 0);
                $durationHours = rand(2, 4);
                $end = $start->copy()->addHours($durationHours);

                // Hindari bentrok di masa lalu
                if ($start > $now) {
                    continue;
                }

                $bookingCode = sprintf('BK-%s-HIST%02d%02d', $monthDate->format('Ym'), $m, $i);
                $booking = Booking::firstOrCreate(
                    ['booking_number' => $bookingCode],
                    [
                        'room_id' => $room->id,
                        'subject_id' => $subject->id,
                        'unit_id' => $room->unit_id ?? $unitSpp->id,
                        'requester_type' => Employee::class,
                        'requester_id' => $dosenTek->id,
                        'responsible_lecturer_id' => $dosenTek->id,
                        'start_at' => $start,
                        'end_at' => $end,
                        'purpose' => 'Sesi Praktikum Terstruktur Silabus Semester Berjalan',
                        'participant_count' => rand(18, 30),
                        'class_group' => 'T-IV-A',
                        'status' => RequestStatus::Completed->value,
                        'submitted_at' => $start->copy()->subDays(4),
                        'notes' => 'Sesi praktikum telah selesai dilaksanakan.',
                    ]
                );

                if ($comp = $competences->random()) {
                    $booking->competences()->syncWithoutDetaching([$comp->id]);
                }

                // Catat Realisasi untuk booking completed
                BookingRealization::firstOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'recorded_by' => $petugasSpp->id,
                        'actual_start_at' => $start->copy()->addMinutes(rand(0, 10)),
                        'actual_end_at' => $end->copy()->subMinutes(rand(0, 10)),
                        'actual_participant_count' => rand(16, 28),
                        'condition_after' => ItemCondition::Good->value,
                        'incident_note' => null,
                    ]
                );
            }
        }

        // ==========================================
        // SEEDING MODUL B: BMN (BARANG MILIK NEGARA)
        // ==========================================

        // Tambah Variasi Inventaris BMN dengan berbagai kondisi
        $extraBmn = [
            [
                'bmn_code' => '3050104010',
                'register_number' => '000005',
                'item_name' => 'Server Rack IBM System x3650 M5 Database CBT',
                'quantity' => 1,
                'brand' => 'IBM',
                'model' => 'System x3650 M5',
                'unit_id' => $unitSpp->id,
                'room_id' => $rooms->get('CBT')?->id ?? 1,
                'condition' => ItemCondition::Good,
                'requires_decree' => true,
                'status' => BmnItemStatus::Active,
            ],
            [
                'bmn_code' => '3050104011',
                'register_number' => '000006',
                'item_name' => 'Infocus Projector Epson EB-2250U Ruang Kuliah',
                'quantity' => 3,
                'brand' => 'Epson',
                'model' => 'EB-2250U',
                'unit_id' => $prodiNau->id,
                'room_id' => Room::where('code', 'KLS-N101')->first()?->id ?? 1,
                'condition' => ItemCondition::MinorDamage, // Rusak Ringan
                'requires_decree' => false,
                'status' => BmnItemStatus::Active,
            ],
            [
                'bmn_code' => '3050104012',
                'register_number' => '000007',
                'item_name' => 'Mesin Las Listrik Inverter Daiden TIG-200',
                'quantity' => 2,
                'brand' => 'Daiden',
                'model' => 'TIG-200',
                'unit_id' => $prodiTek->id,
                'room_id' => $rooms->get('EWS')?->id ?? 1,
                'condition' => ItemCondition::MajorDamage, // Rusak Berat
                'requires_decree' => false,
                'status' => BmnItemStatus::Active,
            ],
            [
                'bmn_code' => '3050104013',
                'register_number' => '000008',
                'item_name' => 'Laptop HP ProBook 440 G8 Inventaris Tata Usaha',
                'quantity' => 1,
                'brand' => 'HP',
                'model' => 'ProBook 440 G8',
                'unit_id' => $unitBmn->id,
                'room_id' => Room::where('code', 'ADM-BMN')->first()?->id ?? 1,
                'condition' => ItemCondition::Good,
                'requires_decree' => true, // Menunggu cetak SK penetapan
                'status' => BmnItemStatus::Active,
            ],
            [
                'bmn_code' => '3050104014',
                'register_number' => '000009',
                'item_name' => 'Perangkat GPS Marine Receiver Furuno GP-39',
                'quantity' => 1,
                'brand' => 'Furuno',
                'model' => 'GP-39',
                'unit_id' => $prodiNau->id,
                'room_id' => $rooms->get('CHL')?->id ?? 1,
                'condition' => ItemCondition::Lost, // Hilang
                'requires_decree' => false,
                'status' => BmnItemStatus::Disposed,
            ],
        ];

        foreach ($extraBmn as $eb) {
            BmnItem::updateOrCreate(
                ['bmn_code' => $eb['bmn_code']],
                array_merge($eb, [
                    'responsible_employee_id' => $petugasBmn->id,
                    'responsible_name' => $petugasBmn->name,
                    'acquisition_date' => '2024-01-15',
                    'acquisition_source' => 'DIPA BLU STIP',
                ])
            );
        }

        // Pengajuan BMN Baru yang Pending (Submitted)
        BmnSubmission::updateOrCreate(
            ['item_name' => 'Komputer All-in-One Lenovo ThinkCentre M70a Gen 3'],
            [
                'unit_id' => $prodiKalk->id,
                'room_id' => Room::where('code', 'KLS-K101')->first()?->id ?? 1,
                'submitted_by' => $dosenNau->id,
                'quantity' => 5,
                'brand' => 'Lenovo',
                'model' => 'ThinkCentre M70a',
                'acquisition_date' => $now->copy()->subDays(3),
                'acquisition_source' => 'Pengadaan Rutin Prodi KALK',
                'condition' => ItemCondition::Good->value,
                'responsible_name' => 'Staf Prodi KALK',
                'requires_decree' => true,
                'status' => RequestStatus::Submitted->value,
            ]
        );

        BmnSubmission::updateOrCreate(
            ['item_name' => 'AC Split Daikin Flash Inverter 2 PK'],
            [
                'unit_id' => $unitSpp->id,
                'room_id' => $rooms->get('LTL')?->id ?? 1,
                'submitted_by' => $petugasSpp->id,
                'quantity' => 2,
                'brand' => 'Daikin',
                'model' => 'FTKQ50UVM4',
                'acquisition_date' => $now->copy()->subDays(1),
                'acquisition_source' => 'Pemeliharaan Sarana SPP',
                'condition' => ItemCondition::Good->value,
                'responsible_name' => 'PLP Lab Bahasa',
                'requires_decree' => false,
                'status' => RequestStatus::Submitted->value,
            ]
        );

        // Pengembalian BMN Rusak yang Butuh Tindak Lanjut
        $itemRusak = BmnItem::where('condition', ItemCondition::MajorDamage)->first();
        if ($itemRusak) {
            BmnReturn::updateOrCreate(
                ['bmn_item_id' => $itemRusak->id],
                [
                    'requested_by' => $dosenTek->id,
                    'from_room_id' => $itemRusak->room_id,
                    'destination_room_id' => Room::where('code', 'ADM-BMN')->first()?->id,
                    'return_date' => $now->copy()->subDays(2),
                    'reason' => 'Kerusakan modul transformator utama dan korsleting papan kontrol.',
                    'condition' => ItemCondition::MajorDamage->value,
                    'damage_note' => 'Percikan api terjadi pada switch utama. Tidak dapat diperbaiki secara mandiri di bengkel kampus.',
                    'status' => RequestStatus::Submitted->value,
                ]
            );
        }

        // ==========================================
        // SEEDING MODUL C: RUMAH DINAS
        // ==========================================

        // Pastikan ada beberapa Rumah Dinas (Official Residence)
        $houses = [
            ['house_number' => 'RD-A03', 'address' => 'Komplek Perumahan Dinas STIP Blok A No. 03', 'is_active' => true],
            ['house_number' => 'RD-B03', 'address' => 'Komplek Perumahan Dinas STIP Blok B No. 03', 'is_active' => true],
            ['house_number' => 'RD-C02', 'address' => 'Komplek Perumahan Dinas STIP Blok C No. 02', 'is_active' => true],
            ['house_number' => 'RD-D01', 'address' => 'Komplek Perumahan Dinas STIP Blok D No. 01 (Renovasi)', 'is_active' => false],
        ];
        foreach ($houses as $h) {
            OfficialResidence::updateOrCreate(['house_number' => $h['house_number']], $h);
        }

        $allResidences = OfficialResidence::all();

        // 1. Izin Aktif (Approved) - Rumah Terisi
        if ($res1 = $allResidences->get(0)) {
            ResidencePermit::updateOrCreate(
                ['permit_number' => 'SIP/STIP/2025/001'],
                [
                    'employee_id' => $dosenNau->id,
                    'unit_id' => $prodiNau->id,
                    'official_residence_id' => $res1->id,
                    'submitted_by' => $petugasRt->id,
                    'verified_by' => $petugasRt->id,
                    'approved_by' => $ketuaStip->id,
                    'occupancy_start' => $now->copy()->subMonths(8),
                    'occupancy_end' => $now->copy()->addMonths(4),
                    'occupancy_notes' => 'Izin penghunian dinas dosen aktif.',
                    'status' => RequestStatus::Approved->value,
                ]
            );
        }

        // 2. Izin Mendekati Berakhir (Expiring in 20 days)
        if ($res2 = $allResidences->get(1)) {
            ResidencePermit::updateOrCreate(
                ['permit_number' => 'SIP/STIP/2024/042'],
                [
                    'employee_id' => $dosenTek->id,
                    'unit_id' => $prodiTek->id,
                    'official_residence_id' => $res2->id,
                    'submitted_by' => $petugasRt->id,
                    'verified_by' => $petugasRt->id,
                    'approved_by' => $ketuaStip->id,
                    'occupancy_start' => $now->copy()->subYear()->subMonths(11),
                    'occupancy_end' => $now->copy()->addDays(20), // Berakhir 20 hari lagi!
                    'occupancy_notes' => 'Masa berlaku izin hampir habis, dalam proses konfirmasi perpanjangan.',
                    'status' => RequestStatus::Approved->value,
                ]
            );
        }

        // 3. Permohonan SIP Pending (Submitted & Verified)
        if ($res3 = $allResidences->get(2)) {
            ResidencePermit::updateOrCreate(
                ['permit_number' => 'SIP/STIP/2026/DRAFT-01'],
                [
                    'employee_id' => $petugasSpp->id,
                    'unit_id' => $unitSpp->id,
                    'official_residence_id' => $res3->id,
                    'submitted_by' => $petugasRt->id,
                    'occupancy_start' => $now->copy()->addDays(10),
                    'occupancy_end' => $now->copy()->addYears(2),
                    'occupancy_notes' => 'Pengajuan baru penempatan staf laboratorium.',
                    'status' => RequestStatus::Submitted->value,
                ]
            );
        }

        if ($res4 = $allResidences->get(3)) {
            ResidencePermit::updateOrCreate(
                ['permit_number' => 'SIP/STIP/2026/DRAFT-02'],
                [
                    'employee_id' => $petugasBmn->id,
                    'unit_id' => $unitBmn->id,
                    'official_residence_id' => $res4->id,
                    'submitted_by' => $petugasRt->id,
                    'verified_by' => $petugasRt->id, // Lolos verifikasi petugas RT, menunggu Ketua STIP
                    'occupancy_start' => $now->copy()->addDays(15),
                    'occupancy_end' => $now->copy()->addYears(1),
                    'occupancy_notes' => 'Telah diverifikasi berkas oleh Bagian Rumah Tangga.',
                    'status' => RequestStatus::Verified->value,
                ]
            );
        }

        // ==========================================
        // SEEDING MODUL D: PERPUSTAKAAN
        // ==========================================

        // Pastikan ada buku yang stoknya kritis (0 atau 1)
        Book::updateOrCreate(
            ['book_code' => 'BK-NAU-009'],
            [
                'isbn' => '978-602-01-9999-0',
                'title' => 'Tabel Pasang Surut dan Almanak Nautika Indonesia 2026',
                'author' => 'Pusat Hidro-Oseanografi TNI AL (Pushidrosal)',
                'publisher' => 'Pushidrosal Press',
                'year' => 2026,
                'category' => 'Nautika',
                'shelf' => 'Rak A-Ref',
                'total_stock' => 5,
                'available_stock' => 0, // Kritis habis!
            ]
        );

        Book::updateOrCreate(
            ['book_code' => 'BK-TEK-009'],
            [
                'isbn' => '978-602-02-9999-1',
                'title' => 'Marine Boilers and Steam Engineering Principles',
                'author' => 'G.T. Flanagan',
                'publisher' => 'Butterworth-Heinemann',
                'year' => 2020,
                'category' => 'Teknika',
                'shelf' => 'Rak B-3',
                'total_stock' => 4,
                'available_stock' => 1, // Kritis sisa 1
            ]
        );

        $allBooks = Book::all();

        // 1. Buat Sirkulasi Hari Ini (Today Circulations)
        if ($bookToday1 = $allBooks->get(0)) {
            Circulation::updateOrCreate(
                ['transaction_code' => 'TRX-PERP-TODAY-01'],
                [
                    'borrower_type' => Student::class,
                    'borrower_id' => $studentNau->id,
                    'book_id' => $bookToday1->id,
                    'loaned_by' => $petugasPerpus->id,
                    'loan_date' => $now->copy()->toDateString(),
                    'due_date' => $now->copy()->addDays(7)->toDateString(),
                    'status' => CirculationStatus::Borrowed->value,
                    'fine_amount' => 0,
                    'fine_paid' => true,
                ]
            );
        }

        if ($bookToday2 = $allBooks->get(1)) {
            Circulation::updateOrCreate(
                ['transaction_code' => 'TRX-PERP-TODAY-02'],
                [
                    'borrower_type' => Employee::class,
                    'borrower_id' => $dosenTek->id,
                    'book_id' => $bookToday2->id,
                    'loaned_by' => $petugasPerpus->id,
                    'returned_by' => $petugasPerpus->id,
                    'loan_date' => $now->copy()->subDays(6)->toDateString(),
                    'due_date' => $now->copy()->addDays(1)->toDateString(),
                    'return_date' => $now->copy()->toDateString(),
                    'status' => CirculationStatus::Returned->value,
                    'return_condition' => ItemCondition::Good->value,
                    'fine_amount' => 0,
                    'fine_paid' => true,
                ]
            );
        }

        // 2. Buat Peminjaman Overdue (Terlambat & Menunggak Denda)
        if ($bookOverdue1 = $allBooks->get(2)) {
            $lateDays = 5;
            $fine = $lateDays * 1000;
            Circulation::updateOrCreate(
                ['transaction_code' => 'TRX-PERP-OVERDUE-01'],
                [
                    'borrower_type' => Student::class,
                    'borrower_id' => $studentTek->id,
                    'book_id' => $bookOverdue1->id,
                    'loaned_by' => $petugasPerpus->id,
                    'loan_date' => $now->copy()->subDays(12)->toDateString(),
                    'due_date' => $now->copy()->subDays($lateDays)->toDateString(),
                    'status' => CirculationStatus::Borrowed->value,
                    'late_days' => $lateDays,
                    'fine_amount' => $fine,
                    'fine_paid' => false,
                    'officer_note' => 'Terlambat 5 hari kalender.',
                ]
            );
        }

        if ($bookOverdue2 = $allBooks->get(3)) {
            $lateDays = 12;
            $fine = $lateDays * 1000;
            Circulation::updateOrCreate(
                ['transaction_code' => 'TRX-PERP-OVERDUE-02'],
                [
                    'borrower_type' => Student::class,
                    'borrower_id' => $studentKalk->id,
                    'book_id' => $bookOverdue2->id,
                    'loaned_by' => $petugasPerpus->id,
                    'loan_date' => $now->copy()->subDays(19)->toDateString(),
                    'due_date' => $now->copy()->subDays($lateDays)->toDateString(),
                    'status' => CirculationStatus::Borrowed->value,
                    'late_days' => $lateDays,
                    'fine_amount' => $fine,
                    'fine_paid' => false,
                    'officer_note' => 'Terlambat 12 hari kalender.',
                ]
            );
        }

        // 3. Sirkulasi Historis untuk Tren Peminjaman & Top 5 Buku Terpopuler
        // Favoritkan 2-3 buku teratas agar grafiknya tinggi
        $popularBooks = $allBooks->take(3);

        for ($m = 3; $m >= 0; $m--) {
            $monthDate = $now->copy()->subMonths($m)->startOfMonth();
            $circulationsCount = rand(15, 25);

            for ($i = 1; $i <= $circulationsCount; $i++) {
                $book = ($i % 2 === 0 && $popularBooks->count() > 0) ? $popularBooks->random() : $allBooks->random();
                $borrower = ($i % 3 === 0) ? $dosenTek : $studentNau;
                $borrowerType = ($borrower instanceof Student) ? Student::class : Employee::class;

                $day = rand(1, min(28, $monthDate->daysInMonth));
                $loanDate = $monthDate->copy()->addDays($day - 1);
                $dueDate = $loanDate->copy()->addDays(7);

                if ($loanDate > $now) {
                    continue;
                }

                $isReturned = $loanDate->copy()->addDays(5) < $now;
                $trxCode = sprintf('TRX-%s-HIST%02d%02d', $monthDate->format('Ym'), $m, $i);

                Circulation::firstOrCreate(
                    ['transaction_code' => $trxCode],
                    [
                        'borrower_type' => $borrowerType,
                        'borrower_id' => $borrower->id,
                        'book_id' => $book->id,
                        'loaned_by' => $petugasPerpus->id,
                        'returned_by' => $isReturned ? $petugasPerpus->id : null,
                        'loan_date' => $loanDate->toDateString(),
                        'due_date' => $dueDate->toDateString(),
                        'return_date' => $isReturned ? $loanDate->copy()->addDays(rand(3, 7))->toDateString() : null,
                        'status' => $isReturned ? CirculationStatus::Returned->value : CirculationStatus::Borrowed->value,
                        'return_condition' => $isReturned ? ItemCondition::Good->value : null,
                        'fine_amount' => 0,
                        'fine_paid' => true,
                    ]
                );
            }
        }
    }
}
