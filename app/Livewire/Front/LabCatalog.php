<?php

namespace App\Livewire\Front;

use App\Models\Core\Room;
use App\Models\Lab\BlackoutDate;
use App\Models\Lab\BookingSlot;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.front')]
#[Title('Katalog Laboratorium & Simulator')]
class LabCatalog extends Component
{
    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'prodi')]
    public string $department = 'all';

    #[Url(as: 'status')]
    public string $statusFilter = 'all';

    #[Url(as: 'sort')]
    public string $sortOrder = 'code-asc';

    public function resetFilters(): void
    {
        $this->search = '';
        $this->department = 'all';
        $this->statusFilter = 'all';
        $this->sortOrder = 'code-asc';
    }

    public function render(): View
    {
        $today = Carbon::now();

        // 1. Query base active labs
        $query = Room::where('is_active', true)
            ->where('type', 'lab');

        // Search filter
        if (! empty(trim($this->search))) {
            $term = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('code', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('location', 'like', $term);
            });
        }

        // Department filter
        if ($this->department !== 'all') {
            if ($this->department === 'teknika') {
                $query->where('lab_category', 'teknika');
            } elseif ($this->department === 'nautika') {
                $query->where('lab_category', 'nautika');
            } elseif ($this->department === 'kalk') {
                $query->whereIn('lab_category', ['kalk', 'nautika']);
            } elseif ($this->department === 'umum') {
                $query->whereIn('lab_category', ['all', 'umum']);
            }
        }

        // Sorting
        match ($this->sortOrder) {
            'code-desc' => $query->orderBy('code', 'desc'),
            'cap-desc' => $query->orderBy('capacity', 'desc')->orderBy('code'),
            'cap-asc' => $query->orderBy('capacity', 'asc')->orderBy('code'),
            default => $query->orderBy('code', 'asc'),
        };

        $rooms = $query->get();

        // 2. Compute dynamic operational status for each lab today
        $rooms->each(function (Room $room) use ($today) {
            // Check Blackout / Maintenance
            $hasBlackout = BlackoutDate::where(function ($q) use ($room) {
                $q->whereNull('room_id')->orWhere('room_id', $room->id);
            })
                ->where('start_at', '<=', $today->copy()->endOfDay())
                ->where('end_at', '>=', $today->copy()->startOfDay())
                ->exists();

            if ($hasBlackout) {
                $room->computed_status = 'maintenance';
                $room->status_label = 'Tidak Operasional';
                $room->status_color = 'status-inoperative';
                $room->status_bg = 'status-inoperative-bg';

                return;
            }

            // Check if booked today
            $hasBookings = BookingSlot::where('room_id', $room->id)
                ->whereDate('slot_start', $today->toDateString())
                ->exists();

            if ($hasBookings) {
                $room->computed_status = 'terisi';
                $room->status_label = 'Terisi Sebagian';
                $room->status_color = 'status-terisi';
                $room->status_bg = 'status-terisi-bg';
            } else {
                $room->computed_status = 'tersedia';
                $room->status_label = 'Tersedia Hari Ini';
                $room->status_color = 'secondary';
                $room->status_bg = 'status-tersedia-bg';
            }
        });

        // 3. Filter by computed status if requested
        if ($this->statusFilter !== 'all') {
            $rooms = $rooms->filter(function ($room) {
                return $room->computed_status === $this->statusFilter;
            });
        }

        // Department counts
        $counts = [
            'all' => Room::where('is_active', true)->where('type', 'lab')->count(),
            'teknika' => Room::where('is_active', true)->where('type', 'lab')->where('lab_category', 'teknika')->count(),
            'nautika' => Room::where('is_active', true)->where('type', 'lab')->where('lab_category', 'nautika')->count(),
            'kalk' => Room::where('is_active', true)->where('type', 'lab')->whereIn('lab_category', ['kalk', 'nautika'])->count(),
            'umum' => Room::where('is_active', true)->where('type', 'lab')->whereIn('lab_category', ['all', 'umum'])->count(),
        ];

        return view('livewire.front.lab-catalog', [
            'rooms' => $rooms,
            'counts' => $counts,
        ]);
    }
}
