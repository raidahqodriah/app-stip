<?php

namespace App\Filament\Admin\Resources\BmnSubmissions\Pages;

use App\Filament\Admin\Resources\BmnSubmissions\BmnSubmissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBmnSubmission extends CreateRecord
{
    protected static string $resource = BmnSubmissionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['submitted_by'] = auth()->id() ?? 1;

        return $data;
    }
}
