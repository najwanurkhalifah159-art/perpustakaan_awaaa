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
        'cover_image_data',
        'cover_image_mime',
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
            if ($this->cover_image_data) {
                $mime = $this->cover_image_mime ?: 'image/jpeg';
                return 'data:' . $mime . ';base64,' . $this->cover_image_data;
            }

            return null;
        }

        if ($this->cover_image_data) {
            $mime = $this->cover_image_mime ?: 'image/jpeg';
            return 'data:' . $mime . ';base64,' . $this->cover_image_data;
        }

        $disk = $this->coverDisk();

        if (Storage::disk($disk)->exists($this->cover_image)) {
            return Storage::disk($disk)->url($this->cover_image);
        }

        return null;
    }

    private function coverDisk(): string
    {
        $disk = config('filesystems.default', 'public');

        return $disk === 'local' ? 'public' : $disk;
    }
}
