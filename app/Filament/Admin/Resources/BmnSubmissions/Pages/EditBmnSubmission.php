<?php

namespace App\Filament\Admin\Resources\BmnSubmissions\Pages;

use App\Filament\Admin\Resources\BmnSubmissions\BmnSubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBmnSubmission extends EditRecord
{
    protected static string $resource = BmnSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
