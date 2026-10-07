<?php

namespace App\Filament\Admin\Resources\ResidencePermits\Pages;

use App\Filament\Admin\Resources\ResidencePermits\ResidencePermitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateResidencePermit extends CreateRecord
{
    protected static string $resource = ResidencePermitResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['submitted_by'] = auth()->id() ?? 1;

        return $data;
    }
}
