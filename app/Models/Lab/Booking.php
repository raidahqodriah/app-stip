<?php

namespace App\Models\Lab;

use App\Enums\RequestStatus;
use App\Models\Core\Attachment;
use App\Models\Core\Employee;
use App\Models\Core\GeneratedDocument;
use App\Models\Core\RequestLog;
use App\Models\Core\Room;
use App\Models\Core\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number',
        'room_id',
        'subject_id',
        'unit_id',
        'requester_type',
        'requester_id',
        'responsible_lecturer_id',
        'recurrence_series_id',
        'start_at',
        'end_at',
        'purpose',
        'participant_count',
        'class_group',
        'status',
        'submitted_at',
        'notes',
        'external_institution',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'submitted_at' => 'datetime',
            'participant_count' => 'integer',
            'status' => RequestStatus::class,
        ];
    }

    public function scopeHoldsSlot(Builder $query): Builder
    {
        return $query->whereIn('status', [
            RequestStatus::Submitted,
            RequestStatus::RevisionRequested,
            RequestStatus::Verified,
            RequestStatus::Approved,
            RequestStatus::InUse,
            RequestStatus::Completed,
        ]);
    }

    public function holdsSlot(): bool
    {
        return $this->status instanceof RequestStatus && $this->status->holdsSlot();
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function requester(): MorphTo
    {
        return $this->morphTo();
    }

    public function responsibleLecturer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'responsible_lecturer_id');
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'booking_competence')->withTimestamps();
    }

    public function materials(): HasMany
    {
        return $this->hasMany(BookingMaterial::class);
    }

    public function slots(): HasMany
    {
        return $this->hasMany(BookingSlot::class);
    }

    public function realization(): HasOne
    {
        return $this->hasOne(BookingRealization::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function requestLogs(): MorphMany
    {
        return $this->morphMany(RequestLog::class, 'loggable');
    }

    public function generatedDocument(): MorphOne
    {
        return $this->morphOne(GeneratedDocument::class, 'documentable');
    }
}
