<?php

namespace App\Filament\Student\Widgets;

use App\Enums\CirculationStatus;
use App\Models\Core\Student;
use App\Models\Library\Circulation;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class StudentActiveLoansTable extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.student_active_loans');
    }

    public function table(Table $table): Table
    {
        $studentId = auth()->id() ?? 0;

        return $table
            ->query(
                Circulation::query()
                    ->with('book')
                    ->where('borrower_type', Student::class)
                    ->where('borrower_id', $studentId)
                    ->where('status', CirculationStatus::Borrowed)
                    ->orderBy('due_date')
            )
            ->columns([
                TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->description(fn (Circulation $record): ?string => $record->book?->author)
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('loan_date')
                    ->label('Tgl Pinjam')
                    ->date('d M Y')
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->icon('heroicon-m-clock'),

                TextColumn::make('due_status')
                    ->label('Status Masa Pinjam')
                    ->state(function (Circulation $record): string {
                        $diff = Carbon::today()->diffInDays($record->due_date, false);
                        if ($diff < 0) {
                            return 'Terlambat '.abs((int) $diff).' hari';
                        }

                        return 'Sisa '.$diff.' hari lagi';
                    })
                    ->badge()
                    ->color(function (Circulation $record): string {
                        $diff = Carbon::today()->diffInDays($record->due_date, false);

                        return $diff < 0 ? 'danger' : ($diff <= 2 ? 'warning' : 'success');
                    }),

                TextColumn::make('fine_status')
                    ->label('Estimasi Denda')
                    ->state(function (Circulation $record): string {
                        $diff = Carbon::today()->diffInDays($record->due_date, false);
                        if ($diff < 0) {
                            $fine = abs((int) $diff) * 1000;

                            return 'Rp '.number_format($fine, 0, ',', '.');
                        }

                        return 'Rp 0';
                    })
                    ->badge()
                    ->color(function (Circulation $record): string {
                        $diff = Carbon::today()->diffInDays($record->due_date, false);

                        return $diff < 0 ? 'danger' : 'gray';
                    }),
            ])
            ->paginated([5]);
    }
}
