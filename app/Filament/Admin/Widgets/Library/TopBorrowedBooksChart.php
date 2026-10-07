<?php

namespace App\Filament\Admin\Widgets\Library;

use App\Models\Core\Employee;
use App\Models\Library\Circulation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopBorrowedBooksChart extends ChartWidget
{
    protected static ?int $sort = 22;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.top_borrowed_books');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer', 'teacher']) ?? false;
    }

    protected function getData(): array
    {
        $topBooks = Circulation::query()
            ->join('books', 'circulations.book_id', '=', 'books.id')
            ->select('books.title', DB::raw('count(*) as loan_count'))
            ->groupBy('books.id', 'books.title')
            ->orderByDesc('loan_count')
            ->limit(5)
            ->get();

        $labels = [];
        $values = [];

        foreach ($topBooks as $b) {
            $shortTitle = strlen($b->title) > 25 ? substr($b->title, 0, 22).'...' : $b->title;
            $labels[] = $shortTitle;
            $values[] = $b->loan_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Peminjaman',
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
