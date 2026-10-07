<?php

namespace App\Filament\Admin\Resources\BlackoutDates;

use App\Filament\Admin\Clusters\Lab\LabCluster;
use App\Filament\Admin\Resources\BlackoutDates\Pages\CreateBlackoutDate;
use App\Filament\Admin\Resources\BlackoutDates\Pages\EditBlackoutDate;
use App\Filament\Admin\Resources\BlackoutDates\Pages\ListBlackoutDates;
use App\Models\Lab\BlackoutDate;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlackoutDateResource extends Resource
{
    protected static ?string $model = BlackoutDate::class;

    protected static ?string $cluster = LabCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDateRange;

    protected static ?int $navigationSort = 7;

    public static function getModelLabel(): string
    {
        return __('filament.resources.blackout_dates.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.blackout_dates.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.blackout_dates.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('room_id')
                    ->relationship('room', 'name'),
                DateTimePicker::make('start_at')
                    ->required(),
                DateTimePicker::make('end_at')
                    ->required(),
                TextInput::make('reason')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reason')
            ->columns([
                TextColumn::make('room.name')
                    ->searchable(),
                TextColumn::make('start_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reason')
                    ->searchable(),
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
            'index' => ListBlackoutDates::route('/'),
            'create' => CreateBlackoutDate::route('/create'),
            'edit' => EditBlackoutDate::route('/{record}/edit'),
        ];
    }
}
