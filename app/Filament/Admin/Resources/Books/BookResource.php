<?php

namespace App\Filament\Admin\Resources\Books;

use App\Filament\Admin\Clusters\Library\LibraryCluster;
use App\Filament\Admin\Resources\Books\Pages\CreateBook;
use App\Filament\Admin\Resources\Books\Pages\EditBook;
use App\Filament\Admin\Resources\Books\Pages\ListBooks;
use App\Models\Library\Book;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $cluster = LibraryCluster::class;

    protected static ?string $navigationLabel = 'Katalog Buku Perpustakaan';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('book_code')
                    ->required(),
                TextInput::make('isbn'),
                TextInput::make('title')
                    ->required(),
                TextInput::make('author')
                    ->required(),
                TextInput::make('publisher'),
                TextInput::make('year')
                    ->numeric(),
                TextInput::make('category')
                    ->required(),
                TextInput::make('shelf'),
                TextInput::make('total_stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('available_stock')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('book_code')
                    ->searchable(),
                TextColumn::make('isbn')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('author')
                    ->searchable(),
                TextColumn::make('publisher')
                    ->searchable(),
                TextColumn::make('year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('category')
                    ->searchable(),
                TextColumn::make('shelf')
                    ->searchable(),
                TextColumn::make('total_stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('available_stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBooks::route('/'),
            'create' => CreateBook::route('/create'),
            'edit' => EditBook::route('/{record}/edit'),
        ];
    }
}
