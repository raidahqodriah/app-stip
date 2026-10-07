<?php

namespace App\Filament\Admin\Widgets\Library;

use App\Models\Core\Employee;
use App\Models\Library\Circulation;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TodayLibraryCirculationsTable extends BaseWidget
{
    protected static ?int $sort = 19;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.today_library_circulations');
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
                Circulation::query()
                    ->with(['book', 'borrower', 'loanOfficer'])
                    ->where(function ($q) {
                        $q->whereDate('loan_date', Carbon::today())
                            ->orWhereDate('return_date', Carbon::today());
                    })
                    ->latest('updated_at')
            )
            ->columns([
                TextColumn::make('transaction_code')
                    ->label('Kode Transaksi')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Aktivitas')
                    ->state(function (Circulation $record): string {
                        if ($record->return_date && $record->return_date->isToday()) {
                            return 'Pengembalian Buku';
                        }

                        return 'Peminjaman Buku';
                    })
                    ->badge()
                    ->color(fn (string $state): string => str_contains($state, 'Pengembalian') ? 'success' : 'info'),

                TextColumn::make('borrower.name')
                    ->label('Nama Anggota / Peminjam')
                    ->icon('heroicon-m-user')
                    ->searchable(),

                TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->searchable(),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->icon('heroicon-m-calendar'),

                TextColumn::make('loanOfficer.name')
                    ->label('Petugas Loket')
                    ->icon('heroicon-m-user-circle'),
            ])
            ->paginated([5]);
    }
}
