<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'publisher',
        'year',
        'stock',
        'category',
        'cover_image',
    ];

    protected $appends = [
        'cover_image_url',
    ];

    /**
     * Relasi: Book memiliki banyak Loan
     */
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }

        return '/storage/' . ltrim($this->cover_image, '/');
    }
}
