<?php

namespace App\Filament\Admin\Widgets\Residence;

use App\Enums\RequestStatus;
use App\Models\Bmn\ResidencePermit;
use App\Models\Core\Employee;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ExpiringResidencePermitsTable extends BaseWidget
{
    protected static ?int $sort = 15;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.expiring_residence_permits');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer']) ?? false;
    }

    public function table(Table $table): Table
    {
        $threshold = Carbon::now()->addDays(60);

        return $table
            ->query(
                ResidencePermit::query()
                    ->with(['employee', 'officialResidence', 'unit'])
                    ->where('status', RequestStatus::Approved)
                    ->whereNotNull('occupancy_end')
                    ->where('occupancy_end', '<=', $threshold)
                    ->orderBy('occupancy_end')
            )
            ->columns([
                TextColumn::make('permit_number')
                    ->label('No. SIP')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('employee.name')
                    ->label('Nama Pegawai Penghuni')
                    ->icon('heroicon-m-user'),

                TextColumn::make('officialResidence.house_number')
                    ->label('Rumah Dinas')
                    ->description(fn (ResidencePermit $record): ?string => $record->officialResidence?->address)
                    ->badge()
                    ->color('info'),

                TextColumn::make('occupancy_end')
                    ->label('Akhir Masa Huni')
                    ->date('d M Y')
                    ->icon('heroicon-m-clock'),

                TextColumn::make('remaining_days')
                    ->label('Sisa Waktu')
                    ->state(function (ResidencePermit $record): string {
                        $diff = Carbon::now()->diffInDays($record->occupancy_end, false);
                        if ($diff < 0) {
                            return 'Kedaluwarsa '.abs((int) $diff).' hari lalu';
                        }

                        return $diff.' hari lagi';
                    })
                    ->badge()
                    ->color(function (ResidencePermit $record): string {
                        $diff = Carbon::now()->diffInDays($record->occupancy_end, false);

                        return $diff <= 0 ? 'danger' : ($diff <= 30 ? 'warning' : 'success');
                    }),
            ])
            ->paginated([5]);
    }
}
