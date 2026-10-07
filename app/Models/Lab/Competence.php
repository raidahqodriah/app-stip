<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Competence extends Model
{
    use HasFactory;

    protected $fillable = [
        'imo_model_course_id',
        'code',
        'title',
        'stcw_reference',
    ];

    public function imoModelCourse(): BelongsTo
    {
        return $this->belongsTo(ImoModelCourse::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'competence_subject')->withTimestamps();
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_competence')->withTimestamps();
    }
}
