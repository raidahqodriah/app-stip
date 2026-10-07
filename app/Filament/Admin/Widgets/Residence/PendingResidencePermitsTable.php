<?php

namespace App\Filament\Admin\Widgets\Residence;

use App\Enums\RequestStatus;
use App\Models\Bmn\ResidencePermit;
use App\Models\Core\Employee;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingResidencePermitsTable extends BaseWidget
{
    protected static ?int $sort = 14;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.pending_residence_permits');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer', 'unit_admin']) ?? false;
    }

    public function table(Table $table): Table
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        $query = ResidencePermit::query()
            ->with(['employee', 'officialResidence', 'unit'])
            ->whereIn('status', [RequestStatus::Submitted, RequestStatus::Verified])
            ->latest('created_at');

        if ($user && $user->hasRole('unit_admin') && ! $user->hasAnyRole(['admin', 'leader', 'officer'])) {
            $query->where('unit_id', $user->unit_id);
        }

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('permit_number')
                    ->label('No. Pengajuan SIP')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('employee.name')
                    ->label('Pegawai Calon Penghuni')
                    ->description(fn (ResidencePermit $record): ?string => 'NIP: '.$record->employee?->employee_number)
                    ->icon('heroicon-m-user')
                    ->searchable(),

                TextColumn::make('unit.name')
                    ->label('Unit Kerja Pengusul')
                    ->icon('heroicon-m-building-office'),

                TextColumn::make('officialResidence.house_number')
                    ->label('Rumah Dinas')
                    ->description(fn (ResidencePermit $record): ?string => $record->officialResidence?->address)
                    ->badge()
                    ->color('info'),

                TextColumn::make('occupancy_range')
                    ->label('Rencana Periode Huni')
                    ->state(fn (ResidencePermit $record): string => ($record->occupancy_start?->format('d M Y') ?? '-').' s/d '.($record->occupancy_end?->format('d M Y') ?? '-'))
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('status')
                    ->label('Tahapan Alur')
                    ->badge(),
            ])
            ->actions([
                Action::make('verify')
                    ->label('Verifikasi (Petugas RT)')
                    ->icon('heroicon-m-clipboard-document-check')
                    ->color('info')
                    ->visible(fn (ResidencePermit $record): bool => $record->status === RequestStatus::Submitted && (auth()->user()?->hasAnyRole(['admin', 'officer']) ?? false))
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Kelengkapan Permohonan SIP')
                    ->modalDescription('Apakah berkas persyaratan telah lengkap dan memenuhi kriteria penghunian rumah dinas?')
                    ->action(function (ResidencePermit $record): void {
                        $record->update([
                            'status' => RequestStatus::Verified,
                            'verified_by' => auth()->id() ?? 1,
                        ]);
                        Notification::make()
                            ->title('Permohonan Lolos Verifikasi')
                            ->body('Status diteruskan ke Ketua STIP untuk penerbitan persetujuan.')
                            ->success()
                            ->send();
                    }),

                Action::make('approve')
                    ->label('Setujui (Ketua STIP)')
                    ->icon('heroicon-m-check-badge')
                    ->color('success')
                    ->visible(fn (ResidencePermit $record): bool => $record->status === RequestStatus::Verified && (auth()->user()?->hasAnyRole(['admin', 'leader']) ?? false))
                    ->requiresConfirmation()
                    ->modalHeading('Persetujuan Permohonan SIP Rumah Dinas')
                    ->modalDescription('Setujui izin penghunian rumah dinas dan terbitkan Surat Izin Penghuni (SIP) resmi?')
                    ->action(function (ResidencePermit $record): void {
                        $record->update([
                            'status' => RequestStatus::Approved,
                            'approved_by' => auth()->id() ?? 1,
                        ]);
                        Notification::make()
                            ->title('Izin Penghunian Disetujui')
                            ->body('Surat Izin Penghuni (SIP) telah resmi diterbitkan.')
                            ->success()
                            ->send();
                    }),
            ])
            ->paginated([5]);
    }
}
