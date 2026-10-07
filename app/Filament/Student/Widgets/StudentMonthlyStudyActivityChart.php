<?php

namespace App\Filament\Student\Widgets;

use App\Models\Core\Student;
use App\Models\Lab\Booking;
use App\Models\Library\Circulation;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class StudentMonthlyStudyActivityChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.student_monthly_study_activity');
    }

    protected function getData(): array
    {
        /** @var Student|null $student */
        $student = auth()->user();
        $studentId = $student?->id ?? 0;
        $classGroup = $student?->class_group;

        $labels = [];
        $labSessions = [];
        $bookLoans = [];

        $now = Carbon::now();

        for ($i = 3; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $labels[] = $month->translatedFormat('F Y');

            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            // Total sesi lab yang diikuti
            $sessionsCount = Booking::whereBetween('start_at', [$start, $end])
                ->where(function ($q) use ($studentId, $classGroup) {
                    $q->where(function ($sub) use ($studentId) {
                        $sub->where('requester_type', Student::class)
                            ->where('requester_id', $studentId);
                    });

                    if ($classGroup) {
                        $q->orWhere('class_group', $classGroup);
                    }
                })
                ->count();

            // Total buku dipinjam
            $loansCount = Circulation::where('borrower_type', Student::class)
                ->where('borrower_id', $studentId)
                ->whereBetween('loan_date', [$start, $end])
                ->count();

            $labSessions[] = $sessionsCount;
            $bookLoans[] = $loansCount;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sesi Praktikum Lab & Simulator',
                    'data' => $labSessions,
                    'backgroundColor' => '#0284c7',
                    'borderRadius' => 4,
                ],
                [
                    'label' => 'Buku Dipinjam di Perpustakaan',
                    'data' => $bookLoans,
                    'backgroundColor' => '#10b981',
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
