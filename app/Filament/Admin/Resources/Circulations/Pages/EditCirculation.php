<?php

namespace App\Filament\Admin\Resources\Circulations\Pages;

use App\Filament\Admin\Resources\Circulations\CirculationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCirculation extends EditRecord
{
    protected static string $resource = CirculationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
