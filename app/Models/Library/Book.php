<?php

namespace App\Models\Library;

use App\Enums\CirculationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_code',
        'isbn',
        'title',
        'author',
        'publisher',
        'year',
        'category',
        'shelf',
        'total_stock',
        'available_stock',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'total_stock' => 'integer',
            'available_stock' => 'integer',
        ];
    }

    public function circulations(): HasMany
    {
        return $this->hasMany(Circulation::class);
    }

    public function activeCirculations(): HasMany
    {
        return $this->hasMany(Circulation::class)->where('status', CirculationStatus::Borrowed);
    }

    public function isAvailable(): bool
    {
        return $this->available_stock > 0;
    }
}
