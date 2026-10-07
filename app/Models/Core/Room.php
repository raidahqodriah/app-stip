<?php

namespace App\Models\Core;

use App\Enums\RoomType;
use App\Models\Bmn\BmnItem;
use App\Models\Bmn\BmnItemMovement;
use App\Models\Bmn\BmnReturn;
use App\Models\Bmn\BmnSubmission;
use App\Models\Lab\BlackoutDate;
use App\Models\Lab\Booking;
use App\Models\Lab\BookingSlot;
use App\Models\Lab\Material;
use App\Models\Lab\MaterialKit;
use App\Models\Lab\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'pic_employee_id',
        'code',
        'name',
        'type',
        'is_bookable',
        'lab_category',
        'capacity',
        'operating_hours',
        'location',
        'description',
        'photo_path',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => RoomType::class,
            'is_bookable' => 'boolean',
            'capacity' => 'integer',
            'operating_hours' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeBookable(Builder $query): Builder
    {
        return $query->where('is_bookable', true);
    }

    public function scopeLabs(Builder $query): Builder
    {
        return $query->where('type', RoomType::Lab);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function picEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'pic_employee_id');
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'room_subject')->withTimestamps();
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function materialKits(): HasMany
    {
        return $this->hasMany(MaterialKit::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bookingSlots(): HasMany
    {
        return $this->hasMany(BookingSlot::class);
    }

    public function blackoutDates(): HasMany
    {
        return $this->hasMany(BlackoutDate::class);
    }

    public function bmnItems(): HasMany
    {
        return $this->hasMany(BmnItem::class);
    }

    public function bmnSubmissions(): HasMany
    {
        return $this->hasMany(BmnSubmission::class);
    }

    public function bmnReturnsFrom(): HasMany
    {
        return $this->hasMany(BmnReturn::class, 'from_room_id');
    }

    public function bmnReturnsTo(): HasMany
    {
        return $this->hasMany(BmnReturn::class, 'destination_room_id');
    }

    public function movementsFrom(): HasMany
    {
        return $this->hasMany(BmnItemMovement::class, 'from_room_id');
    }

    public function movementsTo(): HasMany
    {
        return $this->hasMany(BmnItemMovement::class, 'to_room_id');
    }
}
