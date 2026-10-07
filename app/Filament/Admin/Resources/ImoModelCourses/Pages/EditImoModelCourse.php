<?php

namespace App\Filament\Admin\Resources\ImoModelCourses\Pages;

use App\Filament\Admin\Resources\ImoModelCourses\ImoModelCourseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditImoModelCourse extends EditRecord
{
    protected static string $resource = ImoModelCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
