<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Core\Student;
use Illuminate\Contracts\Auth\Authenticatable;

class StudentPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.student.view');
    }

    public function view(Authenticatable $user, Student $student): bool
    {
        return $user instanceof Employee && $user->can('core.student.view');
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.student.create');
    }

    public function update(Authenticatable $user, Student $student): bool
    {
        return $user instanceof Employee && $user->can('core.student.update');
    }

    public function delete(Authenticatable $user, Student $student): bool
    {
        return $user instanceof Employee && $user->can('core.student.delete');
    }
}
