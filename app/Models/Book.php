<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

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

        return Storage::disk($this->coverDisk())->url($this->cover_image);
    }

    private function coverDisk(): string
    {
        $disk = config('filesystems.default', 'public');

        return $disk === 'local' ? 'public' : $disk;
    }
}
