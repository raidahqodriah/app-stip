<?php

namespace App\Filament\Admin\Resources\OfficialResidences;

use App\Filament\Admin\Clusters\Bmn\BmnCluster;
use App\Filament\Admin\Resources\OfficialResidences\Pages\CreateOfficialResidence;
use App\Filament\Admin\Resources\OfficialResidences\Pages\EditOfficialResidence;
use App\Filament\Admin\Resources\OfficialResidences\Pages\ListOfficialResidences;
use App\Models\Bmn\OfficialResidence;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OfficialResidenceResource extends Resource
{
    protected static ?string $model = OfficialResidence::class;

    protected static ?string $cluster = BmnCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static ?int $navigationSort = 4;

    public static function getModelLabel(): string
    {
        return __('filament.resources.official_residences.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.official_residences.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.official_residences.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('unit_id')
                    ->relationship('unit', 'name'),
                TextInput::make('house_number')
                    ->required(),
                TextInput::make('address')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('house_number')
            ->columns([
                TextColumn::make('unit.name')
                    ->searchable(),
                TextColumn::make('house_number')
                    ->searchable(),
                TextColumn::make('address')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
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
            'index' => ListOfficialResidences::route('/'),
            'create' => CreateOfficialResidence::route('/create'),
            'edit' => EditOfficialResidence::route('/{record}/edit'),
        ];
    }
}
