<?php

namespace App\Filament\Admin\Widgets\Bmn;

use App\Models\Bmn\BmnItem;
use App\Models\Core\Employee;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BmnPendingDecreeDocumentsTable extends BaseWidget
{
    protected static ?int $sort = 11;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.bmn_pending_decree_documents');
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
            ->with(['unit'])
            ->where('requires_decree', true)
            ->latest('acquisition_date');

        if ($user && $user->hasRole('unit_admin') && ! $user->hasAnyRole(['admin', 'leader', 'officer'])) {
            $query->where('unit_id', $user->unit_id);
        }

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('item_name')
                    ->label('Nama Aset BMN')
                    ->description(fn (BmnItem $record): ?string => $record->bmn_code)
                    ->weight('bold')
                    ->searchable(),

                TextColumn::make('unit.name')
                    ->label('Unit Kerja')
                    ->limit(20),

                TextColumn::make('responsible_name')
                    ->label('Penanggung Jawab')
                    ->icon('heroicon-m-user'),

                IconColumn::make('requires_decree')
                    ->label('Perlu SK')
                    ->boolean(),
            ])
            ->paginated([5]);
    }
}
