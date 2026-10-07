<?php

namespace App\Filament\Admin\Widgets\Bmn;

use App\Enums\ItemCondition;
use App\Models\Bmn\BmnItem;
use App\Models\Core\Employee;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BmnConditionDistributionChart extends ChartWidget
{
    protected static ?int $sort = 12;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.bmn_condition_distribution');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer', 'unit_admin']) ?? false;
    }

    protected function getData(): array
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        $query = BmnItem::query();
        if ($user && $user->hasRole('unit_admin') && ! $user->hasAnyRole(['admin', 'leader', 'officer'])) {
            $query->where('unit_id', $user->unit_id);
        }

        $conditions = $query->select('condition', DB::raw('count(*) as total'))
            ->groupBy('condition')
            ->pluck('total', 'condition')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Aset',
                    'data' => [
                        $conditions[ItemCondition::Good->value] ?? 0,
                        $conditions[ItemCondition::MinorDamage->value] ?? 0,
                        $conditions[ItemCondition::MajorDamage->value] ?? 0,
                        $conditions[ItemCondition::Lost->value] ?? 0,
                    ],
                    'backgroundColor' => [
                        '#059669', // Baik (Emerald)
                        '#f59e0b', // Rusak Ringan (Amber)
                        '#dc2626', // Rusak Berat (Red)
                        '#6b7280', // Hilang (Gray)
                    ],
                ],
            ],
            'labels' => [
                ItemCondition::Good->getLabel(),
                ItemCondition::MinorDamage->getLabel(),
                ItemCondition::MajorDamage->getLabel(),
                ItemCondition::Lost->getLabel(),
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
