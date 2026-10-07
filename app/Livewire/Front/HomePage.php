<?php

namespace App\Livewire\Front;

use App\Enums\RequestStatus;
use App\Models\Bmn\BmnItem;
use App\Models\Core\Room;
use App\Models\Lab\BlackoutDate;
use App\Models\Lab\Booking;
use App\Models\Library\Book;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.front')]
#[Title('Beranda')]
class HomePage extends Component
{
    public function render(): View
    {
        $stats = [
            'labs' => Room::where('is_active', true)->where('type', 'lab')->count(),
            'bookings' => Booking::where('status', RequestStatus::Approved->value)->count(),
            'books' => Book::count(),
            'bmn' => BmnItem::count(),
        ];

        // 8 Active Labs for Quick Card carousel / preview
        $activeLabs = Room::where('is_active', true)
            ->where('type', 'lab')
            ->orderBy('code')
            ->limit(4)
            ->get();

        // Operasional Simulator Live Matrix for current week
        $today = Carbon::now();
        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();

        // Sample schedule matrix data for current week
        $weekBookings = Booking::with('room', 'subject')
            ->where('status', RequestStatus::Approved->value)
            ->whereBetween('start_at', [$startOfWeek, $endOfWeek])
            ->orderBy('start_at')
            ->get();

        // Recent Blackout / Maintenance Alerts
        $activeBlackouts = BlackoutDate::with('room')
            ->where('end_at', '>=', $today->startOfDay())
            ->orderBy('start_at')
            ->limit(3)
            ->get();

        return view('livewire.front.home-page', [
            'stats' => $stats,
            'activeLabs' => $activeLabs,
            'weekBookings' => $weekBookings,
            'activeBlackouts' => $activeBlackouts,
        ]);
    }
}
