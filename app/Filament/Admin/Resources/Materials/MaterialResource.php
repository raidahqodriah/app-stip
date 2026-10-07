<?php

namespace App\Filament\Admin\Resources\Materials;

use App\Enums\MaterialType;
use App\Filament\Admin\Clusters\Lab\LabCluster;
use App\Filament\Admin\Resources\Materials\Pages\CreateMaterial;
use App\Filament\Admin\Resources\Materials\Pages\EditMaterial;
use App\Filament\Admin\Resources\Materials\Pages\ListMaterials;
use App\Models\Lab\Material;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $cluster = LabCluster::class;

    protected static ?string $navigationLabel = 'Bahan & Alat Praktik';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('room_id')
                    ->relationship('room', 'name')
                    ->required(),
                Select::make('bmn_item_id')
                    ->relationship('bmnItem', 'id'),
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options(MaterialType::class)
                    ->required(),
                TextInput::make('unit')
                    ->required(),
                TextInput::make('stock_qty')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('min_stock')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('room.name')
                    ->searchable(),
                TextColumn::make('bmnItem.id')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->searchable(),
                TextColumn::make('unit')
                    ->searchable(),
                TextColumn::make('stock_qty')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('min_stock')
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
            'index' => ListMaterials::route('/'),
            'create' => CreateMaterial::route('/create'),
            'edit' => EditMaterial::route('/{record}/edit'),
        ];
    }
}
