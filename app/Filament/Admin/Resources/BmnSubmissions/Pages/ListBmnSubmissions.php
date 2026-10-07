<?php

namespace App\Filament\Admin\Resources\BmnSubmissions\Pages;

use App\Filament\Admin\Resources\BmnSubmissions\BmnSubmissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBmnSubmissions extends ListRecords
{
    protected static string $resource = BmnSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
