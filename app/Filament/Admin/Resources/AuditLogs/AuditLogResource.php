<?php

namespace App\Filament\Admin\Resources\AuditLogs;

use App\Filament\Admin\Clusters\Master\MasterCluster;
use App\Filament\Admin\Resources\AuditLogs\Pages\CreateAuditLog;
use App\Filament\Admin\Resources\AuditLogs\Pages\EditAuditLog;
use App\Filament\Admin\Resources\AuditLogs\Pages\ListAuditLogs;
use App\Models\Core\AuditLog;
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

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $cluster = MasterCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    protected static ?int $navigationSort = 6;

    public static function getModelLabel(): string
    {
        return __('filament.resources.audit_logs.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.audit_logs.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.audit_logs.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('actor_type'),
                TextInput::make('actor_id')
                    ->numeric(),
                TextInput::make('auditable_type')
                    ->required(),
                TextInput::make('auditable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('event')
                    ->required(),
                TextInput::make('old_values'),
                TextInput::make('new_values'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('event')
            ->columns([
                TextColumn::make('actor_type')
                    ->searchable(),
                TextColumn::make('actor_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('auditable_type')
                    ->searchable(),
                TextColumn::make('auditable_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('event')
                    ->searchable(),
                TextColumn::make('created_at')
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
            'index' => ListAuditLogs::route('/'),
            'create' => CreateAuditLog::route('/create'),
            'edit' => EditAuditLog::route('/{record}/edit'),
        ];
    }
}
