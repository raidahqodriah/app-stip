<?php

namespace App\Filament\Admin\Resources\Competences;

use App\Filament\Admin\Clusters\Lab\LabCluster;
use App\Filament\Admin\Resources\Competences\Pages\CreateCompetence;
use App\Filament\Admin\Resources\Competences\Pages\EditCompetence;
use App\Filament\Admin\Resources\Competences\Pages\ListCompetences;
use App\Models\Lab\Competence;
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

class CompetenceResource extends Resource
{
    protected static ?string $model = Competence::class;

    protected static ?string $cluster = LabCluster::class;

    protected static ?string $navigationLabel = 'Kompetensi IMO / STCW';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckBadge;

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('imo_model_course_id')
                    ->relationship('imoModelCourse', 'title')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                TextInput::make('stcw_reference'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('code')
            ->columns([
                TextColumn::make('imoModelCourse.title')
                    ->searchable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('stcw_reference')
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
            'index' => ListCompetences::route('/'),
            'create' => CreateCompetence::route('/create'),
            'edit' => EditCompetence::route('/{record}/edit'),
        ];
    }
}
