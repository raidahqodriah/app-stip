<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Core\Student;
use App\Models\Library\Circulation;
use BackedEnum;
use Illuminate\Contracts\Auth\Authenticatable;

class CirculationPolicy
{
    private function getStatus(Circulation $circulation): ?string
    {
        return $circulation->status instanceof BackedEnum ? $circulation->status->value : $circulation->status;
    }

    public function viewAny(Authenticatable $user): bool
    {
        if ($user instanceof Employee) {
            return $user->can('library.circulation.view-all') || $user->can('library.circulation.view-own');
        }

        if ($user instanceof Student) {
            return true;
        }

        return false;
    }

    public function view(Authenticatable $user, Circulation $circulation): bool
    {
        if ($user instanceof Employee) {
            if ($user->can('library.circulation.view-all')) {
                return true;
            }

            if ($user->can('library.circulation.view-own')) {
                return $circulation->borrower_type === 'employee' && $circulation->borrower_id === $user->id;
            }

            return false;
        }

        if ($user instanceof Student) {
            return $circulation->borrower_type === 'student' && $circulation->borrower_id === $user->id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('library.circulation.process');
    }

    public function update(Authenticatable $user, Circulation $circulation): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($circulation);

        if ($status === 'returned') {
            return false;
        }

        return $user->can('library.circulation.process');
    }

    public function delete(Authenticatable $user, Circulation $circulation): bool
    {
        return false;
    }
}
