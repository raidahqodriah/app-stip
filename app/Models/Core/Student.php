<?php

namespace App\Models\Core;

use App\Models\Lab\Booking;
use App\Models\Library\Circulation;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'unit_id',
        'name',
        'email',
        'password',
        'student_number',
        'batch_year',
        'class_group',
        'phone',
        'is_active',
        'theme_color',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'batch_year' => 'integer',
            'is_active' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'student' && $this->is_active;
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function bookingsRequested(): MorphMany
    {
        return $this->morphMany(Booking::class, 'requester');
    }

    public function circulations(): MorphMany
    {
        return $this->morphMany(Circulation::class, 'borrower');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'uploader');
    }

    public function requestLogs(): MorphMany
    {
        return $this->morphMany(RequestLog::class, 'actor');
    }

    public function generatedDocuments(): MorphMany
    {
        return $this->morphMany(GeneratedDocument::class, 'generator');
    }
}
