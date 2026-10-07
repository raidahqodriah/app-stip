<?php

namespace App\Filament\Admin\Widgets\Residence;

use App\Enums\RequestStatus;
use App\Models\Bmn\OfficialResidence;
use App\Models\Bmn\ResidencePermit;
use App\Models\Core\Employee;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class ResidenceOccupancyChart extends ChartWidget
{
    protected static ?int $sort = 16;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.residence_occupancy');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer']) ?? false;
    }

    protected function getData(): array
    {
        $now = Carbon::now();

        // Total rumah aktif
        $activeHouses = OfficialResidence::where('is_active', true)->count();
        $inactiveHouses = OfficialResidence::where('is_active', false)->count();

        // Rumah terisi (memiliki permit status approved aktif)
        $occupiedCount = ResidencePermit::where('status', RequestStatus::Approved)
            ->where(function ($q) use ($now) {
                $q->whereNull('occupancy_end')
                    ->orWhere('occupancy_end', '>=', $now);
            })
            ->distinct('official_residence_id')
            ->count('official_residence_id');

        $vacantCount = max(0, $activeHouses - $occupiedCount);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Unit',
                    'data' => [$occupiedCount, $vacantCount, $inactiveHouses],
                    'backgroundColor' => [
                        '#059669', // Terisi (Emerald)
                        '#0284c7', // Kosong / Siap Huni (Sky)
                        '#f59e0b', // Renovasi / Non-aktif (Amber)
                    ],
                ],
            ],
            'labels' => [
                'Terisi / Dihuni',
                'Kosong / Siap Huni',
                'Renovasi / Non-aktif',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
