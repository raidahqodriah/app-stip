<?php

namespace App\Filament\Admin\Widgets\Lab;

use App\Models\Core\Employee;
use App\Models\Lab\ImoModelCourse;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ImoCompetenceCoverageChart extends ChartWidget
{
    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.imo_competence_coverage');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer']) ?? false;
    }

    protected function getData(): array
    {
        $courses = ImoModelCourse::query()
            ->join('competences', 'imo_model_courses.id', '=', 'competences.imo_model_course_id')
            ->join('booking_competence', 'competences.id', '=', 'booking_competence.competence_id')
            ->select('imo_model_courses.code', DB::raw('count(booking_competence.booking_id) as total_bookings'))
            ->groupBy('imo_model_courses.id', 'imo_model_courses.code')
            ->orderByDesc('total_bookings')
            ->limit(6)
            ->get();

        $labels = [];
        $values = [];

        foreach ($courses as $course) {
            $labels[] = 'IMO '.$course->code;
            $values[] = $course->total_bookings;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sesi Berstandar IMO',
                    'data' => $values,
                    'backgroundColor' => '#6366f1',
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
