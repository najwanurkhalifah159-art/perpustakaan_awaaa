<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    private function coverDisk(): string
    {
        $disk = config('filesystems.default', 'public');

        return $disk === 'local' ? 'public' : $disk;
    }

    private function coverDataFromPath(string $path): array
    {
        $disk = Storage::disk('public');

        if (!$disk->exists($path)) {
            return [];
        }

        return [
            'cover_image_data' => base64_encode($disk->get($path)),
            'cover_image_mime' => $disk->mimeType($path) ?: 'image/jpeg',
        ];
    }

    public function run(): void
    {
        $seededCoverImages = $this->syncSeededCoverImages();

        // Admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('admin'),
                'role' => 'admin',
            ]
        );

        // Sample user
        User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'password' => bcrypt('user'),
                'role' => 'user',
            ]
        );


        if (Book::count() === 0) {
            // Books - User provided + extras
            $userBooks = [
                // History Of The World War
                ['title' => 'History Of The World War, Sejarah Perang Dunia', 'author' => 'Saut Pasaribu', 'publisher' => 'Alexander Books', 'year' => 2020, 'stock' => 5, 'category' => 'Sejarah'],
                // Nineteenth Century Questions
                ['title' => 'Nineteenth Century Questions Sejarah Dunia Abad Ke-19', 'author' => 'James Freeman Clarke', 'publisher' => 'Indoliterasi', 'year' => 2022, 'stock' => 8, 'category' => 'Sejarah'],
                // Menjadi Istri Bahagia
                ['title' => 'Menjadi Istri Bahagia dan Membahagiakan Keluarga', 'author' => 'Jumriyah, M.Pd.I.', 'publisher' => 'Silda Impika', 'year' => 2024, 'stock' => 10, 'category' => 'Keluarga'],
                // Learn English Practically
                ['title' => 'Learn English Practically', 'author' => 'Dionisius Hargen', 'publisher' => 'Silda Impika', 'year' => 2024, 'stock' => 7, 'category' => 'Bahasa'],
                // Adab Di Atas Ilmu 5
                ['title' => 'Adab Di Atas Ilmu 5', 'author' => 'Syekh Ibnu Jama\'ah', 'publisher' => 'Diva Press', 'year' => 2024, 'stock' => 6, 'category' => 'Agama'],
                // Keutamaan Umat Nabi Muhammad Saw
                ['title' => 'Keutamaan-Keutamaan Umat Nabi Muhammad Saw', 'author' => 'Sayyid Muhammad Alawi Al-Maliki', 'publisher' => 'Diva Press', 'year' => 2022, 'stock' => 12, 'category' => 'Agama'],
                // Bangun Brandmu Sendiri
                ['title' => 'Bangun Brandmu Sendiri; Strategi Jitu Membangun Brand di Era Digital', 'author' => 'Galih F.H.', 'publisher' => 'Silda Impika', 'year' => 2024, 'stock' => 9, 'category' => 'Bisnis'],
                // Seni Mengatur Keuangan
                ['title' => 'Seni Mengatur Keuangan; Bebas Finansial di Usia Muda', 'author' => 'G. S. Perkasa', 'publisher' => 'Silda Impika', 'year' => 2024, 'stock' => 11, 'category' => 'Keuangan'],
                // 20 Peristiwa Penting
                ['title' => '20 Peristiwa Penting yang Mengubah Dunia', 'author' => 'Toha Amar', 'publisher' => 'Silda Impika', 'year' => 2024, 'stock' => 8, 'category' => 'Sejarah'],
                // Jejak Festival
                ['title' => 'Jejak Festival; Menyusuri Tradisi dan Kemeriahan Dunia', 'author' => 'E. Kasiana', 'publisher' => 'Tirta Buana Media', 'year' => 2025, 'stock' => 4, 'category' => 'Budaya'],
                // Suara Gen Z
                ['title' => 'Suara Gen Z Suara Ilahi', 'author' => 'Tim Society 5.0', 'publisher' => 'Silda Impika', 'year' => 2024, 'stock' => 15, 'category' => 'Motivasi'],
                // Buku Pintar Berorganisasi
                ['title' => 'Buku Pintar Berorganisasi untuk Remaja', 'author' => 'T. Utami', 'publisher' => 'Gema Buku', 'year' => 2025, 'stock' => 6, 'category' => 'Pendidikan'],
            ];

            foreach ($userBooks as $index => $data) {
                if (isset($seededCoverImages[$index])) {
                    $data['cover_image'] = $seededCoverImages[$index];
                    $data = array_merge($data, $this->coverDataFromPath($seededCoverImages[$index]));
                }

                Book::create($data);
            }

            // Additional sample books
            $extraBooks = [
                ['title' => 'Laravel Documentation', 'author' => 'Taylor Otwell', 'publisher' => 'Laravel LLC', 'year' => 2024, 'stock' => 3, 'category' => 'Teknologi'],
                ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'publisher' => 'Prentice Hall', 'year' => 2008, 'stock' => 5, 'category' => 'Teknologi'],
            ];

            foreach ($extraBooks as $index => $data) {
                $coverIndex = count($userBooks) + $index;

                if (isset($seededCoverImages[$coverIndex])) {
                    $data['cover_image'] = $seededCoverImages[$coverIndex];
                    $data = array_merge($data, $this->coverDataFromPath($seededCoverImages[$coverIndex]));
                }

                Book::create($data);
            }
        } else {
            $this->backfillBookCoverImages($seededCoverImages);
        }

        if (Loan::count() === 0) {
            // Sample Loans (after books & members exist)
            $books = Book::all();
            $fakeMembers = ['Ahmad Rahman', 'Siti Nurhaliza', 'Budi Santoso', 'Indah Permata', 'Rudi Hartono'];
            // Manual sample loans
            for ($i = 0; $i < 5; $i++) {
                Loan::create([
                    'book_id' => $books->random()->id,
                    'member_name' => $fakeMembers[array_rand($fakeMembers)],
                    'user_id' => 2, // sample user
                    'loan_date' => now()->subDays(rand(0, 10)),
                    'due_date' => now()->addDays(rand(3, 14)),
                    'status' => rand(0,1) ? 'pending' : 'approved',
                ]);
            }
        }
    }

    /**
     * Ambil cover gambar contoh yang sudah dibundel di storage lokal,
     * lalu salin ke disk cover yang aktif supaya file bertahan di cloud.
     */
    private function syncSeededCoverImages(): array
    {
        $seededCoverImages = collect(Storage::disk('public')->files('books'))
            ->filter(fn (string $path) => preg_match('/\.(jpg|jpeg|png|webp)$/i', $path))
            ->values();

        foreach ($seededCoverImages as $path) {
            if (!Storage::disk($this->coverDisk())->exists($path)) {
                Storage::disk($this->coverDisk())->put($path, Storage::disk('public')->get($path));
            }
        }

        return $seededCoverImages->all();
    }

    /**
     * Isi cover yang kosong atau file cover-nya hilang dengan cover contoh.
     */
    private function backfillBookCoverImages(array $seededCoverImages): void
    {
        $books = Book::orderBy('id')->get();

        foreach ($books as $index => $book) {
            $coverImage = $book->cover_image;
            $coverExists = $coverImage && Storage::disk($this->coverDisk())->exists($coverImage);

            if ($coverExists || !isset($seededCoverImages[$index])) {
                continue;
            }

            $newCover = $seededCoverImages[$index];

            if (!Storage::disk($this->coverDisk())->exists($newCover)) {
                Storage::disk($this->coverDisk())->put($newCover, Storage::disk('public')->get($newCover));
            }

            $book->update([
                'cover_image' => $newCover,
                ...$this->coverDataFromPath($newCover),
            ]);
        }
    }
}
