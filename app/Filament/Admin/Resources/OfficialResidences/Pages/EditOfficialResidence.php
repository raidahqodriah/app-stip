<?php

namespace App\Filament\Admin\Resources\OfficialResidences\Pages;

use App\Filament\Admin\Resources\OfficialResidences\OfficialResidenceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOfficialResidence extends EditRecord
{
    protected static string $resource = OfficialResidenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
