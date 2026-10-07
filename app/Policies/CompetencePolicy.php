<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Lab\Competence;
use Illuminate\Contracts\Auth\Authenticatable;

class CompetencePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && ($user->can('lab.curriculum.manage') || $user->can('lab.schedule.view'));
    }

    public function view(Authenticatable $user, Competence $competence): bool
    {
        return $user instanceof Employee && ($user->can('lab.curriculum.manage') || $user->can('lab.schedule.view'));
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('lab.curriculum.manage');
    }

    public function update(Authenticatable $user, Competence $competence): bool
    {
        return $user instanceof Employee && $user->can('lab.curriculum.manage');
    }

    public function delete(Authenticatable $user, Competence $competence): bool
    {
        return $user instanceof Employee && $user->can('lab.curriculum.manage');
    }
}
