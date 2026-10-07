<?php

namespace App\Models\Lab;

use App\Enums\ItemCondition;
use App\Models\Core\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingRealization extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'recorded_by',
        'actual_start_at',
        'actual_end_at',
        'actual_participant_count',
        'condition_after',
        'incident_note',
    ];

    protected function casts(): array
    {
        return [
            'actual_start_at' => 'datetime',
            'actual_end_at' => 'datetime',
            'actual_participant_count' => 'integer',
            'condition_after' => ItemCondition::class,
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'recorded_by');
    }
}
