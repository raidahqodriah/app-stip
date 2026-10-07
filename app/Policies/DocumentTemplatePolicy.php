<?php

namespace App\Policies;

use App\Models\Core\DocumentTemplate;
use App\Models\Core\Employee;
use Illuminate\Contracts\Auth\Authenticatable;

class DocumentTemplatePolicy
{
    public function viewAny(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.template.manage');
    }

    public function view(Authenticatable $user, DocumentTemplate $template): bool
    {
        return $user instanceof Employee && $user->can('core.template.manage');
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('core.template.manage');
    }

    public function update(Authenticatable $user, DocumentTemplate $template): bool
    {
        return $user instanceof Employee && $user->can('core.template.manage');
    }

    public function delete(Authenticatable $user, DocumentTemplate $template): bool
    {
        return $user instanceof Employee && $user->can('core.template.manage');
    }
}
