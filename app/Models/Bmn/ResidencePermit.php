<?php

namespace App\Models\Bmn;

use App\Enums\RequestStatus;
use App\Models\Core\Attachment;
use App\Models\Core\Employee;
use App\Models\Core\GeneratedDocument;
use App\Models\Core\RequestLog;
use App\Models\Core\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class ResidencePermit extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_number',
        'employee_id',
        'unit_id',
        'official_residence_id',
        'submitted_by',
        'verified_by',
        'approved_by',
        'occupancy_start',
        'occupancy_end',
        'occupancy_notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'occupancy_start' => 'date',
            'occupancy_end' => 'date',
            'status' => RequestStatus::class,
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function officialResidence(): BelongsTo
    {
        return $this->belongsTo(OfficialResidence::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'submitted_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'verified_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
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
