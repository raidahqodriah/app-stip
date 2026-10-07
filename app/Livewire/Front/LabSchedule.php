<?php

namespace App\Livewire\Front;

use App\Enums\RequestStatus;
use App\Models\Core\Room;
use App\Models\Lab\BlackoutDate;
use App\Models\Lab\Booking;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.front')]
#[Title('Jadwal Ketersediaan Lab & Simulator')]
class LabSchedule extends Component
{
    #[Url(as: 'minggu')]
    public int $weekOffset = 0;

    #[Url(as: 'prodi')]
    public string $selectedDepartment = 'all';

    #[Url(as: 'lab')]
    public string $selectedRoomCode = 'all';

    public ?array $selectedSession = null;

    public bool $showModal = false;

    public function prevWeek(): void
    {
        $this->weekOffset--;
    }

    public function nextWeek(): void
    {
        $this->weekOffset++;
    }

    public function currentWeek(): void
    {
        $this->weekOffset = 0;
    }

    public function openSession(int $bookingId): void
    {
        $booking = Booking::with(['room', 'subject'])->find($bookingId);
        if (! $booking) {
            return;
        }

        $this->selectedSession = [
            'booking_number' => $booking->booking_number,
            'room_name' => $booking->room?->name,
            'room_code' => $booking->room?->code,
            'location' => $booking->room?->location,
            'subject_name' => $booking->subject?->name,
            'subject_code' => $booking->subject?->code,
            'class_group' => $booking->class_group,
            'participant_count' => $booking->participant_count,
            'start_at' => Carbon::parse($booking->start_at)->translatedFormat('l, d F Y • H:i'),
            'end_at' => Carbon::parse($booking->end_at)->format('H:i').' WIB',
            'purpose' => $booking->purpose,
            'status' => 'Disetujui (Aktif)',
        ];

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedSession = null;
    }

    public function render(): View
    {
        // 1. Calculate Monday - Friday of the selected week
        $baseDate = Carbon::now()->startOfWeek()->addWeeks($this->weekOffset);
        $days = [];
        for ($i = 0; $i < 5; $i++) {
            $dayDate = $baseDate->copy()->addDays($i);
            $days[] = [
                'date' => $dayDate->toDateString(),
                'day_name' => $dayDate->translatedFormat('l'),
                'day_short' => strtoupper($dayDate->translatedFormat('D')),
                'formatted' => $dayDate->translatedFormat('d M Y'),
                'is_today' => $dayDate->isToday(),
            ];
        }

        $weekStartString = $baseDate->translatedFormat('d');
        $weekEndString = $baseDate->copy()->addDays(4)->translatedFormat('d F Y');
        $weekRangeLabel = "Minggu: {$weekStartString} – {$weekEndString}";

        // 2. Query Labs
        $labQuery = Room::where('is_active', true)->where('type', 'lab');

        if ($this->selectedDepartment !== 'all') {
            if ($this->selectedDepartment === 'teknika') {
                $labQuery->where('lab_category', 'teknika');
            } elseif ($this->selectedDepartment === 'nautika') {
                $labQuery->where('lab_category', 'nautika');
            } elseif ($this->selectedDepartment === 'kalk') {
                $labQuery->whereIn('lab_category', ['kalk', 'nautika']);
            }
        }

        if ($this->selectedRoomCode !== 'all') {
            $labQuery->where('code', $this->selectedRoomCode);
        }

        $labs = $labQuery->orderBy('code')->get();

        // 3. Query approved bookings in this week
        $startDate = $baseDate->copy()->startOfDay();
        $endDate = $baseDate->copy()->addDays(4)->endOfDay();

        $bookings = Booking::with(['room', 'subject'])
            ->where('status', RequestStatus::Approved->value)
            ->whereBetween('start_at', [$startDate, $endDate])
            ->when($this->selectedRoomCode !== 'all', function ($q) {
                $q->whereHas('room', fn ($r) => $r->where('code', $this->selectedRoomCode));
            })
            ->orderBy('start_at')
            ->get();

        // 4. Query blackout dates in this week
        $blackouts = BlackoutDate::with('room')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_at', [$startDate, $endDate])
                    ->orWhereBetween('end_at', [$startDate, $endDate])
                    ->orWhere(function ($sub) use ($startDate, $endDate) {
                        $sub->where('start_at', '<=', $startDate)
                            ->where('end_at', '>=', $endDate);
                    });
            })
            ->get();

        // Group bookings and blackouts by date
        $scheduleMatrix = [];
        foreach ($days as $day) {
            $dateKey = $day['date'];
            $dayBookings = $bookings->filter(function ($b) use ($dateKey) {
                return Carbon::parse($b->start_at)->toDateString() === $dateKey;
            });

            $dayBlackouts = $blackouts->filter(function ($bo) use ($dateKey) {
                $boStart = Carbon::parse($bo->start_at)->toDateString();
                $boEnd = Carbon::parse($bo->end_at)->toDateString();

                return $dateKey >= $boStart && $dateKey <= $boEnd;
            });

            $scheduleMatrix[$dateKey] = [
                'bookings' => $dayBookings,
                'blackouts' => $dayBlackouts,
            ];
        }

        // Summary metrics
        $allLabsCount = Room::where('is_active', true)->where('type', 'lab')->count();
        $activeBlackoutCount = $blackouts->count();
        $availableLabEstimate = max(0, $allLabsCount - $activeBlackoutCount);

        return view('livewire.front.lab-schedule', [
            'days' => $days,
            'labs' => $labs,
            'weekRangeLabel' => $weekRangeLabel,
            'scheduleMatrix' => $scheduleMatrix,
            'availableLabEstimate' => $availableLabEstimate,
            'activeBlackoutCount' => $activeBlackoutCount,
        ]);
    }
}
