<?php

namespace App\Livewire\Front;

use App\Models\Core\Room;
use App\Models\Lab\BlackoutDate;
use App\Models\Lab\BookingSlot;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.front')]
class LabDetail extends Component
{
    public string $code = '';

    public ?Room $room = null;

    public bool $linkCopied = false;

    public function mount(string $code): void
    {
        $this->code = strtoupper($code);
        $this->room = Room::with([
            'unit',
            'picEmployee',
            'subjects.competences.imoModelCourse',
            'materials',
            'materialKits.items.material',
        ])
            ->where('code', $this->code)
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function copyShareLink(): void
    {
        $this->linkCopied = true;
    }

    public function render(): View
    {
        $today = Carbon::now();

        // Check blackout for this room
        $hasBlackout = BlackoutDate::where(function ($q) {
            $q->whereNull('room_id')->orWhere('room_id', $this->room->id);
        })
            ->where('start_at', '<=', $today->copy()->endOfDay())
            ->where('end_at', '>=', $today->copy()->startOfDay())
            ->first();

        // Today's booked slots
        $bookedSlots = BookingSlot::with('booking.subject')
            ->where('room_id', $this->room->id)
            ->whereDate('slot_start', $today->toDateString())
            ->get()
            ->keyBy(function ($slot) {
                return Carbon::parse($slot->slot_start)->format('H:i');
            });

        // Generate 30-min time slots from 07:30 to 16:00
        $slots = [];
        $slotTime = $today->copy()->setTime(7, 30);
        $slotEndTime = $today->copy()->setTime(16, 0);

        while ($slotTime < $slotEndTime) {
            $timeKey = $slotTime->format('H:i');
            $nextTime = $slotTime->copy()->addMinutes(30)->format('H:i');

            if ($hasBlackout) {
                $status = 'maintenance';
                $label = 'Pemeliharaan';
            } elseif ($bookedSlots->has($timeKey)) {
                $status = 'terisi';
                $booking = $bookedSlots->get($timeKey)->booking;
                $label = $booking?->subject?->name ?? 'Praktikum Terjadwal';
            } else {
                $status = 'tersedia';
                $label = 'Slot Tersedia';
            }

            $slots[] = [
                'time_start' => $timeKey,
                'time_end' => $nextTime,
                'status' => $status,
                'label' => $label,
            ];

            $slotTime->addMinutes(30);
        }

        // Count available slots
        $availableSlotsCount = collect($slots)->where('status', 'tersedia')->count();

        return view('livewire.front.lab-detail', [
            'hasBlackout' => $hasBlackout,
            'slots' => $slots,
            'availableSlotsCount' => $availableSlotsCount,
        ])->title($this->room->name.' ('.$this->room->code.') | STIP Jakarta');
    }
}
