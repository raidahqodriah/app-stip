<?php

namespace App\Filament\Student\Widgets;

use App\Enums\RequestStatus;
use App\Models\Core\Student;
use App\Models\Lab\Booking;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class StudentUpcomingLabSessionsTable extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.student_upcoming_lab_sessions');
    }

    public function table(Table $table): Table
    {
        /** @var Student|null $student */
        $student = auth()->user();
        $classGroup = $student?->class_group;
        $studentId = $student?->id ?? 0;

        return $table
            ->query(
                Booking::query()
                    ->with(['room', 'subject', 'responsibleLecturer'])
                    ->where(function ($q) use ($studentId, $classGroup) {
                        // Sesi mandiri taruna atau sesi praktikum kelasnya
                        $q->where(function ($sub) use ($studentId) {
                            $sub->where('requester_type', Student::class)
                                ->where('requester_id', $studentId);
                        });

                        if ($classGroup) {
                            $q->orWhere('class_group', $classGroup);
                        }
                    })
                    ->whereIn('status', [RequestStatus::Approved, RequestStatus::InUse])
                    ->where('end_at', '>=', Carbon::now())
                    ->orderBy('start_at')
            )
            ->columns([
                TextColumn::make('start_at')
                    ->label('Tanggal & Jam Sesi')
                    ->dateTime('d M Y, H:i')
                    ->description(fn (Booking $record): string => $record->start_at->diffForHumans())
                    ->icon('heroicon-m-calendar')
                    ->weight('bold'),

                TextColumn::make('room.name')
                    ->label('Laboratorium / Simulator')
                    ->description(fn (Booking $record): ?string => $record->room?->code)
                    ->icon('heroicon-m-computer-desktop'),

                TextColumn::make('subject.name')
                    ->label('Mata Kuliah / Modul')
                    ->description(fn (Booking $record): ?string => $record->purpose)
                    ->searchable(),

                TextColumn::make('responsibleLecturer.name')
                    ->label('Dosen Pengampu')
                    ->icon('heroicon-m-user'),

                TextColumn::make('status')
                    ->label('Status Sesi')
                    ->badge(),
            ])
            ->paginated([5]);
    }
}
