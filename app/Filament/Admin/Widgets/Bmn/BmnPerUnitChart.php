<?php

namespace App\Filament\Admin\Widgets\Bmn;

use App\Models\Bmn\BmnItem;
use App\Models\Core\Employee;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BmnPerUnitChart extends ChartWidget
{
    protected static ?int $sort = 13;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.bmn_per_unit');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer']) ?? false;
    }

    protected function getData(): array
    {
        $unitsData = BmnItem::query()
            ->join('units', 'bmn_items.unit_id', '=', 'units.id')
            ->select('units.name', DB::raw('SUM(bmn_items.quantity) as total_qty'))
            ->groupBy('units.id', 'units.name')
            ->orderByDesc('total_qty')
            ->limit(6)
            ->get();

        $labels = [];
        $values = [];

        foreach ($unitsData as $row) {
            $shortName = strlen($row->name) > 20 ? substr($row->name, 0, 18).'...' : $row->name;
            $labels[] = $shortName;
            $values[] = (int) $row->total_qty;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Unit Barang',
                    'data' => $values,
                    'backgroundColor' => '#0284c7',
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
