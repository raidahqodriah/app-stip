<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Core\Student;
use App\Models\Lab\Booking;
use BackedEnum;
use Illuminate\Contracts\Auth\Authenticatable;

class BookingPolicy
{
    private function getStatus(Booking $booking): ?string
    {
        return $booking->status instanceof BackedEnum ? $booking->status->value : $booking->status;
    }

    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Employee) {
            return $user->can('lab.booking.view-all') || $user->can('lab.booking.view-own');
        }

        if ($user instanceof Student) {
            return true;
        }

        return false;
    }

    public function view(Authenticatable $user, Booking $booking): bool
    {
        if ($user instanceof Employee) {
            if ($user->can('lab.booking.view-all')) {
                return true;
            }

            if ($user->can('lab.booking.view-own')) {
                return ($booking->requester_type === 'employee' && $booking->requester_id === $user->id)
                    || $booking->responsible_lecturer_id === $user->id;
            }

            return false;
        }

        if ($user instanceof Student) {
            return $booking->requester_type === 'student' && $booking->requester_id === $user->id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        if ($user instanceof Employee) {
            return $user->can('lab.booking.create') || $user->can('lab.booking.create-on-behalf');
        }

        if ($user instanceof Student) {
            return true; // Taruna booking mandiri CBT/ERCS
        }

        return false;
    }

    public function update(Authenticatable $user, Booking $booking): bool
    {
        $status = $this->getStatus($booking);

        if (! in_array($status, ['draft', 'revision_requested'], true)) {
            return false;
        }

        if ($user instanceof Employee) {
            if ($user->can('lab.booking.create-on-behalf')) {
                return true;
            }

            if ($user->can('lab.booking.update')) {
                return ($booking->requester_type === 'employee' && $booking->requester_id === $user->id)
                    || $booking->responsible_lecturer_id === $user->id;
            }

            return false;
        }

        if ($user instanceof Student) {
            return $booking->requester_type === 'student' && $booking->requester_id === $user->id;
        }

        return false;
    }

    public function delete(Authenticatable $user, Booking $booking): bool
    {
        $status = $this->getStatus($booking);

        if (! in_array($status, ['draft', 'submitted', 'revision_requested'], true)) {
            return false;
        }

        if ($user instanceof Employee) {
            if ($user->can('lab.booking.cancel')) {
                if ($user->can('lab.booking.create-on-behalf')) {
                    return true;
                }

                return ($booking->requester_type === 'employee' && $booking->requester_id === $user->id)
                    || $booking->responsible_lecturer_id === $user->id;
            }

            return false;
        }

        if ($user instanceof Student) {
            return $booking->requester_type === 'student' && $booking->requester_id === $user->id;
        }

        return false;
    }

    public function verify(Authenticatable $user, Booking $booking): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($booking);

        return in_array($status, ['submitted', 'revision_requested'], true) && $user->can('lab.booking.verify');
    }

    public function approve(Authenticatable $user, Booking $booking): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($booking);

        return $status === 'verified' && $user->can('lab.booking.approve');
    }

    public function realize(Authenticatable $user, Booking $booking): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($booking);

        if (! in_array($status, ['approved', 'in_use', 'completed'], true)) {
            return false;
        }

        if ($user->can('lab.booking.realize')) {
            if ($user->can('lab.booking.verify')) {
                return true;
            }

            return $booking->responsible_lecturer_id === $user->id;
        }

        return false;
    }
}
