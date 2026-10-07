<?php

namespace App\Filament\Admin\Resources\Bookings;

use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Filament\Admin\Clusters\Lab\LabCluster;
use App\Filament\Admin\Resources\Bookings\Pages\CreateBooking;
use App\Filament\Admin\Resources\Bookings\Pages\EditBooking;
use App\Filament\Admin\Resources\Bookings\Pages\ListBookings;
use App\Models\Core\Employee;
use App\Models\Core\RequestLog;
use App\Models\Core\Room;
use App\Models\Lab\Booking;
use App\Models\Lab\BookingRealization;
use App\Models\Lab\BookingSlot;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $cluster = LabCluster::class;

    protected static ?string $navigationLabel = 'Booking Lab & Simulator';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'booking_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_number')
                    ->label('Nomor Booking')
                    ->default(fn () => 'BK-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -4)))
                    ->required()
                    ->readOnly(),
                Select::make('room_id')
                    ->label('Laboratorium / Simulator')
                    ->options(Room::where('is_bookable', true)->where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('subject_id')
                    ->label('Mata Kuliah Praktikum')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('unit_id')
                    ->label('Program Studi / Unit Pengusul')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('responsible_lecturer_id')
                    ->label('Dosen Penanggung Jawab')
                    ->relationship('responsibleLecturer', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('start_at')
                    ->label('Waktu Mulai')
                    ->seconds(false)
                    ->required(),
                DateTimePicker::make('end_at')
                    ->label('Waktu Selesai')
                    ->seconds(false)
                    ->required(),
                TextInput::make('participant_count')
                    ->label('Jumlah Peserta (Taruna)')
                    ->numeric()
                    ->default(20)
                    ->required(),
                TextInput::make('class_group')
                    ->label('Kelas / Peleton')
                    ->placeholder('Mis. T-IV-A / N-IV-B')
                    ->required(),
                TextInput::make('purpose')
                    ->label('Tujuan Praktikum')
                    ->default('Praktik RPS dan Sertifikasi Kompetensi Pelaut')
                    ->required(),
                Select::make('competences')
                    ->label('Kompetensi IMO Terkait')
                    ->relationship('competences', 'title')
                    ->multiple()
                    ->preload(),
                Select::make('status')
                    ->label('Status Pengajuan')
                    ->options(RequestStatus::class)
                    ->default(RequestStatus::Submitted)
                    ->required(),
                Textarea::make('notes')
                    ->label('Catatan Tambahan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('booking_number')
            ->defaultSort('start_at', 'desc')
            ->columns([
                TextColumn::make('booking_number')
                    ->label('No. Booking')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('room.name')
                    ->label('Lab / Simulator')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject.name')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->limit(25),
                TextColumn::make('start_at')
                    ->label('Jadwal Sesi')
                    ->formatStateUsing(fn ($record) => $record->start_at->format('d/m/Y H:i').' - '.$record->end_at->format('H:i'))
                    ->sortable(),
                TextColumn::make('responsibleLecturer.name')
                    ->label('Dosen PJ')
                    ->searchable(),
                TextColumn::make('participant_count')
                    ->label('Peserta')
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (RequestStatus $state): string => match ($state) {
                        RequestStatus::Draft => 'gray',
                        RequestStatus::Submitted => 'warning',
                        RequestStatus::RevisionRequested => 'orange',
                        RequestStatus::Verified => 'info',
                        RequestStatus::Approved => 'success',
                        RequestStatus::InUse => 'primary',
                        RequestStatus::Completed => 'teal',
                        RequestStatus::Rejected => 'danger',
                        RequestStatus::Cancelled, RequestStatus::NoShow, RequestStatus::Expired => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('room_id')
                    ->label('Laboratorium')
                    ->relationship('room', 'name'),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(RequestStatus::class),
            ])
            ->recordActions([
                // Verifikasi Tahap 1 (Petugas SPP)
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->visible(fn (Booking $record) => in_array($record->status, [RequestStatus::Submitted, RequestStatus::RevisionRequested]))
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi Kesiapan Lab & Jadwal (Tahap 1)')
                    ->form([
                        Textarea::make('note')
                            ->label('Catatan Petugas Pemeriksa')
                            ->placeholder('Lab dan bahan siap digunakan.')
                            ->required(),
                    ])
                    ->action(function (Booking $record, array $data) {
                        $record->update(['status' => RequestStatus::Verified]);
                        RequestLog::create([
                            'loggable_type' => Booking::class,
                            'loggable_id' => $record->id,
                            'actor_type' => Employee::class,
                            'actor_id' => auth()->id() ?? 1,
                            'role' => 'officer',
                            'action' => 'verify',
                            'from_status' => RequestStatus::Submitted->value,
                            'to_status' => RequestStatus::Verified->value,
                            'note' => $data['note'],
                        ]);
                        Notification::make()->title('Booking berhasil diverifikasi (Tahap 1)')->success()->send();
                    }),

                // Persetujuan Tahap 2 (Kepala Unit SPP)
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Booking $record) => $record->status === RequestStatus::Verified)
                    ->requiresConfirmation()
                    ->modalHeading('Persetujuan Kepala Unit SPP (Tahap 2)')
                    ->form([
                        Textarea::make('note')
                            ->label('Catatan Persetujuan')
                            ->placeholder('Disetujui untuk dilaksanakan.')
                            ->nullable(),
                    ])
                    ->action(function (Booking $record, array $data) {
                        $record->update(['status' => RequestStatus::Approved]);

                        // Generate booking slots (tiap 30 menit)
                        $start = Carbon::parse($record->start_at);
                        $end = Carbon::parse($record->end_at);
                        while ($start < $end) {
                            BookingSlot::firstOrCreate([
                                'booking_id' => $record->id,
                                'room_id' => $record->room_id,
                                'slot_start' => $start->copy(),
                            ]);
                            $start->addMinutes(30);
                        }

                        RequestLog::create([
                            'loggable_type' => Booking::class,
                            'loggable_id' => $record->id,
                            'actor_type' => Employee::class,
                            'actor_id' => auth()->id() ?? 1,
                            'role' => 'leader',
                            'action' => 'approve',
                            'from_status' => RequestStatus::Verified->value,
                            'to_status' => RequestStatus::Approved->value,
                            'note' => $data['note'] ?? 'Disetujui Ka. Unit SPP',
                        ]);
                        Notification::make()->title('Booking berhasil disetujui & slot dikunci')->success()->send();
                    }),

                // Realisasi Pelaksanaan Lab
                Action::make('record_realization')
                    ->label('Catat Realisasi')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('teal')
                    ->visible(fn (Booking $record) => in_array($record->status, [RequestStatus::Approved, RequestStatus::InUse]))
                    ->form([
                        DateTimePicker::make('actual_start_at')
                            ->label('Waktu Mulai Aktual')
                            ->default(fn (Booking $record) => $record->start_at)
                            ->required(),
                        DateTimePicker::make('actual_end_at')
                            ->label('Waktu Selesai Aktual')
                            ->default(fn (Booking $record) => $record->end_at)
                            ->required(),
                        TextInput::make('actual_participant_count')
                            ->label('Jumlah Peserta Hadir')
                            ->numeric()
                            ->default(fn (Booking $record) => $record->participant_count)
                            ->required(),
                        Select::make('condition_after')
                            ->label('Kondisi Peralatan Pasca Sesi')
                            ->options(ItemCondition::class)
                            ->default(ItemCondition::Good)
                            ->required(),
                        Textarea::make('incident_note')
                            ->label('Catatan Khusus / Insiden (Jika ada)'),
                    ])
                    ->action(function (Booking $record, array $data) {
                        BookingRealization::updateOrCreate(
                            ['booking_id' => $record->id],
                            [
                                'recorded_by' => auth()->id() ?? 1,
                                'actual_start_at' => $data['actual_start_at'],
                                'actual_end_at' => $data['actual_end_at'],
                                'actual_participant_count' => $data['actual_participant_count'],
                                'condition_after' => $data['condition_after'],
                                'incident_note' => $data['incident_note'] ?? null,
                            ]
                        );
                        $record->update(['status' => RequestStatus::Completed]);
                        Notification::make()->title('Realisasi sesi lab berhasil dicatat')->success()->send();
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookings::route('/'),
            'create' => CreateBooking::route('/create'),
            'edit' => EditBooking::route('/{record}/edit'),
        ];
    }
}
