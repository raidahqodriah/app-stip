<?php

namespace App\Models\Bmn;

use App\Enums\ItemCondition;
use App\Enums\RequestStatus;
use App\Models\Core\Attachment;
use App\Models\Core\Employee;
use App\Models\Core\RequestLog;
use App\Models\Core\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BmnReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'bmn_item_id',
        'requested_by',
        'verified_by',
        'from_room_id',
        'destination_room_id',
        'return_date',
        'reason',
        'condition',
        'damage_note',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'return_date' => 'date',
            'condition' => ItemCondition::class,
            'status' => RequestStatus::class,
        ];
    }

    public function bmnItem(): BelongsTo
    {
        return $this->belongsTo(BmnItem::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'requested_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'verified_by');
    }

    public function fromRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'from_room_id');
    }

    public function destinationRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'destination_room_id');
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
