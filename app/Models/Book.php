<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\File;

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

        $relativePath = ltrim($this->cover_image, '/');
        $publicPath = public_path('storage/' . $relativePath);
        $storagePath = storage_path('app/public/' . $relativePath);

        // Jika file ada di storage aplikasi tetapi belum ada di public/storage,
        // salin sekali supaya browser bisa membacanya lewat /storage.
        if (!File::exists($publicPath) && File::exists($storagePath)) {
            $publicDir = dirname($publicPath);

            if (!File::isDirectory($publicDir)) {
                File::makeDirectory($publicDir, 0755, true, true);
            }

            File::copy($storagePath, $publicPath);
        }

        return File::exists($publicPath) ? '/storage/' . $relativePath : null;
    }
}
