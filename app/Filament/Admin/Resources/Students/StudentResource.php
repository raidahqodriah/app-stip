<?php

namespace App\Filament\Admin\Resources\Students;

use App\Filament\Admin\Clusters\Master\MasterCluster;
use App\Filament\Admin\Resources\Students\Pages\CreateStudent;
use App\Filament\Admin\Resources\Students\Pages\EditStudent;
use App\Filament\Admin\Resources\Students\Pages\ListStudents;
use App\Models\Core\Student;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $cluster = MasterCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('filament.resources.students.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.students.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.students.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('unit_id')
                    ->label('Program Studi')
                    ->relationship('unit', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                TextInput::make('student_number')
                    ->label('NIT / NRP / NIM')
                    ->required(),
                TextInput::make('batch_year')
                    ->label('Tahun Angkatan')
                    ->numeric()
                    ->default(date('Y')),
                TextInput::make('class_group')
                    ->label('Kelas / Peleton')
                    ->placeholder('Mis. T-IV-A / N-II-B'),
                TextInput::make('email')
                    ->label('Email Taruna')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn ($livewire) => $livewire instanceof CreateStudent),
                TextInput::make('phone')
                    ->label('No. Telepon / WhatsApp')
                    ->tel(),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->defaultSort('student_number', 'asc')
            ->columns([
                TextColumn::make('student_number')
                    ->label('NIT')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Taruna')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit.name')
                    ->label('Prodi')
                    ->searchable(),
                TextColumn::make('batch_year')
                    ->label('Angkatan')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('class_group')
                    ->label('Kelas')
                    ->alignCenter()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('unit_id')
                    ->label('Prodi')
                    ->relationship('unit', 'name'),
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

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }
}
