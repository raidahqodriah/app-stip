<?php

namespace App\Filament\Admin\Widgets\Lab;

use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Models\Core\Employee;
use App\Models\Lab\Booking;
use App\Models\Lab\BookingRealization;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodayLabScheduleTable extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.today_lab_schedule');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer', 'teacher']) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->with(['room', 'subject', 'responsibleLecturer'])
                    ->whereDate('start_at', Carbon::today())
                    ->whereIn('status', [RequestStatus::Approved, RequestStatus::InUse, RequestStatus::Completed])
                    ->orderBy('start_at')
            )
            ->columns([
                TextColumn::make('time_range')
                    ->label('Jam Sesi')
                    ->state(fn (Booking $record): string => $record->start_at->format('H:i').' - '.$record->end_at->format('H:i'))
                    ->icon('heroicon-m-clock')
                    ->weight('bold'),

                TextColumn::make('room.name')
                    ->label('Laboratorium / Simulator')
                    ->description(fn (Booking $record): ?string => $record->room?->code)
                    ->searchable(),

                TextColumn::make('subject.name')
                    ->label('Mata Kuliah & Kelas')
                    ->description(fn (Booking $record): ?string => $record->class_group)
                    ->searchable(),

                TextColumn::make('responsibleLecturer.name')
                    ->label('Dosen Pengampu')
                    ->icon('heroicon-m-user'),

                TextColumn::make('participant_count')
                    ->label('Peserta')
                    ->suffix(' Orang')
                    ->alignCenter(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
            ])
            ->actions([
                Action::make('check_in')
                    ->label('Mulai Sesi')
                    ->icon('heroicon-m-play')
                    ->color('warning')
                    ->visible(fn (Booking $record): bool => $record->status === RequestStatus::Approved && (auth()->user()?->hasAnyRole(['admin', 'officer', 'teacher']) ?? false))
                    ->requiresConfirmation()
                    ->modalHeading('Mulai Sesi Praktikum')
                    ->modalDescription('Pastikan ruangan simulator telah siap dan peserta telah berada di lokasi.')
                    ->action(function (Booking $record): void {
                        $record->update(['status' => RequestStatus::InUse]);
                        Notification::make()
                            ->title('Sesi Praktikum Dimulai')
                            ->success()
                            ->send();
                    }),

                Action::make('record_realization')
                    ->label('Catat Realisasi')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->visible(fn (Booking $record): bool => $record->status === RequestStatus::InUse && (auth()->user()?->hasAnyRole(['admin', 'officer', 'teacher']) ?? false))
                    ->form([
                        TextInput::make('actual_participant_count')
                            ->label('Jumlah Peserta Hadir (Aktual)')
                            ->numeric()
                            ->required()
                            ->default(fn (Booking $record): int => $record->participant_count),

                        Select::make('condition_after')
                            ->label('Kondisi Peralatan / Lab Selesai Sesi')
                            ->options(ItemCondition::class)
                            ->required()
                            ->default(ItemCondition::Good->value),

                        Textarea::make('incident_note')
                            ->label('Catatan Insiden / Kendala Teknis (Opsional)')
                            ->rows(2),
                    ])
                    ->action(function (Booking $record, array $data): void {
                        BookingRealization::updateOrCreate(
                            ['booking_id' => $record->id],
                            [
                                'recorded_by' => auth()->id() ?? 1,
                                'actual_start_at' => $record->start_at,
                                'actual_end_at' => Carbon::now(),
                                'actual_participant_count' => $data['actual_participant_count'],
                                'condition_after' => $data['condition_after'],
                                'incident_note' => $data['incident_note'] ?? null,
                            ]
                        );

                        $record->update(['status' => RequestStatus::Completed]);

                        Notification::make()
                            ->title('Realisasi Sesi Berhasil Dicatat')
                            ->body('Status booking telah diubah menjadi Selesai (Completed).')
                            ->success()
                            ->send();
                    }),
            ])
            ->paginated([5, 10]);
    }
}
