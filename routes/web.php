<?php

use App\Enums\RequestStatus;
use App\Models\Bmn\BmnItem;
use App\Models\Core\Room;
use App\Models\Lab\Booking;
use App\Models\Library\Book;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $rooms = Room::where('is_active', true)
        ->orderBy('name')
        ->get();

    $recentBookings = Booking::with(['room', 'subject', 'requester'])
        ->whereIn('status', [
            RequestStatus::Approved,
            RequestStatus::Completed,
            RequestStatus::Submitted,
        ])
        ->latest('start_at')
        ->limit(10)
        ->get();

    $stats = [
        'rooms' => Room::count(),
        'bookings' => Booking::count(),
        'books' => Book::count(),
        'bmn' => BmnItem::count(),
    ];

    return view('welcome', compact('rooms', 'recentBookings', 'stats'));
});

Route::get('/jadwal', function () {
    return redirect('/#jadwal');
});
