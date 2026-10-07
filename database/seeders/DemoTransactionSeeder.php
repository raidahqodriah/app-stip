<?php

namespace Database\Seeders;

use App\Enums\CirculationStatus;
use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Models\Bmn\BmnItem;
use App\Models\Bmn\BmnReturn;
use App\Models\Bmn\BmnSubmission;
use App\Models\Bmn\OfficialResidence;
use App\Models\Bmn\ResidencePermit;
use App\Models\Core\DocumentTemplate;
use App\Models\Core\Employee;
use App\Models\Core\GeneratedDocument;
use App\Models\Core\RequestLog;
use App\Models\Core\Room;
use App\Models\Core\Student;
use App\Models\Core\Unit;
use App\Models\Lab\Booking;
use App\Models\Lab\BookingMaterial;
use App\Models\Lab\BookingRealization;
use App\Models\Lab\BookingSlot;
use App\Models\Lab\Competence;
use App\Models\Lab\Material;
use App\Models\Lab\Subject;
use App\Models\Library\Book;
use App\Models\Library\Circulation;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DemoTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Employee::first();
        $lecturers = Employee::where('position', 'like', '%Dosen%')->get();
        $lecturer = $lecturers->first() ?? $admin;
        $students = Student::all();
        $student = $students->first();
        $units = Unit::all();
        $nautikaUnit = Unit::where('code', 'NAUT')->first() ?? $units->first();
        $teknikaUnit = Unit::where('code', 'TEKN')->first() ?? $units->first();
        $rooms = Room::where('is_bookable', true)->get();
        $chlRoom = Room::where('code', 'CHL')->first() ?? $rooms->first();
        $ercsRoom = Room::where('code', 'ERCS')->first() ?? $rooms->first();
        $eelRoom = Room::where('code', 'EEL')->first() ?? $rooms->first();
        $melRoom = Room::where('code', 'MEL')->first() ?? $rooms->first();

        // 1. Lab Bookings & Realizations
        $subjects = Subject::all();
        $chlSubject = Subject::where('name', 'like', '%Cargo%')->first() ?? $subjects->first();
        $competences = Competence::all();
        $materials = Material::all();

        // Booking 1: Approved CHL Lab
        $booking1 = Booking::updateOrCreate(
            ['booking_number' => 'BOOK-202610-0001'],
            [
                'room_id' => $chlRoom->id,
                'subject_id' => $chlSubject ? $chlSubject->id : 1,
                'unit_id' => $nautikaUnit->id,
                'requester_type' => 'employee',
                'requester_id' => $lecturer->id,
                'responsible_lecturer_id' => $lecturer->id,
                'start_at' => Carbon::now()->addDays(2)->setHour(8)->setMinute(0)->setSecond(0),
                'end_at' => Carbon::now()->addDays(2)->setHour(11)->setMinute(30)->setSecond(0),
                'purpose' => 'Praktikum Penanganan dan Pengaturan Muatan Curah (Dry Bulk Cargo Handling)',
                'participant_count' => 30,
                'class_group' => 'Nautika VII-A',
                'status' => RequestStatus::Approved,
                'submitted_at' => Carbon::now()->subDays(2),
                'notes' => 'Membutuhkan simulator derek kargo dan modul palka.',
            ]
        );

        if ($competences->count() > 0) {
            $booking1->competences()->sync($competences->take(2)->pluck('id'));
        }

        // Slots for booking 1 (30 min slots)
        $slotStart = Carbon::now()->addDays(2)->setHour(8)->setMinute(0);
        for ($i = 0; $i < 7; $i++) {
            BookingSlot::updateOrCreate(
                [
                    'booking_id' => $booking1->id,
                    'room_id' => $chlRoom->id,
                    'slot_start' => $slotStart->copy()->addMinutes($i * 30),
                ]
            );
        }

        // Materials for booking 1
        if ($materials->count() > 0) {
            BookingMaterial::updateOrCreate(
                [
                    'booking_id' => $booking1->id,
                    'material_id' => $materials->first()->id,
                ],
                [
                    'qty_requested' => 30,
                    'qty_approved' => 30,
                ]
            );
        }

        // Booking 1 Request Logs
        RequestLog::create([
            'loggable_type' => Booking::class,
            'loggable_id' => $booking1->id,
            'actor_type' => 'employee',
            'actor_id' => $lecturer->id,
            'role' => 'teacher',
            'action' => 'submit',
            'from_status' => 'draft',
            'to_status' => 'submitted',
            'note' => 'Permohonan praktikum lab diajukan.',
            'created_at' => Carbon::now()->subDays(2),
        ]);
        RequestLog::create([
            'loggable_type' => Booking::class,
            'loggable_id' => $booking1->id,
            'actor_type' => 'employee',
            'actor_id' => $admin->id,
            'role' => 'officer',
            'action' => 'verify',
            'from_status' => 'submitted',
            'to_status' => 'verified',
            'note' => 'Peralatan simulator siap, bahan tersedia.',
            'created_at' => Carbon::now()->subDays(1),
        ]);
        RequestLog::create([
            'loggable_type' => Booking::class,
            'loggable_id' => $booking1->id,
            'actor_type' => 'employee',
            'actor_id' => $admin->id,
            'role' => 'leader',
            'action' => 'approve',
            'from_status' => 'verified',
            'to_status' => 'approved',
            'note' => 'Disetujui Kepala Unit SPP.',
            'created_at' => Carbon::now()->subHours(10),
        ]);

        // Booking 2: Verified ERCS Simulator
        $booking2 = Booking::updateOrCreate(
            ['booking_number' => 'BOOK-202610-0002'],
            [
                'room_id' => $ercsRoom->id,
                'subject_id' => $subjects->count() > 1 ? $subjects->skip(1)->first()->id : 1,
                'unit_id' => $teknikaUnit->id,
                'requester_type' => 'student',
                'requester_id' => $student ? $student->id : 1,
                'responsible_lecturer_id' => $lecturer->id,
                'start_at' => Carbon::now()->addDays(3)->setHour(13)->setMinute(0),
                'end_at' => Carbon::now()->addDays(3)->setHour(15)->setMinute(30),
                'purpose' => 'Simulasi Engine Room Blackout & Recovery Procedure',
                'participant_count' => 24,
                'class_group' => 'Teknika V-B',
                'status' => RequestStatus::Verified,
                'submitted_at' => Carbon::now()->subDays(1),
                'notes' => 'Praktikum mandiri terbimbing dengan dosen penanggung jawab.',
            ]
        );

        // Booking 3: Completed MEL with Realization
        $booking3 = Booking::updateOrCreate(
            ['booking_number' => 'BOOK-202610-0003'],
            [
                'room_id' => $melRoom->id,
                'subject_id' => $subjects->first()->id,
                'unit_id' => $teknikaUnit->id,
                'requester_type' => 'employee',
                'requester_id' => $lecturer->id,
                'responsible_lecturer_id' => $lecturer->id,
                'start_at' => Carbon::now()->subDays(1)->setHour(9)->setMinute(0),
                'end_at' => Carbon::now()->subDays(1)->setHour(12)->setMinute(0),
                'purpose' => 'Uji Karakteristik Mesin Induk Diesel 4-Tak',
                'participant_count' => 25,
                'class_group' => 'Teknika VII-C',
                'status' => RequestStatus::Completed,
                'submitted_at' => Carbon::now()->subDays(5),
            ]
        );

        BookingRealization::updateOrCreate(
            ['booking_id' => $booking3->id],
            [
                'recorded_by' => $lecturer->id,
                'actual_start_at' => Carbon::now()->subDays(1)->setHour(9)->setMinute(10),
                'actual_end_at' => Carbon::now()->subDays(1)->setHour(12)->setMinute(0),
                'actual_participant_count' => 25,
                'condition_after' => ItemCondition::Good,
                'incident_note' => 'Praktikum berjalan lancar tanpa kendala mesin.',
            ]
        );

        // 2. BMN Submissions
        $bmnItem = BmnItem::first();
        $submission1 = BmnSubmission::updateOrCreate(
            ['item_name' => 'Laptop ASUS ExpertBook B1400 (Pengadaan Lab Nautika)'],
            [
                'unit_id' => $nautikaUnit->id,
                'room_id' => $chlRoom->id,
                'submitted_by' => $admin->id,
                'bmn_code' => 'BMN-NAUT-2026-004',
                'register_number' => 'NUP-0045',
                'quantity' => 5,
                'brand' => 'ASUS',
                'model' => 'ExpertBook B1400 Core i7 / 16GB / 512GB',
                'serial_number' => 'SN-ASUS-991204',
                'acquisition_date' => Carbon::now()->subDays(10),
                'acquisition_source' => 'DIPA STIP Jakarta T.A. 2026',
                'condition' => ItemCondition::Good,
                'responsible_name' => 'Capt. Budi Santoso, M.Mar.',
                'requires_decree' => true,
                'status' => RequestStatus::Submitted,
            ]
        );

        $submission2 = BmnSubmission::updateOrCreate(
            ['item_name' => 'Projector EPSON EB-E01 Ruang Simulator'],
            [
                'unit_id' => $teknikaUnit->id,
                'room_id' => $ercsRoom->id,
                'submitted_by' => $admin->id,
                'verified_by' => $admin->id,
                'bmn_code' => 'BMN-TEKN-2026-009',
                'register_number' => 'NUP-0078',
                'quantity' => 2,
                'brand' => 'Epson',
                'model' => 'EB-E01 3300 Lumens',
                'serial_number' => 'EPS-99124-ID',
                'acquisition_date' => Carbon::now()->subDays(20),
                'acquisition_source' => 'DIPA STIP Jakarta T.A. 2026',
                'condition' => ItemCondition::Good,
                'responsible_name' => 'Ir. Hendra Gunawan, M.T.',
                'requires_decree' => false,
                'status' => RequestStatus::Approved,
            ]
        );

        // 3. BMN Returns (including damaged items)
        if ($bmnItem) {
            $return1 = BmnReturn::updateOrCreate(
                ['bmn_item_id' => $bmnItem->id],
                [
                    'requested_by' => $admin->id,
                    'from_room_id' => $bmnItem->room_id ?? $chlRoom->id,
                    'destination_room_id' => $rooms->last()->id,
                    'return_date' => Carbon::now()->subDays(1),
                    'reason' => 'Penggantian unit akibat layar retak saat praktikum',
                    'condition' => ItemCondition::MajorDamage,
                    'damage_note' => 'Panel LCD retak di sudut kiri atas, tampilan flickering, lampu latar redup.',
                    'status' => RequestStatus::Submitted,
                ]
            );
        }

        // 4. Residence Permits (Rumah Dinas)
        $residences = OfficialResidence::all();
        $residence = $residences->first();
        if ($residence) {
            $permit1 = ResidencePermit::updateOrCreate(
                ['permit_number' => 'SIP/STIP/2026/10/001'],
                [
                    'employee_id' => $lecturer->id,
                    'unit_id' => $teknikaUnit->id,
                    'official_residence_id' => $residence->id,
                    'submitted_by' => $admin->id,
                    'verified_by' => $admin->id,
                    'approved_by' => $admin->id,
                    'occupancy_start' => Carbon::now()->startOfMonth(),
                    'occupancy_end' => Carbon::now()->startOfMonth()->addYears(2),
                    'occupancy_notes' => 'Diberikan fasilitas rumah dinas tipe A sesuai Keputusan Ketua STIP.',
                    'status' => RequestStatus::Approved,
                ]
            );

            if ($residences->count() > 1) {
                $permit2 = ResidencePermit::updateOrCreate(
                    ['permit_number' => 'SIP/STIP/2026/10/002'],
                    [
                        'employee_id' => $lecturers->skip(1)->first()?->id ?? $lecturer->id,
                        'unit_id' => $nautikaUnit->id,
                        'official_residence_id' => $residences->skip(1)->first()->id,
                        'submitted_by' => $admin->id,
                        'verified_by' => $admin->id,
                        'occupancy_start' => Carbon::now()->addMonth()->startOfMonth(),
                        'occupancy_end' => Carbon::now()->addMonth()->startOfMonth()->addYears(1),
                        'occupancy_notes' => 'Menunggu persetujuan akhir Ketua STIP Jakarta.',
                        'status' => RequestStatus::Verified,
                    ]
                );
            }
        }

        // 5. Library Circulations
        $books = Book::all();
        $book1 = $books->first();
        $book2 = $books->skip(1)->first() ?? $book1;
        $book3 = $books->skip(2)->first() ?? $book1;

        if ($book1 && $student) {
            // Active on-time loan
            Circulation::updateOrCreate(
                ['transaction_code' => 'TRX-LIB-202610-001'],
                [
                    'borrower_type' => 'student',
                    'borrower_id' => $student->id,
                    'book_id' => $book1->id,
                    'loaned_by' => $admin->id,
                    'loan_date' => Carbon::now()->subDays(3),
                    'due_date' => Carbon::now()->addDays(4),
                    'status' => CirculationStatus::Borrowed,
                    'fine_amount' => 0,
                    'fine_paid' => false,
                ]
            );
        }

        if ($book2 && $students->count() > 1) {
            $student2 = $students->skip(1)->first();
            // Overdue loan (3 days late -> fine Rp 3.000)
            Circulation::updateOrCreate(
                ['transaction_code' => 'TRX-LIB-202610-002'],
                [
                    'borrower_type' => 'student',
                    'borrower_id' => $student2->id,
                    'book_id' => $book2->id,
                    'loaned_by' => $admin->id,
                    'loan_date' => Carbon::now()->subDays(10),
                    'due_date' => Carbon::now()->subDays(3),
                    'status' => CirculationStatus::Borrowed,
                    'late_days' => 3,
                    'fine_amount' => 3000,
                    'fine_paid' => false,
                    'officer_note' => 'Peminjaman melewati jatuh tempo 3 hari kalender.',
                ]
            );
        }

        if ($book3 && $lecturer) {
            // Returned loan with paid fine
            Circulation::updateOrCreate(
                ['transaction_code' => 'TRX-LIB-202610-003'],
                [
                    'borrower_type' => 'employee',
                    'borrower_id' => $lecturer->id,
                    'book_id' => $book3->id,
                    'loaned_by' => $admin->id,
                    'returned_by' => $admin->id,
                    'loan_date' => Carbon::now()->subDays(14),
                    'due_date' => Carbon::now()->subDays(7),
                    'return_date' => Carbon::now()->subDays(5),
                    'status' => CirculationStatus::Returned,
                    'return_condition' => ItemCondition::Good,
                    'late_days' => 2,
                    'fine_amount' => 2000,
                    'fine_paid' => true,
                    'fine_paid_at' => Carbon::now()->subDays(5),
                    'officer_note' => 'Denda keterlambatan 2 hari telah dilunasi di loket perpustakaan.',
                ]
            );
        }

        // 6. Generated Documents for Print Previews
        $tplBooking = DocumentTemplate::where('type', 'booking_confirmation')->first();
        if ($tplBooking && $booking1) {
            GeneratedDocument::updateOrCreate(
                ['document_number' => 'DOC-LAB-202610-001'],
                [
                    'document_template_id' => $tplBooking->id,
                    'documentable_type' => Booking::class,
                    'documentable_id' => $booking1->id,
                    'generator_type' => 'employee',
                    'generator_id' => $admin->id,
                    'print_count' => 1,
                ]
            );
        }

        $tplDecree = DocumentTemplate::where('type', 'bmn_decree')->first();
        if ($tplDecree && $submission2) {
            GeneratedDocument::updateOrCreate(
                ['document_number' => 'DOC-BMN-202610-001'],
                [
                    'document_template_id' => $tplDecree->id,
                    'documentable_type' => BmnSubmission::class,
                    'documentable_id' => $submission2->id,
                    'generator_type' => 'employee',
                    'generator_id' => $admin->id,
                    'print_count' => 2,
                ]
            );
        }

        $tplReturn = DocumentTemplate::where('type', 'bmn_return_receipt')->first();
        if ($tplReturn && isset($return1)) {
            GeneratedDocument::updateOrCreate(
                ['document_number' => 'DOC-RET-202610-001'],
                [
                    'document_template_id' => $tplReturn->id,
                    'documentable_type' => BmnReturn::class,
                    'documentable_id' => $return1->id,
                    'generator_type' => 'employee',
                    'generator_id' => $admin->id,
                    'print_count' => 1,
                ]
            );
        }

        $tplPermit = DocumentTemplate::where('type', 'residence_permit')->first();
        if ($tplPermit && isset($permit1)) {
            GeneratedDocument::updateOrCreate(
                ['document_number' => 'DOC-SIP-202610-001'],
                [
                    'document_template_id' => $tplPermit->id,
                    'documentable_type' => ResidencePermit::class,
                    'documentable_id' => $permit1->id,
                    'generator_type' => 'employee',
                    'generator_id' => $admin->id,
                    'print_count' => 3,
                ]
            );
        }
    }
}
