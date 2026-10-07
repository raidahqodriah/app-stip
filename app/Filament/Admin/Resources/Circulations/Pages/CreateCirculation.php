<?php

namespace App\Filament\Admin\Resources\Circulations\Pages;

use App\Filament\Admin\Resources\Circulations\CirculationResource;
use App\Models\Core\Student;
use Filament\Resources\Pages\CreateRecord;

class CreateCirculation extends CreateRecord
{
    protected static string $resource = CirculationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['borrower_type'] = Student::class;
        $data['loaned_by'] = auth()->id() ?? 1;

        return $data;
    }

    protected function afterCreate(): void
    {
        $circulation = $this->record;
        $circulation->book?->decrement('available_stock');
    }
}
