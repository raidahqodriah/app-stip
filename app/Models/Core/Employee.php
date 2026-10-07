<?php

namespace App\Models\Core;

use App\Models\Bmn\BmnItem;
use App\Models\Bmn\BmnReturn;
use App\Models\Bmn\BmnSubmission;
use App\Models\Bmn\ResidencePermit;
use App\Models\Lab\Booking;
use App\Models\Lab\BookingRealization;
use App\Models\Library\Circulation;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'unit_id',
        'name',
        'email',
        'password',
        'employee_number',
        'position',
        'phone',
        'is_active',
        'theme_color',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin' && $this->is_active;
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function headedUnits(): HasMany
    {
        return $this->hasMany(Unit::class, 'head_employee_id');
    }

    public function managedRooms(): HasMany
    {
        return $this->hasMany(Room::class, 'pic_employee_id');
    }

    public function bookingsRequested(): MorphMany
    {
        return $this->morphMany(Booking::class, 'requester');
    }

    public function supervisedBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'responsible_lecturer_id');
    }

    public function recordedRealizations(): HasMany
    {
        return $this->hasMany(BookingRealization::class, 'recorded_by');
    }

    public function issuedCirculations(): HasMany
    {
        return $this->hasMany(Circulation::class, 'loaned_by');
    }

    public function acceptedCirculations(): HasMany
    {
        return $this->hasMany(Circulation::class, 'returned_by');
    }

    public function borrowedCirculations(): MorphMany
    {
        return $this->morphMany(Circulation::class, 'borrower');
    }

    public function bmnItems(): HasMany
    {
        return $this->hasMany(BmnItem::class, 'responsible_employee_id');
    }

    public function bmnSubmissions(): HasMany
    {
        return $this->hasMany(BmnSubmission::class, 'submitted_by');
    }

    public function verifiedBmnSubmissions(): HasMany
    {
        return $this->hasMany(BmnSubmission::class, 'verified_by');
    }

    public function bmnReturns(): HasMany
    {
        return $this->hasMany(BmnReturn::class, 'requested_by');
    }

    public function verifiedBmnReturns(): HasMany
    {
        return $this->hasMany(BmnReturn::class, 'verified_by');
    }

    public function residencePermits(): HasMany
    {
        return $this->hasMany(ResidencePermit::class, 'employee_id');
    }

    public function submittedResidencePermits(): HasMany
    {
        return $this->hasMany(ResidencePermit::class, 'submitted_by');
    }

    public function verifiedResidencePermits(): HasMany
    {
        return $this->hasMany(ResidencePermit::class, 'verified_by');
    }

    public function approvedResidencePermits(): HasMany
    {
        return $this->hasMany(ResidencePermit::class, 'approved_by');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'uploader');
    }

    public function requestLogs(): MorphMany
    {
        return $this->morphMany(RequestLog::class, 'actor');
    }

    public function generatedDocuments(): MorphMany
    {
        return $this->morphMany(GeneratedDocument::class, 'generator');
    }
}
