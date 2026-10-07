<?php

namespace App\Filament\Admin\Resources\Bookings\Pages;

use App\Filament\Admin\Resources\Bookings\BookingResource;
use App\Models\Core\Employee;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['requester_type'] = Employee::class;
        $data['requester_id'] = auth()->id() ?? 1;
        $data['submitted_at'] = now();

        return $data;
    }
}
