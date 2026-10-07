<?php

namespace App\Filament\Admin\Resources\BmnReturns\Pages;

use App\Filament\Admin\Resources\BmnReturns\BmnReturnResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBmnReturn extends EditRecord
{
    protected static string $resource = BmnReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
