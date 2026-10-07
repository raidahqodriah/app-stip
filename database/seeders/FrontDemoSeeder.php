<?php

namespace Database\Seeders;

use App\Enums\RequestStatus;
use App\Models\Core\Employee;
use App\Models\Core\Room;
use App\Models\Core\Unit;
use App\Models\Lab\BlackoutDate;
use App\Models\Lab\Booking;
use App\Models\Lab\BookingSlot;
use App\Models\Lab\Subject;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FrontDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosenTek = Employee::where('email', 'dosen.teknika@stipjakarta.ac.id')->first() ?? Employee::first();
        $unitSpp = Unit::where('code', 'UNIT-SPP')->first() ?? Unit::first();

        // 1. Blackout Dates
        $acsl = Room::where('code', 'ACSL')->first();
        if ($acsl) {
            BlackoutDate::updateOrCreate(
                [
                    'room_id' => $acsl->id,
                    'start_at' => Carbon::now()->startOfWeek()->addDays(0)->setTime(7, 30),
                ],
                [
                    'end_at' => Carbon::now()->startOfWeek()->addDays(2)->setTime(16, 0),
                    'reason' => 'Pemeliharaan Terjadwal & Kalibrasi ACSL (Sensor Pneumatik dan PLC)',
                ]
            );
        }

        BlackoutDate::updateOrCreate(
            [
                'room_id' => null,
                'start_at' => Carbon::now()->addWeeks(2)->startOfWeek()->addDays(4)->setTime(0, 0),
            ],
            [
                'end_at' => Carbon::now()->addWeeks(2)->startOfWeek()->addDays(4)->setTime(23, 59),
                'reason' => 'Hari Libur Nasional / Cuti Bersama Akademik',
            ]
        );

        // 2. Sample Bookings for Current Week
        $now = Carbon::now();
        $monday = $now->copy()->startOfWeek();

        $samples = [
            [
                'room_code' => 'ERCS',
                'day_offset' => 0, // Senin
                'start_hour' => 8,
                'start_min' => 0,
                'end_hour' => 11,
                'end_min' => 0,
                'class_group' => 'Teknika VI-A',
                'purpose' => 'Simulasi Engine Room Maneuvering & Emergency Stop',
                'participant_count' => 18,
            ],
            [
                'room_code' => 'CHL',
                'day_offset' => 0, // Senin
                'start_hour' => 13,
                'start_min' => 0,
                'end_hour' => 15,
                'end_min' => 30,
                'class_group' => 'Nautika IV-B',
                'purpose' => 'Prosedur Loading & Discharging Muatan Curah Cair',
                'participant_count' => 24,
            ],
            [
                'room_code' => 'MEL',
                'day_offset' => 1, // Selasa
                'start_hour' => 8,
                'start_min' => 30,
                'end_hour' => 11,
                'end_min' => 30,
                'class_group' => 'Teknika IV-B',
                'purpose' => 'Overhaul & Pengukuran Komponen Mesin Diesel 2-Tak',
                'participant_count' => 20,
            ],
            [
                'room_code' => 'EEL',
                'day_offset' => 1, // Selasa
                'start_hour' => 12,
                'start_min' => 30,
                'end_hour' => 15,
                'end_min' => 30,
                'class_group' => 'Teknika VI-B',
                'purpose' => 'Troubleshooting Generator Paralleling & Power Management',
                'participant_count' => 22,
            ],
            [
                'room_code' => 'CBT',
                'day_offset' => 2, // Rabu
                'start_hour' => 8,
                'start_min' => 0,
                'end_hour' => 12,
                'end_min' => 0,
                'class_group' => 'Pra-Prala Teknika & Nautika',
                'purpose' => 'Simulasi CBT Ujian Keahlian Pelaut (UKP)',
                'participant_count' => 38,
            ],
            [
                'room_code' => 'EWS',
                'day_offset' => 3, // Kamis
                'start_hour' => 8,
                'start_min' => 30,
                'end_hour' => 12,
                'end_min' => 0,
                'class_group' => 'Teknika II-A',
                'purpose' => 'Praktikum Pengelasan SMAW dan Bubut Presisi',
                'participant_count' => 25,
            ],
            [
                'room_code' => 'ERCS',
                'day_offset' => 3, // Kamis
                'start_hour' => 13,
                'start_min' => 0,
                'end_hour' => 16,
                'end_min' => 0,
                'class_group' => 'Diklat Peningkatan ATT-II',
                'purpose' => 'Sertifikasi Kompetensi Kamar Mesin STCW Reg. III/2',
                'participant_count' => 16,
            ],
            [
                'room_code' => 'LTL',
                'day_offset' => 4, // Jumat
                'start_hour' => 8,
                'start_min' => 0,
                'end_hour' => 11,
                'end_min' => 0,
                'class_group' => 'KALK IV-A',
                'purpose' => 'Maritime English Standard Marine Communication Phrases (SMCP)',
                'participant_count' => 30,
            ],
        ];

        foreach ($samples as $idx => $item) {
            $room = Room::where('code', $item['room_code'])->first();
            if (! $room) {
                continue;
            }

            $subject = Subject::whereHas('rooms', function ($q) use ($room) {
                $q->where('rooms.id', $room->id);
            })->first() ?? Subject::first();

            $startAt = $monday->copy()->addDays($item['day_offset'])->setTime($item['start_hour'], $item['start_min']);
            $endAt = $monday->copy()->addDays($item['day_offset'])->setTime($item['end_hour'], $item['end_min']);

            $bookingNumber = sprintf('BK-%s-%03d', $now->format('Ym'), $idx + 1);

            $booking = Booking::updateOrCreate(
                ['booking_number' => $bookingNumber],
                [
                    'room_id' => $room->id,
                    'subject_id' => $subject?->id ?? 1,
                    'unit_id' => $room->unit_id ?? $unitSpp->id,
                    'requester_type' => Employee::class,
                    'requester_id' => $dosenTek->id,
                    'responsible_lecturer_id' => $dosenTek->id,
                    'start_at' => $startAt,
                    'end_at' => $endAt,
                    'purpose' => $item['purpose'],
                    'participant_count' => $item['participant_count'],
                    'class_group' => $item['class_group'],
                    'status' => RequestStatus::Approved->value,
                    'submitted_at' => $startAt->copy()->subDays(3),
                    'notes' => 'Praktikum terjadwal sesuai RPS semester berjalan.',
                ]
            );

            // Populate booking_slots in 30-minute intervals
            $slotCursor = $startAt->copy();
            while ($slotCursor < $endAt) {
                BookingSlot::firstOrCreate(
                    [
                        'room_id' => $room->id,
                        'slot_start' => $slotCursor->copy(),
                    ],
                    [
                        'booking_id' => $booking->id,
                    ]
                );
                $slotCursor->addMinutes(30);
            }
        }
    }
}
