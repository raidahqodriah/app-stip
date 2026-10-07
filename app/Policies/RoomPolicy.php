<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Core\Room;
use Illuminate\Contracts\Auth\Authenticatable;

class RoomPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && ($user->can('core.room.view') || $user->can('lab.schedule.view'));
    }

    public function view(Authenticatable $user, Room $room): bool
    {
        return $user instanceof Employee && ($user->can('core.room.view') || $user->can('lab.schedule.view'));
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.room.manage');
    }

    public function update(Authenticatable $user, Room $room): bool
    {
        return $user instanceof Employee && $user->can('core.room.manage');
    }

    public function delete(Authenticatable $user, Room $room): bool
    {
        return $user instanceof Employee && $user->can('core.room.manage') && $user->hasRole('admin');
    }
}
