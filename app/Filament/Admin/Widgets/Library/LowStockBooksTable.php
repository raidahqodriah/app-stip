<?php

namespace App\Filament\Admin\Widgets\Library;

use App\Models\Core\Employee;
use App\Models\Library\Book;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockBooksTable extends BaseWidget
{
    protected static ?int $sort = 20;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.low_stock_books');
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
                Book::query()
                    ->where('available_stock', '<=', 1)
                    ->orderBy('available_stock')
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Buku')
                    ->description(fn (Book $record): ?string => $record->book_code)
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                TextColumn::make('shelf')
                    ->label('Lokasi Rak')
                    ->icon('heroicon-m-archive-box'),

                TextColumn::make('available_stock')
                    ->label('Stok Tersedia')
                    ->suffix(' Eks')
                    ->badge()
                    ->color(fn (Book $record): string => $record->available_stock === 0 ? 'danger' : 'warning'),

                TextColumn::make('total_stock')
                    ->label('Total Koleksi')
                    ->suffix(' Eks')
                    ->color('gray'),
            ])
            ->paginated([5]);
    }
}
