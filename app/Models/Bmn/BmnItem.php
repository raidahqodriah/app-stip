<?php

namespace App\Models\Bmn;

use App\Enums\BmnItemStatus;
use App\Enums\ItemCondition;
use App\Models\Core\Attachment;
use App\Models\Core\Employee;
use App\Models\Core\Room;
use App\Models\Core\Unit;
use App\Models\Lab\Material;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class BmnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'bmn_code',
        'register_number',
        'item_name',
        'quantity',
        'brand',
        'model',
        'serial_number',
        'unit_id',
        'room_id',
        'responsible_employee_id',
        'responsible_name',
        'acquisition_date',
        'acquisition_source',
        'condition',
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
            'status' => BmnItemStatus::class,
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

    public function responsibleEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'responsible_employee_id');
    }

    public function returns(): HasMany
    {
        return $this->hasMany(BmnReturn::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(BmnItemMovement::class);
    }

    public function submission(): HasOne
    {
        return $this->hasOne(BmnSubmission::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
