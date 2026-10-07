<?php

namespace App\Filament\Admin\Resources\ResidencePermits\Pages;

use App\Filament\Admin\Resources\ResidencePermits\ResidencePermitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResidencePermits extends ListRecords
{
    protected static string $resource = ResidencePermitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
