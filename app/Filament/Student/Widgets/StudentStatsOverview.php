<?php

namespace App\Filament\Student\Widgets;

use App\Enums\CirculationStatus;
use App\Models\Core\Student;
use App\Models\Lab\Booking;
use App\Models\Library\Circulation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $studentId = auth()->id() ?? 0;

        $myBookings = Booking::where('requester_type', Student::class)
            ->where('requester_id', $studentId)
            ->count();

        $activeLoans = Circulation::where('borrower_type', Student::class)
            ->where('borrower_id', $studentId)
            ->where('status', CirculationStatus::Borrowed)
            ->count();

        $unpaidFines = Circulation::where('borrower_type', Student::class)
            ->where('borrower_id', $studentId)
            ->where('fine_paid', false)
            ->where('fine_amount', '>', 0)
            ->sum('fine_amount');

        return [
            Stat::make('Pengajuan Booking Mandiri Saya', $myBookings)
                ->description('Riwayat sesi latihan lab / simulator')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Buku Sedang Dipinjam', $activeLoans)
                ->description('Maksimal kuota 3 buku')
                ->descriptionIcon('heroicon-m-book-open')
                ->color($activeLoans >= 3 ? 'warning' : 'success'),

            Stat::make('Tunggakan Denda', 'Rp '.number_format($unpaidFines, 0, ',', '.'))
                ->description($unpaidFines > 0 ? 'Harap lunasi di loket perpustakaan' : 'Tidak ada tunggakan')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($unpaidFines > 0 ? 'danger' : 'success'),
        ];
    }
}
