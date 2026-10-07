<?php

namespace App\Filament\Admin\Widgets\Library;

use App\Enums\CirculationStatus;
use App\Enums\ItemCondition;
use App\Models\Core\Employee;
use App\Models\Library\Circulation;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OverdueCirculationsTable extends BaseWidget
{
    protected static ?int $sort = 18;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.overdue_circulations');
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
                    ->with(['book', 'borrower'])
                    ->where('status', CirculationStatus::Borrowed)
                    ->whereDate('due_date', '<', Carbon::today())
                    ->orderBy('due_date')
            )
            ->columns([
                TextColumn::make('transaction_code')
                    ->label('Kode Transaksi')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('borrower.name')
                    ->label('Nama Peminjam')
                    ->description(function (Circulation $record): ?string {
                        return $record->borrower?->student_number ?? $record->borrower?->employee_number;
                    })
                    ->icon('heroicon-m-user')
                    ->searchable(),

                TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->description(fn (Circulation $record): ?string => $record->book?->book_code)
                    ->searchable(),

                TextColumn::make('due_date')
                    ->label('Batas Jatuh Tempo')
                    ->date('d M Y')
                    ->icon('heroicon-m-calendar-days'),

                TextColumn::make('overdue_days')
                    ->label('Keterlambatan')
                    ->state(fn (Circulation $record): string => Carbon::today()->diffInDays($record->due_date).' Hari')
                    ->badge()
                    ->color('danger'),

                TextColumn::make('fine_display')
                    ->label('Akumulasi Denda')
                    ->state(function (Circulation $record): string {
                        $days = Carbon::today()->diffInDays($record->due_date);
                        $amount = max(0, $days * 1000);

                        return 'Rp '.number_format($amount, 0, ',', '.');
                    })
                    ->weight('bold')
                    ->color('danger'),
            ])
            ->actions([
                Action::make('process_return')
                    ->label('Proses Pengembalian')
                    ->icon('heroicon-m-arrow-path-rounded-square')
                    ->color('success')
                    ->visible(fn (): bool => auth()->user()?->hasAnyRole(['admin', 'officer']) ?? false)
                    ->form([
                        Select::make('return_condition')
                            ->label('Kondisi Fisik Buku Saat Kembali')
                            ->options(ItemCondition::class)
                            ->default(ItemCondition::Good->value)
                            ->required(),

                        Toggle::make('fine_paid')
                            ->label('Denda Telah Dilunasi di Loket')
                            ->default(true),
                    ])
                    ->action(function (Circulation $record, array $data): void {
                        $days = Carbon::today()->diffInDays($record->due_date);
                        $amount = max(0, $days * 1000);

                        $record->update([
                            'status' => CirculationStatus::Returned,
                            'return_date' => Carbon::today(),
                            'returned_by' => auth()->id() ?? 1,
                            'return_condition' => $data['return_condition'],
                            'late_days' => $days,
                            'fine_amount' => $amount,
                            'fine_paid' => $data['fine_paid'],
                            'fine_paid_at' => $data['fine_paid'] ? Carbon::now() : null,
                        ]);

                        // Kembalikan stok buku
                        $record->book?->increment('available_stock');

                        Notification::make()
                            ->title('Buku Berhasil Dikembalikan')
                            ->body('Transaksi sirkulasi telah diselesaikan dan stok buku telah diperbarui.')
                            ->success()
                            ->send();
                    }),
            ])
            ->paginated([5]);
    }
}
