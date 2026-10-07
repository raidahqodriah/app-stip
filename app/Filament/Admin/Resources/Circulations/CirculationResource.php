<?php

namespace App\Filament\Admin\Resources\Circulations;

use App\Enums\CirculationStatus;
use App\Enums\ItemCondition;
use App\Filament\Admin\Clusters\Library\LibraryCluster;
use App\Filament\Admin\Resources\Circulations\Pages\CreateCirculation;
use App\Filament\Admin\Resources\Circulations\Pages\EditCirculation;
use App\Filament\Admin\Resources\Circulations\Pages\ListCirculations;
use App\Models\Core\Student;
use App\Models\Library\Book;
use App\Models\Library\Circulation;
use BackedEnum;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CirculationResource extends Resource
{
    protected static ?string $model = Circulation::class;

    protected static ?string $cluster = LibraryCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('filament.resources.circulations.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.circulations.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.circulations.navigation_label');
    }

    protected static ?string $recordTitleAttribute = 'transaction_code';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('transaction_code')
                    ->label('Kode Transaksi')
                    ->default(fn () => 'TRX-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -4)))
                    ->readOnly()
                    ->required(),
                Select::make('borrower_id')
                    ->label('Peminjam (Taruna / Anggota)')
                    ->options(Student::where('is_active', true)->pluck('name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('book_id')
                    ->label('Buku yang Dipinjam')
                    ->options(Book::where('available_stock', '>', 0)->pluck('title', 'id'))
                    ->searchable()
                    ->required(),
                DatePicker::make('loan_date')
                    ->label('Tanggal Pinjam')
                    ->default(now())
                    ->required(),
                DatePicker::make('due_date')
                    ->label('Jatuh Tempo (+7 Hari)')
                    ->default(now()->addDays(7))
                    ->required(),
                Select::make('status')
                    ->label('Status Sirkulasi')
                    ->options(CirculationStatus::class)
                    ->default(CirculationStatus::Borrowed)
                    ->required(),
                Textarea::make('officer_note')
                    ->label('Catatan Petugas Loket')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('transaction_code')
            ->defaultSort('loan_date', 'desc')
            ->columns([
                TextColumn::make('transaction_code')
                    ->label('Kode Transaksi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('borrower.name')
                    ->label('Peminjam')
                    ->searchable(),
                TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('loan_date')
                    ->label('Tgl Pinjam')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date()
                    ->sortable(),
                TextColumn::make('return_date')
                    ->label('Tgl Kembali')
                    ->date()
                    ->placeholder('(Belum Kembali)'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (CirculationStatus $state): string => match ($state) {
                        CirculationStatus::Borrowed => 'warning',
                        CirculationStatus::Returned => 'success',
                    }),
                TextColumn::make('fine_amount')
                    ->label('Denda (Rp)')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => $state > 0 ? 'Rp '.number_format($state, 0, ',', '.') : '-'),
                IconColumn::make('fine_paid')
                    ->label('Denda Lunas')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(CirculationStatus::class),
            ])
            ->recordActions([
                // Proses Pengembalian Buku di Loket
                Action::make('return_book')
                    ->label('Proses Kembali')
                    ->icon('heroicon-o-arrow-left-on-rectangle')
                    ->color('success')
                    ->visible(fn (Circulation $record) => $record->status === CirculationStatus::Borrowed)
                    ->modalHeading('Pemeriksaan & Pengembalian Buku')
                    ->form([
                        Select::make('return_condition')
                            ->label('Kondisi Buku Saat Dikembalikan')
                            ->options(ItemCondition::class)
                            ->default(ItemCondition::Good)
                            ->required(),
                        Toggle::make('fine_paid')
                            ->label('Denda Telah Dibayar / Bebas Denda')
                            ->default(true),
                        Textarea::make('officer_note')
                            ->label('Catatan Petugas (Jika ada kerusakan/keterlambatan)'),
                    ])
                    ->action(function (Circulation $record, array $data) {
                        $now = Carbon::now();
                        $dueDate = Carbon::parse($record->due_date);
                        $lateDays = $now->greaterThan($dueDate) ? (int) $dueDate->diffInDays($now) : 0;
                        $fineAmount = $lateDays * 1000; // Rp 1.000 / hari keterlambatan (§6.3)

                        $record->update([
                            'status' => CirculationStatus::Returned,
                            'return_date' => $now->toDateString(),
                            'returned_by' => auth()->id() ?? 1,
                            'return_condition' => $data['return_condition'],
                            'late_days' => $lateDays,
                            'fine_amount' => $fineAmount,
                            'fine_paid' => $data['fine_paid'],
                            'fine_paid_at' => $data['fine_paid'] ? $now : null,
                            'officer_note' => $data['officer_note'] ?? null,
                        ]);

                        // Perbarui stok buku
                        $book = $record->book;
                        if ($book) {
                            if ($data['return_condition'] === ItemCondition::Lost->value) {
                                $book->decrement('total_stock');
                            } else {
                                $book->increment('available_stock');
                            }
                        }

                        Notification::make()
                            ->title('Buku berhasil dikembalikan. '.($lateDays > 0 ? "Terlambat {$lateDays} hari (Denda Rp ".number_format($fineAmount, 0, ',', '.').').' : 'Tepat waktu.'))
                            ->success()
                            ->send();
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
            'index' => ListCirculations::route('/'),
            'create' => CreateCirculation::route('/create'),
            'edit' => EditCirculation::route('/{record}/edit'),
        ];
    }
}
