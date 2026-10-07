<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GeneratedDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_template_id',
        'documentable_type',
        'documentable_id',
        'document_number',
        'generator_type',
        'generator_id',
        'print_count',
    ];

    protected function casts(): array
    {
        return [
            'print_count' => 'integer',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(DocumentTemplate::class, 'document_template_id');
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function generator(): MorphTo
    {
        return $this->morphTo();
    }
}
