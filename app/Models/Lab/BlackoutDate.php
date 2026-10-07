<?php

namespace App\Models\Lab;

use App\Models\Core\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlackoutDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'start_at',
        'end_at',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeForRoom(Builder $query, ?int $roomId): Builder
    {
        return $query->where(function (Builder $q) use ($roomId) {
            $q->whereNull('room_id');
            if ($roomId !== null) {
                $q->orWhere('room_id', $roomId);
            }
        });
    }
}
