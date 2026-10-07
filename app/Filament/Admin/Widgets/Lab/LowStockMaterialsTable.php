<?php

namespace App\Filament\Admin\Widgets\Lab;

use App\Models\Core\Employee;
use App\Models\Lab\Material;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockMaterialsTable extends BaseWidget
{
    protected static ?int $sort = 7;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.low_stock_materials');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer']) ?? false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Material::query()
                    ->with('room')
                    ->whereColumn('stock_qty', '<=', 'min_stock')
                    ->orderBy('stock_qty')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Bahan / Peralatan')
                    ->description(fn (Material $record): ?string => $record->room?->name)
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('stock_qty')
                    ->label('Sisa Stok')
                    ->suffix(fn (Material $record): string => ' '.$record->unit)
                    ->badge()
                    ->color(fn (Material $record): string => $record->stock_qty <= 0 ? 'danger' : 'warning'),

                TextColumn::make('min_stock')
                    ->label('Ambang Batas')
                    ->suffix(fn (Material $record): string => ' '.$record->unit)
                    ->color('gray'),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color('info'),
            ])
            ->paginated([5]);
    }
}
