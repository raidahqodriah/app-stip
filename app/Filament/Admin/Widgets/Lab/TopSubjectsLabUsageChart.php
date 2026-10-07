<?php

namespace App\Filament\Admin\Widgets\Lab;

use App\Models\Core\Employee;
use App\Models\Lab\Booking;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopSubjectsLabUsageChart extends ChartWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.top_subjects_lab_usage');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer', 'teacher']) ?? false;
    }

    protected function getData(): array
    {
        $topSubjects = Booking::query()
            ->join('subjects', 'bookings.subject_id', '=', 'subjects.id')
            ->select('subjects.name', DB::raw('count(*) as booking_count'))
            ->groupBy('subjects.id', 'subjects.name')
            ->orderByDesc('booking_count')
            ->limit(5)
            ->get();

        $labels = [];
        $values = [];

        foreach ($topSubjects as $item) {
            // Shorten name if too long
            $shortName = strlen($item->name) > 25 ? substr($item->name, 0, 22).'...' : $item->name;
            $labels[] = $shortName;
            $values[] = $item->booking_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Sesi Lab',
                    'data' => $values,
                    'backgroundColor' => '#3b82f6',
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
