<?php

namespace App\Policies;

use App\Models\Core\Employee;
use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Permission\Models\Permission;

class PermissionPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.role.manage');
    }

    public function view(Authenticatable $user, Permission $permission): bool
    {
        return $user instanceof Employee && $user->can('core.role.manage');
    }

    public function create(Authenticatable $user): bool
    {
        return false;
    }

    public function update(Authenticatable $user, Permission $permission): bool
    {
        return false;
    }

    public function delete(Authenticatable $user, Permission $permission): bool
    {
        return false;
    }
}
