<?php

namespace App\Filament\Admin\Resources\ImoModelCourses;

use App\Filament\Admin\Clusters\Lab\LabCluster;
use App\Filament\Admin\Resources\ImoModelCourses\Pages\CreateImoModelCourse;
use App\Filament\Admin\Resources\ImoModelCourses\Pages\EditImoModelCourse;
use App\Filament\Admin\Resources\ImoModelCourses\Pages\ListImoModelCourses;
use App\Models\Lab\ImoModelCourse;
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

class ImoModelCourseResource extends Resource
{
    protected static ?string $model = ImoModelCourse::class;

    protected static ?string $cluster = LabCluster::class;

    protected static ?string $navigationLabel = 'IMO Model Courses';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAmericas;

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('edition_year')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('edition_year')
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
            'index' => ListImoModelCourses::route('/'),
            'create' => CreateImoModelCourse::route('/create'),
            'edit' => EditImoModelCourse::route('/{record}/edit'),
        ];
    }
}
