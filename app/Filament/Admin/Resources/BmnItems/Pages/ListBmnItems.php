<?php

namespace App\Filament\Admin\Resources\BmnItems\Pages;

use App\Filament\Admin\Resources\BmnItems\BmnItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBmnItems extends ListRecords
{
    protected static string $resource = BmnItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
