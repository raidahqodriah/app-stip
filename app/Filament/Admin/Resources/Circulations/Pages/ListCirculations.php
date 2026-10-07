<?php

namespace App\Filament\Admin\Resources\Circulations\Pages;

use App\Filament\Admin\Resources\Circulations\CirculationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCirculations extends ListRecords
{
    protected static string $resource = CirculationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
