<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Core\Unit;
use Illuminate\Contracts\Auth\Authenticatable;

class UnitPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.unit.view');
    }

    public function view(Authenticatable $user, Unit $unit): bool
    {
        return $user instanceof Employee && $user->can('core.unit.view');
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.unit.manage');
    }

    public function update(Authenticatable $user, Unit $unit): bool
    {
        return $user instanceof Employee && $user->can('core.unit.manage');
    }

    public function delete(Authenticatable $user, Unit $unit): bool
    {
        return $user instanceof Employee && $user->can('core.unit.manage');
    }
}
