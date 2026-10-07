<?php

namespace App\Models\Lab;

use App\Enums\MaterialType;
use App\Models\Bmn\BmnItem;
use App\Models\Core\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'bmn_item_id',
        'name',
        'type',
        'unit',
        'stock_qty',
        'min_stock',
    ];

    protected function casts(): array
    {
        return [
            'type' => MaterialType::class,
            'stock_qty' => 'decimal:2',
            'min_stock' => 'decimal:2',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function bmnItem(): BelongsTo
    {
        return $this->belongsTo(BmnItem::class);
    }

    public function materialKits(): HasMany
    {
        return $this->hasMany(MaterialKit::class);
    }

    public function bookingMaterials(): HasMany
    {
        return $this->hasMany(BookingMaterial::class);
    }
}
