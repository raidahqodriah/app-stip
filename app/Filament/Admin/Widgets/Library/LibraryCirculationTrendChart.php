<?php

namespace App\Filament\Admin\Widgets\Library;

use App\Models\Core\Employee;
use App\Models\Library\Circulation;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class LibraryCirculationTrendChart extends ChartWidget
{
    protected static ?int $sort = 21;

    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    public ?string $filter = '3_months';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.library_circulation_trend');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer']) ?? false;
    }

    protected function getFilters(): ?array
    {
        return [
            'this_month' => __('filament.widgets.filters.this_month'),
            '3_months' => __('filament.widgets.filters.3_months'),
            'this_year' => __('filament.widgets.filters.this_year'),
        ];
    }

    protected function getData(): array
    {
        $monthsCount = match ($this->filter) {
            'this_month' => 1,
            'this_year' => 12,
            default => 3,
        };

        $labels = [];
        $borrowedData = [];
        $returnedData = [];

        $now = Carbon::now();

        for ($i = $monthsCount - 1; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $labels[] = $month->translatedFormat('F Y');

            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $borrowedCount = Circulation::whereBetween('loan_date', [$start, $end])->count();
            $returnedCount = Circulation::whereBetween('return_date', [$start, $end])->count();

            $borrowedData[] = $borrowedCount;
            $returnedData[] = $returnedCount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Buku Dipinjam',
                    'data' => $borrowedData,
                    'borderColor' => '#0284c7',
                    'backgroundColor' => 'rgba(2, 132, 199, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Buku Dikembalikan',
                    'data' => $returnedData,
                    'borderColor' => '#059669',
                    'backgroundColor' => 'rgba(5, 150, 105, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
