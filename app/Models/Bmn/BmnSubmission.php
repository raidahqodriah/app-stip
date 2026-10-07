<?php

namespace App\Models\Bmn;

use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Models\Core\Attachment;
use App\Models\Core\Employee;
use App\Models\Core\RequestLog;
use App\Models\Core\Room;
use App\Models\Core\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BmnSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'room_id',
        'submitted_by',
        'verified_by',
        'bmn_item_id',
        'item_name',
        'bmn_code',
        'register_number',
        'quantity',
        'brand',
        'model',
        'serial_number',
        'acquisition_date',
        'acquisition_source',
        'condition',
        'responsible_name',
        'requires_decree',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'acquisition_date' => 'date',
            'condition' => ItemCondition::class,
            'requires_decree' => 'boolean',
            'status' => RequestStatus::class,
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'submitted_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'verified_by');
    }

    public function bmnItem(): BelongsTo
    {
        return $this->belongsTo(BmnItem::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function requestLogs(): MorphMany
    {
        return $this->morphMany(RequestLog::class, 'loggable');
    }
}
