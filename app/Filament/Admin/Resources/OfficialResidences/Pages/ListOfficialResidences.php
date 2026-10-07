<?php

namespace App\Filament\Admin\Resources\OfficialResidences\Pages;

use App\Filament\Admin\Resources\OfficialResidences\OfficialResidenceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOfficialResidences extends ListRecords
{
    protected static string $resource = OfficialResidenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
