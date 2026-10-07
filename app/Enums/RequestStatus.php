<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum RequestStatus: string implements HasLabel
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case RevisionRequested = 'revision_requested';
    case Verified = 'verified';
    case Approved = 'approved';
    case InUse = 'in_use';
    case Completed = 'completed';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';
    case NoShow = 'no_show';
    case Expired = 'expired';

    public function holdsSlot(): bool
    {
        return in_array($this, [
            self::Submitted,
            self::RevisionRequested,
            self::Verified,
            self::Approved,
            self::InUse,
            self::Completed,
        ], true);
    }

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => __('enums.request_status.draft'),
            self::Submitted => __('enums.request_status.submitted'),
            self::RevisionRequested => __('enums.request_status.revision_requested'),
            self::Verified => __('enums.request_status.verified'),
            self::Approved => __('enums.request_status.approved'),
            self::InUse => __('enums.request_status.in_use'),
            self::Completed => __('enums.request_status.completed'),
            self::Rejected => __('enums.request_status.rejected'),
            self::Cancelled => __('enums.request_status.cancelled'),
            self::NoShow => __('enums.request_status.no_show'),
            self::Expired => __('enums.request_status.expired'),
        };
    }

    public function label(): string
    {
        return $this->getLabel() ?? $this->value;
    }
}
