<?php

namespace App\Filament\Admin\Resources\ImoModelCourses\Pages;

use App\Filament\Admin\Resources\ImoModelCourses\ImoModelCourseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListImoModelCourses extends ListRecords
{
    protected static string $resource = ImoModelCourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
