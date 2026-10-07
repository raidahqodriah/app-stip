<?php

namespace App\Policies;

use App\Models\Core\AuditLog;
use App\Models\Core\Employee;
use Illuminate\Contracts\Auth\Authenticatable;

class AuditLogPolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.audit.view');
    }

    public function view(Authenticatable $user, AuditLog $auditLog): bool
    {
        return $user instanceof Employee && $user->can('core.audit.view');
    }

    public function create(Authenticatable $user): bool
    {
        return false;
    }

    public function update(Authenticatable $user, AuditLog $auditLog): bool
    {
        return false;
    }

    public function delete(Authenticatable $user, AuditLog $auditLog): bool
    {
        return false;
    }
}
