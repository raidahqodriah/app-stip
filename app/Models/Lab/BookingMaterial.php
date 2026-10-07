<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'material_id',
        'qty_requested',
        'qty_approved',
        'qty_used',
        'qty_returned',
        'condition_note',
    ];

    protected function casts(): array
    {
        return [
            'qty_requested' => 'decimal:2',
            'qty_approved' => 'decimal:2',
            'qty_used' => 'decimal:2',
            'qty_returned' => 'decimal:2',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
