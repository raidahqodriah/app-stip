<?php

namespace App\Filament\Student\Resources\Circulations\Pages;

use App\Filament\Student\Resources\Circulations\CirculationResource;
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
