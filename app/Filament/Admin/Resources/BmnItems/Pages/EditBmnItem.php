<?php

namespace App\Filament\Admin\Resources\BmnItems\Pages;

use App\Filament\Admin\Resources\BmnItems\BmnItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBmnItem extends EditRecord
{
    protected static string $resource = BmnItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
