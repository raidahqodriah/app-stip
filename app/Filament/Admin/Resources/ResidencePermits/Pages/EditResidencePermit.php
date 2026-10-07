<?php

namespace App\Filament\Admin\Resources\ResidencePermits\Pages;

use App\Filament\Admin\Resources\ResidencePermits\ResidencePermitResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditResidencePermit extends EditRecord
{
    protected static string $resource = ResidencePermitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
