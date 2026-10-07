<?php

namespace App\Filament\Admin\Resources\Competences\Pages;

use App\Filament\Admin\Resources\Competences\CompetenceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCompetence extends EditRecord
{
    protected static string $resource = CompetenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
