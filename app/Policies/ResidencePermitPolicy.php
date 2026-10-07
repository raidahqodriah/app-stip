<?php

namespace App\Policies;

use App\Models\Bmn\ResidencePermit;
use App\Models\Core\Employee;
use BackedEnum;
use Illuminate\Contracts\Auth\Authenticatable;

class ResidencePermitPolicy
{
    private function getStatus(ResidencePermit $permit): ?string
    {
        return $permit->status instanceof BackedEnum ? $permit->status->value : $permit->status;
    }

    public function viewAny(Authenticatable $user): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        return $user->can('residence.permit.view-all') || $user->can('residence.permit.view-unit');
    }

    public function view(Authenticatable $user, ResidencePermit $permit): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        if ($user->can('residence.permit.view-all')) {
            return true;
        }

        if ($user->can('residence.permit.view-unit')) {
            return $permit->unit_id === $user->unit_id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('residence.permit.create');
    }

    public function update(Authenticatable $user, ResidencePermit $permit): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($permit);

        if (! in_array($status, ['draft', 'revision_requested'], true)) {
            return false;
        }

        if ($user->can('residence.permit.verify')) {
            return true;
        }

        return $permit->unit_id === $user->unit_id && $user->can('residence.permit.create');
    }

    public function delete(Authenticatable $user, ResidencePermit $permit): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($permit);

        if ($status !== 'draft') {
            return false;
        }

        return $user->hasRole('admin')
            || ($user->can('residence.permit.create') && $permit->unit_id === $user->unit_id);
    }

    public function verify(Authenticatable $user, ResidencePermit $permit): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($permit);

        return in_array($status, ['submitted', 'revision_requested'], true) && $user->can('residence.permit.verify');
    }

    public function approve(Authenticatable $user, ResidencePermit $permit): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($permit);

        return $status === 'verified' && $user->can('residence.permit.approve');
    }
}
