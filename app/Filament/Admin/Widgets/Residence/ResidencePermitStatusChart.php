<?php

namespace App\Filament\Admin\Widgets\Residence;

use App\Enums\RequestStatus;
use App\Models\Bmn\ResidencePermit;
use App\Models\Core\Employee;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ResidencePermitStatusChart extends ChartWidget
{
    protected static ?int $sort = 17;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.residence_permit_status');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer']) ?? false;
    }

    protected function getData(): array
    {
        $statusCounts = ResidencePermit::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Permohonan SIP',
                    'data' => [
                        $statusCounts[RequestStatus::Submitted->value] ?? 0,
                        $statusCounts[RequestStatus::Verified->value] ?? 0,
                        $statusCounts[RequestStatus::Approved->value] ?? 0,
                        $statusCounts[RequestStatus::Rejected->value] ?? 0,
                    ],
                    'backgroundColor' => [
                        '#f59e0b', // Submitted (Amber)
                        '#0284c7', // Verified (Sky)
                        '#059669', // Approved (Emerald)
                        '#dc2626', // Rejected (Red)
                    ],
                    'borderRadius' => 4,
                ],
            ],
            'labels' => [
                'Menunggu Verifikasi',
                'Menunggu Persetujuan',
                'Disetujui',
                'Ditolak',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
