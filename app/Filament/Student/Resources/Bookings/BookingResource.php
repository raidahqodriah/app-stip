<?php

namespace App\Filament\Student\Resources\Bookings;

use App\Enums\RequestStatus;
use App\Filament\Student\Resources\Bookings\Pages\CreateBooking;
use App\Filament\Student\Resources\Bookings\Pages\EditBooking;
use App\Filament\Student\Resources\Bookings\Pages\ListBookings;
use App\Models\Core\Employee;
use App\Models\Core\Room;
use App\Models\Core\Student;
use App\Models\Lab\Booking;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationLabel = 'Booking Mandiri Lab';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'booking_number';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('booking_number')
                    ->label('Nomor Pengajuan')
                    ->default(fn () => 'BK-TRN-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -4)))
                    ->readOnly()
                    ->required(),
                Select::make('room_id')
                    ->label('Laboratorium / Simulator Mandiri')
                    ->options(Room::where('is_bookable', true)->where('is_active', true)->pluck('name', 'id'))
                    ->helperText('Taruna dapat mengajukan latihan mandiri (ERCS, CBT, dsb)')
                    ->searchable()
                    ->required(),
                Select::make('subject_id')
                    ->label('Mata Kuliah Relevan')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('responsible_lecturer_id')
                    ->label('Dosen Pengampu / Penanggung Jawab')
                    ->options(Employee::where('position', 'like', '%Dosen%')->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                DateTimePicker::make('start_at')
                    ->label('Waktu Mulai')
                    ->seconds(false)
                    ->required(),
                DateTimePicker::make('end_at')
                    ->label('Waktu Selesai')
                    ->seconds(false)
                    ->required(),
                TextInput::make('purpose')
                    ->label('Tujuan Latihan Mandiri')
                    ->placeholder('Mis. Persiapan Ujian Sertifikasi Simulator / Pengayaan RPS')
                    ->required(),
                Textarea::make('notes')
                    ->label('Keterangan Tambahan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('requester_type', Student::class)->where('requester_id', auth()->id()))
            ->recordTitleAttribute('booking_number')
            ->defaultSort('start_at', 'desc')
            ->columns([
                TextColumn::make('booking_number')
                    ->label('No. Booking')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('room.name')
                    ->label('Lab / Simulator')
                    ->searchable(),
                TextColumn::make('subject.name')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('responsibleLecturer.name')
                    ->label('Dosen PJ')
                    ->searchable(),
                TextColumn::make('start_at')
                    ->label('Jadwal Sesi')
                    ->formatStateUsing(fn ($record) => $record->start_at->format('d/m/Y H:i').' - '.$record->end_at->format('H:i'))
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (RequestStatus $state): string => match ($state) {
                        RequestStatus::Draft => 'gray',
                        RequestStatus::Submitted => 'warning',
                        RequestStatus::RevisionRequested => 'orange',
                        RequestStatus::Verified => 'info',
                        RequestStatus::Approved => 'success',
                        RequestStatus::Completed => 'teal',
                        RequestStatus::Rejected => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(RequestStatus::class),
            ])
            ->recordActions([
                EditAction::make()->visible(fn (Booking $record) => in_array($record->status, [RequestStatus::Draft, RequestStatus::RevisionRequested])),
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
