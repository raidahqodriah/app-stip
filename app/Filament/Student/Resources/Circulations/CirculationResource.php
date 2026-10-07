<?php

namespace App\Filament\Student\Resources\Circulations;

use App\Enums\CirculationStatus;
use App\Filament\Student\Resources\Circulations\Pages\ListCirculations;
use App\Models\Core\Student;
use App\Models\Library\Circulation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CirculationResource extends Resource
{
    protected static ?string $model = Circulation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'transaction_code';

    public static function getModelLabel(): string
    {
        return __('filament.resources.student_circulations.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.student_circulations.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.student_circulations.navigation_label');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('borrower_type', Student::class)->where('borrower_id', auth()->id()))
            ->recordTitleAttribute('transaction_code')
            ->defaultSort('loan_date', 'desc')
            ->columns([
                TextColumn::make('transaction_code')
                    ->label('Kode Transaksi')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->searchable()
                    ->limit(35),
                TextColumn::make('loan_date')
                    ->label('Tanggal Pinjam')
                    ->date()
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date()
                    ->sortable()
                    ->color(fn ($record) => $record->status === CirculationStatus::Borrowed && $record->due_date->isPast() ? 'danger' : null),
                TextColumn::make('return_date')
                    ->label('Tgl Kembali')
                    ->date()
                    ->placeholder('(Sedang Dipinjam)'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (CirculationStatus $state): string => match ($state) {
                        CirculationStatus::Borrowed => 'warning',
                        CirculationStatus::Returned => 'success',
                    }),
                TextColumn::make('fine_amount')
                    ->label('Denda (Rp)')
                    ->numeric()
                    ->formatStateUsing(fn ($state) => $state > 0 ? 'Rp '.number_format($state, 0, ',', '.') : '-'),
                IconColumn::make('fine_paid')
                    ->label('Lunas')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(CirculationStatus::class),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCirculations::route('/'),
        ];
    }
}
