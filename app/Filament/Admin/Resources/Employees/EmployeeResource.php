<?php

namespace App\Filament\Admin\Resources\Employees;

use App\Filament\Admin\Clusters\Master\MasterCluster;
use App\Filament\Admin\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Admin\Resources\Employees\Pages\EditEmployee;
use App\Filament\Admin\Resources\Employees\Pages\ListEmployees;
use App\Models\Core\Employee;
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

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $cluster = MasterCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return __('filament.resources.employees.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.employees.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.employees.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('unit_id')
                    ->label('Unit Kerja / Homebase')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                TextInput::make('employee_number')
                    ->label('NIP / NIK')
                    ->required(),
                TextInput::make('position')
                    ->label('Jabatan / Peran')
                    ->placeholder('Mis. Dosen Teknika / Petugas SPP / Ka. Unit'),
                TextInput::make('email')
                    ->label('Email')
                    ->email(),
                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn ($livewire) => $livewire instanceof CreateEmployee),
                TextInput::make('phone')
                    ->label('No. Telepon / WhatsApp')
                    ->tel(),
                Select::make('roles')
                    ->label('Peran Akun (Roles)')
                    ->relationship('roles', 'name', fn ($query) => $query->where('guard_name', 'employee'))
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Select::make('permissions')
                    ->label('Izin Khusus / Langsung (Direct Permissions)')
                    ->helperText('Gunakan untuk partisi tugas petugas operasional atau wewenang persetujuan pejabat (Plh).')
                    ->relationship('permissions', 'name', fn ($query) => $query->where('guard_name', 'employee'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Toggle::make('is_active')
                    ->label('Status Akun Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('unit.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->label('Peran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'leader' => 'info',
                        'officer' => 'primary',
                        'unit_admin' => 'warning',
                        'teacher' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('employee_number')
                    ->searchable(),
                TextColumn::make('position')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('theme_color')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
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
            'index' => ListEmployees::route('/'),
            'create' => CreateEmployee::route('/create'),
            'edit' => EditEmployee::route('/{record}/edit'),
        ];
    }
}
