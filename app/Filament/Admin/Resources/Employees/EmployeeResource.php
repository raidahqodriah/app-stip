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

    protected static ?string $navigationLabel = 'Data Pegawai & Dosen';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 2;

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
