<?php

namespace App\Filament\Student\Resources\Books\Pages;

use App\Filament\Student\Resources\Books\BookResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;
}
