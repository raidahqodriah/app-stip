<?php

namespace App\Policies;

use App\Models\Core\Employee;
use App\Models\Core\Setting;
use Illuminate\Contracts\Auth\Authenticatable;

class SettingPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.setting.manage');
    }

    public function view(Authenticatable $user, Setting $setting): bool
    {
        return $user instanceof Employee && $user->can('core.setting.manage');
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.setting.manage');
    }

    public function update(Authenticatable $user, Setting $setting): bool
    {
        return $user instanceof Employee && $user->can('core.setting.manage');
    }

    public function delete(Authenticatable $user, Setting $setting): bool
    {
        return $user instanceof Employee && $user->can('core.setting.manage');
    }
}
