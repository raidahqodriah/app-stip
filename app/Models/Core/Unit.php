<?php

namespace App\Models\Core;

use App\Enums\UnitType;
use App\Models\Bmn\BmnItem;
use App\Models\Bmn\BmnSubmission;
use App\Models\Bmn\OfficialResidence;
use App\Models\Bmn\ResidencePermit;
use App\Models\Lab\Booking;
use App\Models\Lab\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'type',
        'head_employee_id',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => UnitType::class,
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Unit::class, 'parent_id');
    }

    public function headEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'head_employee_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function bmnItems(): HasMany
    {
        return $this->hasMany(BmnItem::class);
    }

    public function bmnSubmissions(): HasMany
    {
        return $this->hasMany(BmnSubmission::class);
    }

    public function officialResidences(): HasMany
    {
        return $this->hasMany(OfficialResidence::class);
    }

    public function residencePermits(): HasMany
    {
        return $this->hasMany(ResidencePermit::class);
    }
}
