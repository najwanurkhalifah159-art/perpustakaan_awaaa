<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'member_name',
        'user_id',
        'loan_date',
        'return_date',
        'due_date',
        'fine',
        'status',
    ];


    protected $casts = [
        'loan_date' => 'date',
        'return_date' => 'date',
        'due_date' => 'date',
        'status' => 'string',
    ];


    /**
     * Relasi: Loan milik satu Book
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }



    /**
     * Relasi: Loan milik satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hitung denda otomatis (Rp 1.000 per hari keterlambatan)
     */
    public function calculateFine()
    {
        if ($this->return_date && $this->due_date) {
            $returnDate = Carbon::parse($this->return_date);
            $dueDate = Carbon::parse($this->due_date);

            if ($returnDate->greaterThan($dueDate)) {
                $lateDays = $returnDate->diffInDays($dueDate);
                return $lateDays * 1000; // Rp 1.000 per hari
            }
        }
        return 0;
    }

    /**
     * Cek apakah peminjaman masih aktif (belum dikembalikan)
     */
    public function isActive()
    {
        return is_null($this->return_date);
    }

    /**
     * Cek apakah peminjaman terlambat
     */
    public function isOverdue()
    {
        if ($this->isActive()) {
            return Carbon::now()->greaterThan($this->due_date);
        }
        return false;
    }
}
