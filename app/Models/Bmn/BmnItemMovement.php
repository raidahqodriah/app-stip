<?php

namespace App\Models\Bmn;

use App\Enums\BmnMovementSource;
use App\Models\Core\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BmnItemMovement extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'bmn_item_id',
        'from_room_id',
        'to_room_id',
        'source',
        'reason',
    ];

    protected function casts(): array
    {
        return [
            'source' => BmnMovementSource::class,
            'created_at' => 'datetime',
        ];
    }

    public function bmnItem(): BelongsTo
    {
        return $this->belongsTo(BmnItem::class);
    }

    public function fromRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'from_room_id');
    }

    public function toRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'to_room_id');
    }
}
