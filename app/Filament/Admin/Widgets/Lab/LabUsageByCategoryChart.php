<?php

namespace App\Filament\Admin\Widgets\Lab;

use App\Models\Core\Employee;
use App\Models\Lab\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class LabUsageByCategoryChart extends ChartWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.lab_usage_by_category');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer']) ?? false;
    }

    protected function getData(): array
    {
        $data = Booking::query()
            ->join('subjects', 'bookings.subject_id', '=', 'subjects.id')
            ->select('subjects.category', DB::raw('count(*) as total'))
            ->groupBy('subjects.category')
            ->pluck('total', 'category')
            ->toArray();

        $labels = [
            'teknika' => 'Teknika',
            'nautika' => 'Nautika',
            'kalk' => 'KALK',
        ];

        $chartLabels = [];
        $chartValues = [];

        foreach ($labels as $key => $name) {
            $chartLabels[] = $name;
            $chartValues[] = $data[$key] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Sesi',
                    'data' => $chartValues,
                    'backgroundColor' => [
                        '#0284c7', // Sky / Nautika
                        '#059669', // Emerald / Teknika
                        '#f59e0b', // Amber / KALK
                    ],
                ],
            ],
            'labels' => $chartLabels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
