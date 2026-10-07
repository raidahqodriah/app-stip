<?php

namespace App\Filament\Admin\Resources\BmnReturns\Pages;

use App\Filament\Admin\Resources\BmnReturns\BmnReturnResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBmnReturns extends ListRecords
{
    protected static string $resource = BmnReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
