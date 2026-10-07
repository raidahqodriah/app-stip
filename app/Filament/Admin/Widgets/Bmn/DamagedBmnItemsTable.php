<?php

namespace App\Filament\Admin\Widgets\Bmn;

use App\Enums\ItemCondition;
use App\Models\Bmn\BmnItem;
use App\Models\Core\Employee;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class DamagedBmnItemsTable extends BaseWidget
{
    protected static ?int $sort = 10;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.damaged_bmn_items');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer', 'unit_admin']) ?? false;
    }

    public function table(Table $table): Table
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        $query = BmnItem::query()
            ->with(['unit', 'room'])
            ->whereIn('condition', [ItemCondition::MinorDamage, ItemCondition::MajorDamage])
            ->latest('updated_at');

        if ($user && $user->hasRole('unit_admin') && ! $user->hasAnyRole(['admin', 'leader', 'officer'])) {
            $query->where('unit_id', $user->unit_id);
        }

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('bmn_code')
                    ->label('Kode BMN')
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('item_name')
                    ->label('Nama Barang')
                    ->description(fn (BmnItem $record): ?string => $record->brand.' '.$record->model)
                    ->searchable(),

                TextColumn::make('unit.name')
                    ->label('Unit & Lokasi Ruang')
                    ->description(fn (BmnItem $record): ?string => $record->room?->name),

                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->suffix(' Unit')
                    ->alignCenter(),

                TextColumn::make('condition')
                    ->label('Kondisi Kerusakan')
                    ->badge(),
            ])
            ->paginated([5]);
    }
}
