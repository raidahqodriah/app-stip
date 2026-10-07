<?php

namespace App\Filament\Student\Resources\Books;

use App\Filament\Student\Resources\Books\Pages\ListBooks;
use App\Models\Library\Book;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationLabel = 'Katalog Perpustakaan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'title';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->defaultSort('title', 'asc')
            ->columns([
                TextColumn::make('book_code')
                    ->label('Kode Buku')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Judul Koleksi')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('author')
                    ->label('Penulis / Pengarang')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->searchable(),
                TextColumn::make('shelf')
                    ->label('Lokasi Rak')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                TextColumn::make('available_stock')
                    ->label('Stok Tersedia')
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->formatStateUsing(fn ($state) => $state > 0 ? "{$state} Eks" : 'Habis'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Nautika' => 'Nautika',
                        'Teknika' => 'Teknika',
                        'KALK' => 'KALK',
                        'Umum' => 'Umum',
                        'Hukum Maritim' => 'Hukum Maritim',
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBooks::route('/'),
        ];
    }
}
