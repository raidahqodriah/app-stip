<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Lab\Material;
use Illuminate\Contracts\Auth\Authenticatable;

class MaterialPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && ($user->can('lab.material.manage') || $user->can('lab.booking.create') || $user->can('lab.schedule.view'));
    }

    public function view(Authenticatable $user, Material $material): bool
    {
        return $user instanceof Employee && ($user->can('lab.material.manage') || $user->can('lab.booking.create') || $user->can('lab.schedule.view'));
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('lab.material.manage');
    }

    public function update(Authenticatable $user, Material $material): bool
    {
        return $user instanceof Employee && $user->can('lab.material.manage');
    }

    public function delete(Authenticatable $user, Material $material): bool
    {
        return $user instanceof Employee && $user->can('lab.material.manage');
    }
}
