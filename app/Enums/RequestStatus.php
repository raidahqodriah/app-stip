<?php

namespace App\Enums;

enum RequestStatus: string
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

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draf',
            self::Submitted => 'Menunggu Pemeriksaan',
            self::RevisionRequested => 'Perlu Revisi',
            self::Verified => 'Terverifikasi',
            self::Approved => 'Disetujui',
            self::InUse => 'Sedang Digunakan',
            self::Completed => 'Selesai',
            self::Rejected => 'Ditolak',
            self::Cancelled => 'Dibatalkan',
            self::NoShow => 'Tidak Hadir',
            self::Expired => 'Kedaluwarsa',
        };
    }
}
