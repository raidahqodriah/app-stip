<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Lab\BlackoutDate;
use Illuminate\Contracts\Auth\Authenticatable;

class BlackoutDatePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && ($user->can('lab.blackout.manage') || $user->can('lab.schedule.view'));
    }

    public function view(Authenticatable $user, BlackoutDate $blackoutDate): bool
    {
        return $user instanceof Employee && ($user->can('lab.blackout.manage') || $user->can('lab.schedule.view'));
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('lab.blackout.manage');
    }

    public function update(Authenticatable $user, BlackoutDate $blackoutDate): bool
    {
        return $user instanceof Employee && $user->can('lab.blackout.manage');
    }

    public function delete(Authenticatable $user, BlackoutDate $blackoutDate): bool
    {
        return $user instanceof Employee && $user->can('lab.blackout.manage');
    }
}
