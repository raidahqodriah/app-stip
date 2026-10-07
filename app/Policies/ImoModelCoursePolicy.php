<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Lab\ImoModelCourse;
use Illuminate\Contracts\Auth\Authenticatable;

class ImoModelCoursePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && ($user->can('lab.curriculum.manage') || $user->can('lab.schedule.view'));
    }

    public function view(Authenticatable $user, ImoModelCourse $course): bool
    {
        return $user instanceof Employee && ($user->can('lab.curriculum.manage') || $user->can('lab.schedule.view'));
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('lab.curriculum.manage');
    }

    public function update(Authenticatable $user, ImoModelCourse $course): bool
    {
        return $user instanceof Employee && $user->can('lab.curriculum.manage');
    }

    public function delete(Authenticatable $user, ImoModelCourse $course): bool
    {
        return $user instanceof Employee && $user->can('lab.curriculum.manage');
    }
}
