<?php

namespace App\Policies;

use App\Models\Bmn\BmnReturn;
use App\Models\Core\Employee;
use BackedEnum;
use Illuminate\Contracts\Auth\Authenticatable;

class BmnReturnPolicy
{
    private function getStatus(BmnReturn $bmnReturn): ?string
    {
        return $bmnReturn->status instanceof BackedEnum ? $bmnReturn->status->value : $bmnReturn->status;
    }

    public function viewAny(Authenticatable $user): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        return $user->can('bmn.return.view-all') || $user->can('bmn.return.view-unit');
    }

    public function view(Authenticatable $user, BmnReturn $bmnReturn): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        if ($user->can('bmn.return.view-all')) {
            return true;
        }

        if ($user->can('bmn.return.view-unit')) {
            return $bmnReturn->fromRoom?->unit_id === $user->unit_id
                || $bmnReturn->bmnItem?->unit_id === $user->unit_id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('bmn.return.create');
    }

    public function update(Authenticatable $user, BmnReturn $bmnReturn): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($bmnReturn);

        if (! in_array($status, ['draft', 'revision_requested'], true)) {
            return false;
        }

        if ($user->can('bmn.return.process')) {
            return true;
        }

        if ($user->can('bmn.return.create')) {
            return $bmnReturn->fromRoom?->unit_id === $user->unit_id;
        }

        return false;
    }

    public function delete(Authenticatable $user, BmnReturn $bmnReturn): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($bmnReturn);

        if ($status !== 'draft') {
            return false;
        }

        return $user->hasRole('admin')
            || ($user->can('bmn.return.create') && $bmnReturn->fromRoom?->unit_id === $user->unit_id);
    }

    public function process(Authenticatable $user, BmnReturn $bmnReturn): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($bmnReturn);

        return in_array($status, ['submitted', 'revision_requested'], true)
            && $user->can('bmn.return.process');
    }
}
