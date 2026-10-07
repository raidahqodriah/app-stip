<?php

namespace App\Filament\Admin\Resources\Permissions;

use App\Filament\Admin\Clusters\Master\MasterCluster;
use App\Filament\Admin\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Admin\Resources\Permissions\Pages\ViewPermission;
use App\Filament\Admin\Resources\Roles\RoleResource;
use BackedEnum;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $cluster = MasterCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedKey;

    protected static ?int $navigationSort = 8;

    public static function getModelLabel(): string
    {
        return __('filament.resources.permissions.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.permissions.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.permissions.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Izin Sistem')
                    ->schema([
                        TextInput::make('name')
                            ->label('Kode Izin (Permission Key)')
                            ->readOnly(),
                        TextInput::make('guard_name')
                            ->label('Guard Otentikasi')
                            ->default('employee')
                            ->readOnly(),
                        TextInput::make('description')
                            ->label('Keterangan Fungsional')
                            ->formatStateUsing(fn (?Permission $record) => $record ? (RoleResource::PERMISSION_DESCRIPTIONS[$record->name] ?? '-') : null)
                            ->readOnly(),
                        Select::make('roles')
                            ->label('Peran yang Memiliki Izin Ini')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('name', 'asc')
            ->columns([
                TextColumn::make('name')
                    ->label('Kode Izin')
                    ->fontFamily('mono')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('module')
                    ->label('Modul')
                    ->state(function (Permission $record): string {
                        $prefix = explode('.', $record->name)[0] ?? 'core';

                        return match ($prefix) {
                            'core' => 'Core & Admin',
                            'lab' => 'Lab & Simulator SPP',
                            'bmn' => 'Pengelolaan BMN',
                            'residence' => 'Rumah Dinas',
                            'library' => 'Perpustakaan',
                            default => strtoupper($prefix),
                        };
                    })
                    ->badge()
                    ->color(function (Permission $record): string {
                        $prefix = explode('.', $record->name)[0] ?? 'core';

                        return match ($prefix) {
                            'core' => 'gray',
                            'lab' => 'warning',
                            'bmn' => 'info',
                            'residence' => 'teal',
                            'library' => 'success',
                            default => 'gray',
                        };
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('name', $direction);
                    }),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->state(fn (Permission $record): string => RoleResource::PERMISSION_DESCRIPTIONS[$record->name] ?? '-')
                    ->wrap()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        // Search by description or key
                        $matchedKeys = array_keys(array_filter(
                            RoleResource::PERMISSION_DESCRIPTIONS,
                            fn (string $desc) => stripos($desc, $search) !== false
                        ));

                        return $query->whereIn('name', $matchedKeys)->orWhere('name', 'like', "%{$search}%");
                    }),

                TextColumn::make('roles.name')
                    ->label('Peran Terkait')
                    ->badge()
                    ->color('primary')
                    ->separator(', '),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('module')
                    ->label('Kategori Modul')
                    ->options([
                        'core' => 'Core & Administrasi',
                        'lab' => 'Layanan Lab & Simulator SPP',
                        'bmn' => 'Pengelolaan BMN',
                        'residence' => 'Rumah Dinas',
                        'library' => 'Perpustakaan',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (! empty($data['value'])) {
                            return $query->where('name', 'like', $data['value'].'.%');
                        }

                        return $query;
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
                // Read-only catalog, no bulk delete
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
            'index' => ListPermissions::route('/'),
            'view' => ViewPermission::route('/{record}'),
        ];
    }
}
