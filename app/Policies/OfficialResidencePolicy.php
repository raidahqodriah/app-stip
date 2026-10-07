<?php

namespace App\Policies;

use App\Models\Bmn\OfficialResidence;
use App\Models\Core\Employee;
use Illuminate\Contracts\Auth\Authenticatable;

class OfficialResidencePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && ($user->can('residence.master.manage') || $user->can('residence.permit.view-all') || $user->can('residence.permit.view-unit'));
    }

    public function view(Authenticatable $user, OfficialResidence $residence): bool
    {
        return $user instanceof Employee && ($user->can('residence.master.manage') || $user->can('residence.permit.view-all') || $user->can('residence.permit.view-unit'));
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('residence.master.manage');
    }

    public function update(Authenticatable $user, OfficialResidence $residence): bool
    {
        return $user instanceof Employee && $user->can('residence.master.manage');
    }

    public function delete(Authenticatable $user, OfficialResidence $residence): bool
    {
        return $user instanceof Employee && $user->can('residence.master.manage');
    }
}
