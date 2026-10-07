<?php

namespace App\Models\Bmn;

use App\Enums\RequestStatus;
use App\Models\Core\Unit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OfficialResidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'house_number',
        'address',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function permits(): HasMany
    {
        return $this->hasMany(ResidencePermit::class);
    }

    public function currentPermit(): HasOne
    {
        return $this->hasOne(ResidencePermit::class)
            ->where('status', RequestStatus::Approved)
            ->latestOfMany();
    }
}
