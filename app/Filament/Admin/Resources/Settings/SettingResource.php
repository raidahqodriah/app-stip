<?php

namespace App\Filament\Admin\Resources\Settings;

use App\Filament\Admin\Clusters\Master\MasterCluster;
use App\Filament\Admin\Resources\Settings\Pages\CreateSetting;
use App\Filament\Admin\Resources\Settings\Pages\EditSetting;
use App\Filament\Admin\Resources\Settings\Pages\ListSettings;
use App\Models\Core\Setting;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $cluster = MasterCluster::class;

    protected static ?string $navigationLabel = 'Pengaturan Sistem';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required(),
                Textarea::make('value')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('key')
            ->columns([
                TextColumn::make('key')
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
            'index' => ListSettings::route('/'),
            'create' => CreateSetting::route('/create'),
            'edit' => EditSetting::route('/{record}/edit'),
        ];
    }
}
