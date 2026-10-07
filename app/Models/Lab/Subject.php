<?php

namespace App\Models\Lab;

use App\Enums\SubjectCategory;
use App\Models\Core\Room;
use App\Models\Core\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'code',
        'name',
        'category',
        'semester',
        'credits',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'category' => SubjectCategory::class,
            'semester' => 'integer',
            'credits' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function competences(): BelongsToMany
    {
        return $this->belongsToMany(Competence::class, 'competence_subject')->withTimestamps();
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_subject')->withTimestamps();
    }

    public function materialKits(): HasMany
    {
        return $this->hasMany(MaterialKit::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
