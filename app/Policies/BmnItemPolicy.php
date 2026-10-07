<?php

namespace App\Policies;

use App\Models\Bmn\BmnItem;
use App\Models\Core\Employee;
use Illuminate\Contracts\Auth\Authenticatable;

class BmnItemPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        return $user->can('bmn.item.view-all') || $user->can('bmn.item.view-unit');
    }

    public function view(Authenticatable $user, BmnItem $item): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        if ($user->can('bmn.item.view-all')) {
            return true;
        }

        if ($user->can('bmn.item.view-unit')) {
            return $item->unit_id === $user->unit_id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('bmn.item.manage');
    }

    public function update(Authenticatable $user, BmnItem $item): bool
    {
        return $user instanceof Employee && $user->can('bmn.item.manage');
    }

    public function delete(Authenticatable $user, BmnItem $item): bool
    {
        return $user instanceof Employee && $user->can('bmn.item.manage') && $user->hasRole('admin');
    }
}
