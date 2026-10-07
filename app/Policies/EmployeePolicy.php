<?php

namespace App\Policies;

use App\Models\Core\Employee;
use Illuminate\Contracts\Auth\Authenticatable;

class EmployeePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.employee.view');
    }

    public function view(Authenticatable $user, Employee $target): bool
    {
        return $user instanceof Employee && $user->can('core.employee.view');
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.employee.create');
    }

    public function update(Authenticatable $user, Employee $target): bool
    {
        if ($user instanceof Employee && $user->id === $target->id) {
            return true;
        }

        return $user instanceof Employee && $user->can('core.employee.update');
    }

    public function delete(Authenticatable $user, Employee $target): bool
    {
        if ($user instanceof Employee && $user->id === $target->id) {
            return false;
        }

        return $user instanceof Employee && $user->can('core.employee.delete');
    }
}
