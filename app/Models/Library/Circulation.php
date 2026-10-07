<?php

namespace App\Models\Library;

use App\Enums\CirculationStatus;
use App\Enums\ItemCondition;
use App\Models\Core\Employee;
use App\Models\Core\RequestLog;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Circulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'borrower_type',
        'borrower_id',
        'book_id',
        'loaned_by',
        'returned_by',
        'loan_date',
        'due_date',
        'return_date',
        'status',
        'return_condition',
        'late_days',
        'fine_amount',
        'fine_paid',
        'fine_paid_at',
        'officer_note',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'due_date' => 'date',
            'return_date' => 'date',
            'status' => CirculationStatus::class,
            'return_condition' => ItemCondition::class,
            'late_days' => 'integer',
            'fine_amount' => 'integer',
            'fine_paid' => 'boolean',
            'fine_paid_at' => 'datetime',
        ];
    }

    public function borrower(): MorphTo
    {
        return $this->morphTo();
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function loanOfficer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'loaned_by');
    }

    public function returnOfficer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'returned_by');
    }

    public function requestLogs(): MorphMany
    {
        return $this->morphMany(RequestLog::class, 'loggable');
    }

    public function isOverdue(): bool
    {
        return $this->status === CirculationStatus::Borrowed && $this->due_date->isPast();
    }
}
