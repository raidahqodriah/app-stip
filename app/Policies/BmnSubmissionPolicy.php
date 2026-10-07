<?php

namespace App\Policies;

use App\Models\Bmn\BmnSubmission;
use App\Models\Core\Employee;
use BackedEnum;
use Illuminate\Contracts\Auth\Authenticatable;

class BmnSubmissionPolicy
{
    private function getStatus(BmnSubmission $submission): ?string
    {
        return $submission->status instanceof BackedEnum ? $submission->status->value : $submission->status;
    }

    public function viewAny(Authenticatable $user): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        return $user->can('bmn.submission.view-all') || $user->can('bmn.submission.view-unit');
    }

    public function view(Authenticatable $user, BmnSubmission $submission): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        if ($user->can('bmn.submission.view-all')) {
            return true;
        }

        if ($user->can('bmn.submission.view-unit')) {
            return $submission->unit_id === $user->unit_id;
        }

        return false;
    }

    public function create(Authenticatable $user): bool
    {
        return $user instanceof Employee && $user->can('bmn.submission.create');
    }

    public function update(Authenticatable $user, BmnSubmission $submission): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($submission);

        if (! in_array($status, ['draft', 'revision_requested'], true)) {
            return false;
        }

        if ($user->can('bmn.submission.process')) {
            return true;
        }

        if ($user->can('bmn.submission.create')) {
            return $submission->unit_id === $user->unit_id;
        }

        return false;
    }

    public function delete(Authenticatable $user, BmnSubmission $submission): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($submission);

        if ($status !== 'draft') {
            return false;
        }

        return $user->hasRole('admin')
            || ($user->can('bmn.submission.create') && $submission->unit_id === $user->unit_id);
    }

    public function process(Authenticatable $user, BmnSubmission $submission): bool
    {
        if (! ($user instanceof Employee)) {
            return false;
        }

        $status = $this->getStatus($submission);

        return in_array($status, ['submitted', 'revision_requested'], true)
            && $user->can('bmn.submission.process');
    }
}
