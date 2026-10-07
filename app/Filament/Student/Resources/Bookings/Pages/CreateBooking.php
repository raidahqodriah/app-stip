<?php

namespace App\Filament\Student\Resources\Bookings\Pages;

use App\Enums\RequestStatus;
use App\Filament\Student\Resources\Bookings\BookingResource;
use App\Models\Core\Student;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        $data['requester_type'] = Student::class;
        $data['requester_id'] = $user?->id ?? 1;
        $data['unit_id'] = $user?->unit_id ?? 2;
        $data['participant_count'] = 1;
        $data['class_group'] = $user?->class_group ?? 'Taruna Mandiri';
        $data['status'] = RequestStatus::Submitted;
        $data['submitted_at'] = now();

        return $data;
    }
}
