<?php

namespace App\Policies;

use App\Models\Core\Employee;
use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Permission\Models\Role;

class RolePolicy
{
    /**
     * Built-in system roles that cannot be deleted or renamed.
     *
     * @var array<string>
     */
    public const SYSTEM_ROLES = [
        'admin',
        'leader',
        'officer',
        'unit_admin',
        'teacher',
    ];

    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.role.manage');
    }

    public function view(Authenticatable $user, Role $role): bool
    {
        return $user instanceof Employee && $user->can('core.role.manage');
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.role.manage');
    }

    public function update(Authenticatable $user, Role $role): bool
    {
        return $user instanceof Employee && $user->can('core.role.manage');
    }

    public function delete(Authenticatable $user, Role $role): bool
    {
        if (in_array($role->name, self::SYSTEM_ROLES, true)) {
            return false;
        }

        return $user instanceof Employee && $user->can('core.role.manage');
    }
}
