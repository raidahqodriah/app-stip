<?php

namespace App\Models\Lab;

use App\Models\Core\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'room_id',
        'material_id',
        'qty_per_participant',
        'qty_per_session',
    ];

    protected function casts(): array
    {
        return [
            'qty_per_participant' => 'decimal:2',
            'qty_per_session' => 'decimal:2',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
