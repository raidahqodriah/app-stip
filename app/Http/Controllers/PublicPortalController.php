<?php

namespace App\Http\Controllers;

use App\Enums\RequestStatus;
use App\Models\Bmn\BmnItem;
use App\Models\Core\Room;
use App\Models\Lab\Booking;
use App\Models\Lab\BookingSlot;
use App\Models\Library\Book;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPortalController extends Controller
{
    public function index(): View
    {
        $rooms = Room::where('is_active', true)
            ->where('is_bookable', true)
            ->orderBy('name')
            ->get();

        $recentBookings = Booking::with(['room', 'subject', 'requester', 'responsibleLecturer'])
            ->whereIn('status', [
                RequestStatus::Approved,
                RequestStatus::Completed,
                RequestStatus::Verified,
                RequestStatus::Submitted,
            ])
            ->latest('start_at')
            ->limit(10)
            ->get();

        $stats = [
            'rooms' => Room::where('is_bookable', true)->count(),
            'bookings' => Booking::count(),
            'books' => Book::count(),
            'bmn' => BmnItem::count(),
        ];

        return view('welcome', compact('rooms', 'recentBookings', 'stats'));
    }

    public function schedule(Request $request): View
    {
        $selectedDate = $request->input('date', Carbon::today()->toDateString());
        $date = Carbon::parse($selectedDate);
        $roomId = $request->input('room_id');

        $rooms = Room::where('is_active', true)
            ->where('is_bookable', true)
            ->orderBy('name')
            ->get();

        $activeRoom = $roomId ? $rooms->firstWhere('id', $roomId) : $rooms->first();

        // Operasional 07:30 - 16:00 WIB (slot 30 menit)
        $timeSlots = [];
        $startTime = Carbon::parse($selectedDate.' 07:30:00');
        $endTime = Carbon::parse($selectedDate.' 16:00:00');

        $slots = [];
        if ($activeRoom) {
            $slots = BookingSlot::with('booking.subject')
                ->where('room_id', $activeRoom->id)
                ->whereDate('slot_start', $date)
                ->get()
                ->keyBy(fn ($item) => $item->slot_start->format('H:i'));
        }

        $current = $startTime->copy();
        while ($current < $endTime) {
            $slotKey = $current->format('H:i');
            $next = $current->copy()->addMinutes(30);
            $bookedSlot = $slots->get($slotKey);

            $timeSlots[] = [
                'start' => $slotKey,
                'end' => $next->format('H:i'),
                'is_booked' => $bookedSlot !== null,
                'subject' => $bookedSlot?->booking?->subject?->name,
                'purpose' => $bookedSlot?->booking?->purpose,
                'status' => $bookedSlot?->booking?->status?->label() ?? 'Tersedia',
            ];

            $current->addMinutes(30);
        }

        // Bookings of the week for timeline preview
        $weekBookings = Booking::with(['room', 'subject'])
            ->where('room_id', $activeRoom?->id)
            ->whereBetween('start_at', [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()])
            ->orderBy('start_at')
            ->get();

        return view('public.schedule', compact('rooms', 'activeRoom', 'date', 'timeSlots', 'weekBookings'));
    }

    public function labDetail(string $code): View
    {
        $room = Room::with(['pic', 'unit', 'subjects.competences.imoModelCourse', 'materials'])
            ->where('code', $code)
            ->where('is_bookable', true)
            ->firstOrFail();

        $upcomingBookings = Booking::with(['subject', 'responsibleLecturer'])
            ->where('room_id', $room->id)
            ->where('start_at', '>=', Carbon::now()->startOfDay())
            ->whereIn('status', [RequestStatus::Approved, RequestStatus::InUse])
            ->orderBy('start_at')
            ->limit(5)
            ->get();

        return view('public.lab-detail', compact('room', 'upcomingBookings'));
    }
}
