<?php

namespace App\Models\Lab;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ImoModelCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'edition_year',
    ];

    protected function casts(): array
    {
        return [
            'edition_year' => 'integer',
        ];
    }

    public function competences(): HasMany
    {
        return $this->hasMany(Competence::class);
    }
}
