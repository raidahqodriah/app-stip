<?php

namespace App\Filament\Admin\Widgets;

use App\Enums\CirculationStatus;
use App\Enums\RequestStatus;
use App\Models\Bmn\BmnItem;
use App\Models\Bmn\BmnSubmission;
use App\Models\Bmn\ResidencePermit;
use App\Models\Lab\Booking;
use App\Models\Library\Circulation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $pendingBookings = Booking::where('status', RequestStatus::Submitted)->count();
        $approvedBookings = Booking::where('status', RequestStatus::Approved)->count();

        $pendingBmn = BmnSubmission::where('status', RequestStatus::Submitted)->count();
        $totalBmn = BmnItem::count();

        $activeLoans = Circulation::where('status', CirculationStatus::Borrowed)->count();
        $pendingSip = ResidencePermit::where('status', RequestStatus::Submitted)->count();

        return [
            Stat::make('Booking Lab Menunggu Verifikasi', $pendingBookings)
                ->description("{$approvedBookings} sesi telah disetujui")
                ->descriptionIcon('heroicon-m-calendar')
                ->color($pendingBookings > 0 ? 'warning' : 'success'),

            Stat::make('Pengajuan BMN Baru', $pendingBmn)
                ->description("Total {$totalBmn} unit BMN terdaftar")
                ->descriptionIcon('heroicon-m-cube')
                ->color($pendingBmn > 0 ? 'warning' : 'info'),

            Stat::make('Buku Sedang Dipinjam', $activeLoans)
                ->description('Transaksi sirkulasi aktif perpustakaan')
                ->descriptionIcon('heroicon-m-book-open')
                ->color('primary'),

            Stat::make('Permohonan SIP Rumah Dinas', $pendingSip)
                ->description('Menunggu pemeriksaan / persetujuan')
                ->descriptionIcon('heroicon-m-home')
                ->color($pendingSip > 0 ? 'warning' : 'gray'),
        ];
    }
}
