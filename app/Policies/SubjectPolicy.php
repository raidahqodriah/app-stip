<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Lab\Subject;
use Illuminate\Contracts\Auth\Authenticatable;

class SubjectPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && ($user->can('lab.curriculum.manage') || $user->can('lab.schedule.view'));
    }

    public function view(Authenticatable $user, Subject $subject): bool
    {
        return $user instanceof Employee && ($user->can('lab.curriculum.manage') || $user->can('lab.schedule.view'));
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('lab.curriculum.manage');
    }

    public function update(Authenticatable $user, Subject $subject): bool
    {
        return $user instanceof Employee && $user->can('lab.curriculum.manage');
    }

    public function delete(Authenticatable $user, Subject $subject): bool
    {
        return $user instanceof Employee && $user->can('lab.curriculum.manage');
    }
}
