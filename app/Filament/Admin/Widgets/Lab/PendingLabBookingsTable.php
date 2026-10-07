<?php

namespace App\Filament\Admin\Widgets\Lab;

use App\Enums\RequestStatus;
use App\Models\Core\Employee;
use App\Models\Lab\Booking;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PendingLabBookingsTable extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.pending_lab_bookings');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer']) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->with(['room', 'subject', 'requester'])
                    ->whereIn('status', [RequestStatus::Submitted, RequestStatus::Verified])
                    ->orderBy('submitted_at')
            )
            ->columns([
                TextColumn::make('booking_number')
                    ->label('No. Booking')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('requester.name')
                    ->label('Pemohon')
                    ->icon('heroicon-m-user'),

                TextColumn::make('room.name')
                    ->label('Laboratorium')
                    ->description(fn (Booking $record): ?string => $record->subject?->name),

                TextColumn::make('start_at')
                    ->label('Jadwal Sesi Diajukan')
                    ->dateTime('d M Y, H:i')
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('submitted_at')
                    ->label('Waktu Tunggu (SLA)')
                    ->state(fn (Booking $record): string => $record->submitted_at ? $record->submitted_at->diffForHumans() : '-')
                    ->badge()
                    ->color(function (Booking $record): string {
                        if (! $record->submitted_at) {
                            return 'gray';
                        }
                        $hours = Carbon::now()->diffInHours($record->submitted_at);

                        return $hours > 24 ? 'danger' : ($hours > 12 ? 'warning' : 'info');
                    }),

                TextColumn::make('status')
                    ->label('Tahapan Status')
                    ->badge(),
            ])
            ->actions([
                Action::make('verify')
                    ->label('Verifikasi (Petugas)')
                    ->icon('heroicon-m-clipboard-document-check')
                    ->color('info')
                    ->visible(fn (Booking $record): bool => $record->status === RequestStatus::Submitted && (auth()->user()?->hasAnyRole(['admin', 'officer']) ?? false))
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Kesiapan Lab & Bahan')
                    ->modalDescription('Apakah sarana lab, ketersediaan bahan, dan relevansi kurikulum telah diverifikasi?')
                    ->action(function (Booking $record): void {
                        $record->update(['status' => RequestStatus::Verified]);
                        Notification::make()
                            ->title('Booking Lolos Verifikasi')
                            ->body('Status diteruskan ke Kepala Unit SPP untuk persetujuan akhir.')
                            ->success()
                            ->send();
                    }),

                Action::make('approve')
                    ->label('Setujui (Ka. SPP)')
                    ->icon('heroicon-m-check-badge')
                    ->color('success')
                    ->visible(fn (Booking $record): bool => $record->status === RequestStatus::Verified && (auth()->user()?->hasAnyRole(['admin', 'leader']) ?? false))
                    ->requiresConfirmation()
                    ->modalHeading('Persetujuan Akhir Booking Lab')
                    ->modalDescription('Setujui pengajuan booking lab ini dan terbitkan surat konfirmasi PDF?')
                    ->action(function (Booking $record): void {
                        $record->update(['status' => RequestStatus::Approved]);
                        Notification::make()
                            ->title('Booking Berhasil Disetujui')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['admin', 'leader', 'officer']) ?? false)
                    ->form([
                        Textarea::make('notes')
                            ->label('Alasan Penolakan (Wajib)')
                            ->required(),
                    ])
                    ->action(function (Booking $record, array $data): void {
                        $record->update([
                            'status' => RequestStatus::Rejected,
                            'notes' => $data['notes'],
                        ]);
                        // Hapus slot penahan booking
                        $record->slots()->delete();

                        Notification::make()
                            ->title('Booking Ditolak')
                            ->body('Slot ruangan telah dilepas kembali.')
                            ->warning()
                            ->send();
                    }),
            ])
            ->paginated([5, 10]);
    }
}
