<?php

namespace App\Filament\Admin\Widgets\Lab;

use App\Enums\RequestStatus;
use App\Models\Core\Employee;
use App\Models\Lab\Booking;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class LabUtilizationTrendChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = [
        'md' => 2,
        'xl' => 2,
    ];

    public ?string $filter = '3_months';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.lab_utilization_trend');
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
        $plannedHours = [];
        $completedHours = [];

        $now = Carbon::now();

        for ($i = $monthsCount - 1; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $monthLabel = $month->translatedFormat('F Y');
            $labels[] = $monthLabel;

            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            // Total jam rencana (approved, in_use, completed)
            $plannedSeconds = Booking::whereBetween('start_at', [$startOfMonth, $endOfMonth])
                ->whereIn('status', [RequestStatus::Approved, RequestStatus::InUse, RequestStatus::Completed])
                ->select(DB::raw('SUM(TIMESTAMPDIFF(SECOND, start_at, end_at)) as total_seconds'))
                ->value('total_seconds') ?? 0;

            // Total jam realisasi (completed)
            $completedSeconds = Booking::whereBetween('start_at', [$startOfMonth, $endOfMonth])
                ->where('status', RequestStatus::Completed)
                ->select(DB::raw('SUM(TIMESTAMPDIFF(SECOND, start_at, end_at)) as total_seconds'))
                ->value('total_seconds') ?? 0;

            $plannedHours[] = round($plannedSeconds / 3600, 1);
            $completedHours[] = round($completedSeconds / 3600, 1);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jam Terbooking (Rencana)',
                    'data' => $plannedHours,
                    'borderColor' => '#0284c7',
                    'backgroundColor' => 'rgba(2, 132, 199, 0.1)',
                    'fill' => true,
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Jam Realisasi Terlaksana',
                    'data' => $completedHours,
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
