<?php

namespace App\Http\Controllers;

use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Models\Core\Employee;
use App\Models\Core\RequestLog;
use App\Models\Core\Room;
use App\Models\Core\Unit;
use App\Models\Lab\Booking;
use App\Models\Lab\BookingRealization;
use App\Models\Lab\BookingSlot;
use App\Models\Lab\Subject;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LabController extends Controller
{
    public function bookingForm(Request $request): View
    {
        $rooms = Room::where('is_active', true)
            ->where('is_bookable', true)
            ->with(['subjects.competences.imoModelCourse', 'materials'])
            ->orderBy('name')
            ->get();

        $subjects = Subject::where('is_active', true)
            ->with(['competences.imoModelCourse', 'rooms'])
            ->orderBy('name')
            ->get();

        $lecturers = Employee::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();

        $preselectedRoom = $request->query('room_code')
            ? $rooms->firstWhere('code', $request->query('room_code'))
            : null;

        return view('lab.booking', compact('rooms', 'subjects', 'lecturers', 'units', 'preselectedRoom'));
    }

    public function storeBooking(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'responsible_lecturer_id' => 'required|exists:employees,id',
            'unit_id' => 'required|exists:units,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'purpose' => 'required|string|max:255',
            'participant_count' => 'required|integer|min:1|max:100',
            'class_group' => 'required|string|max:50',
            'notes' => 'nullable|string',
            'competence_ids' => 'required|array|min:1',
            'competence_ids.*' => 'exists:competences,id',
        ]);

        $startAt = Carbon::parse($validated['booking_date'].' '.$validated['start_time']);
        $endAt = Carbon::parse($validated['booking_date'].' '.$validated['end_time']);

        // Check if slot overlaps in DB
        $conflict = BookingSlot::where('room_id', $validated['room_id'])
            ->whereBetween('slot_start', [$startAt, $endAt->copy()->subMinute()])
            ->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Waktu yang dipilih bertabrakan dengan jadwal booking lain! Silakan pilih jam atau slot lain.');
        }

        // Generate booking number
        $bookingNumber = 'BOOK-'.date('Ym').'-'.str_pad((string) (Booking::count() + 1), 4, '0', STR_PAD_LEFT);

        $defaultEmployee = Employee::first();

        $booking = Booking::create([
            'booking_number' => $bookingNumber,
            'room_id' => $validated['room_id'],
            'subject_id' => $validated['subject_id'],
            'unit_id' => $validated['unit_id'],
            'requester_type' => 'employee',
            'requester_id' => $defaultEmployee->id,
            'responsible_lecturer_id' => $validated['responsible_lecturer_id'],
            'start_at' => $startAt,
            'end_at' => $endAt,
            'purpose' => $validated['purpose'],
            'participant_count' => $validated['participant_count'],
            'class_group' => $validated['class_group'],
            'status' => RequestStatus::Submitted,
            'submitted_at' => now(),
            'notes' => $validated['notes'],
        ]);

        // Attach competences
        $booking->competences()->sync($validated['competence_ids']);

        // Create 30-min booking slots
        $current = $startAt->copy();
        while ($current < $endAt) {
            BookingSlot::create([
                'booking_id' => $booking->id,
                'room_id' => $booking->room_id,
                'slot_start' => $current->copy(),
            ]);
            $current->addMinutes(30);
        }

        // Request Log
        RequestLog::create([
            'loggable_type' => Booking::class,
            'loggable_id' => $booking->id,
            'actor_type' => 'employee',
            'actor_id' => $defaultEmployee->id,
            'role' => 'teacher',
            'action' => 'submit',
            'from_status' => 'draft',
            'to_status' => 'submitted',
            'note' => 'Pengajuan booking baru berhasil disimpan dan diteruskan ke Petugas SPP.',
        ]);

        return redirect()->route('lab.monitoring', ['highlight' => $booking->id])
            ->with('success', "Permohonan booking {$booking->booking_number} berhasil dikirim! Menunggu verifikasi Petugas SPP.");
    }

    public function monitoring(Request $request): View
    {
        $statusFilter = $request->query('status');
        $roomFilter = $request->query('room_id');

        $query = Booking::with(['room', 'subject', 'responsibleLecturer', 'requester', 'realization', 'generatedDocument'])
            ->latest('start_at');

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        if ($roomFilter) {
            $query->where('room_id', $roomFilter);
        }

        $bookings = $query->paginate(10)->withQueryString();
        $rooms = Room::where('is_bookable', true)->get();

        $stats = [
            'total' => Booking::count(),
            'submitted' => Booking::where('status', RequestStatus::Submitted)->count(),
            'verified' => Booking::where('status', RequestStatus::Verified)->count(),
            'approved' => Booking::where('status', RequestStatus::Approved)->count(),
            'completed' => Booking::where('status', RequestStatus::Completed)->count(),
        ];

        return view('lab.monitoring', compact('bookings', 'rooms', 'stats', 'statusFilter', 'roomFilter'));
    }

    public function detail(int $id): View
    {
        $booking = Booking::with([
            'room.materials',
            'subject.competences.imoModelCourse',
            'competences.imoModelCourse',
            'responsibleLecturer',
            'requester',
            'materials.material',
            'slots',
            'realization.recorder',
            'requestLogs' => fn ($q) => $q->latest('created_at'),
            'generatedDocument',
        ])->findOrFail($id);

        return view('lab.detail', compact('booking'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        $action = $request->input('action');
        $note = $request->input('note', 'Perubahan status via alur verifikasi.');
        $admin = Employee::first();

        $prevStatus = $booking->status->value;

        if ($action === 'verify' && $booking->status === RequestStatus::Submitted) {
            $booking->status = RequestStatus::Verified;
            $role = 'officer';
        } elseif ($action === 'approve' && $booking->status === RequestStatus::Verified) {
            $booking->status = RequestStatus::Approved;
            $role = 'leader';
        } elseif ($action === 'request_revision') {
            $booking->status = RequestStatus::RevisionRequested;
            $role = 'officer';
        } elseif ($action === 'reject') {
            $booking->status = RequestStatus::Rejected;
            $role = 'leader';
            // Release slots on reject
            $booking->slots()->delete();
        } else {
            return back()->with('error', 'Aksi tidak valid untuk status booking saat ini.');
        }

        $booking->save();

        RequestLog::create([
            'loggable_type' => Booking::class,
            'loggable_id' => $booking->id,
            'actor_type' => 'employee',
            'actor_id' => $admin->id,
            'role' => $role,
            'action' => $action,
            'from_status' => $prevStatus,
            'to_status' => $booking->status->value,
            'note' => $note,
        ]);

        return back()->with('success', "Status booking {$booking->booking_number} berhasil diperbarui menjadi {$booking->status->label()}!");
    }

    public function recordRealization(Request $request, int $id): RedirectResponse
    {
        $booking = Booking::findOrFail($id);
        $admin = Employee::first();

        $validated = $request->validate([
            'actual_start_at' => 'required|date',
            'actual_end_at' => 'required|date|after:actual_start_at',
            'actual_participant_count' => 'required|integer|min:1',
            'condition_after' => 'required|string',
            'incident_note' => 'nullable|string',
        ]);

        BookingRealization::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'recorded_by' => $admin->id,
                'actual_start_at' => Carbon::parse($validated['actual_start_at']),
                'actual_end_at' => Carbon::parse($validated['actual_end_at']),
                'actual_participant_count' => $validated['actual_participant_count'],
                'condition_after' => ItemCondition::from($validated['condition_after']),
                'incident_note' => $validated['incident_note'],
            ]
        );

        $booking->status = RequestStatus::Completed;
        $booking->save();

        RequestLog::create([
            'loggable_type' => Booking::class,
            'loggable_id' => $booking->id,
            'actor_type' => 'employee',
            'actor_id' => $admin->id,
            'role' => 'teacher',
            'action' => 'record_realization',
            'from_status' => RequestStatus::Approved->value,
            'to_status' => RequestStatus::Completed->value,
            'note' => 'Pencatatan realisasi praktikum lab berhasil disimpan.',
        ]);

        return back()->with('success', "Realisasi sesi praktikum {$booking->booking_number} berhasil dicatat dan status selesai!");
    }
}
