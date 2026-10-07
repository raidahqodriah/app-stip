<?php

namespace App\Filament\Admin\Widgets\Library;

use App\Models\Core\Employee;
use App\Models\Library\Book;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BooksByCategoryChart extends ChartWidget
{
    protected static ?int $sort = 23;

    protected int|string|array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getHeading(): ?string
    {
        return __('filament.widgets.titles.books_by_category');
    }

    public static function canView(): bool
    {
        /** @var Employee|null $user */
        $user = auth()->user();

        return $user?->hasAnyRole(['admin', 'leader', 'officer']) ?? false;
    }

    protected function getData(): array
    {
        $categories = Book::query()
            ->select('category', DB::raw('SUM(total_stock) as total_copies'))
            ->groupBy('category')
            ->orderByDesc('total_copies')
            ->pluck('total_copies', 'category')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Eksamplar',
                    'data' => array_values($categories),
                    'backgroundColor' => [
                        '#0284c7', // Sky
                        '#059669', // Emerald
                        '#f59e0b', // Amber
                        '#6366f1', // Indigo
                        '#8b5cf6', // Violet
                        '#ec4899', // Pink
                    ],
                ],
            ],
            'labels' => array_keys($categories),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
