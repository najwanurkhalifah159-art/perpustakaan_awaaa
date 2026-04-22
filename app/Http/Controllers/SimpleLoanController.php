<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SimpleLoanController extends Controller
{
    private function coverDisk(): string
    {
        $disk = config('filesystems.default', 'public');

        return $disk === 'local' ? 'public' : $disk;
    }

    public function buku()
    {
        $books = Book::all();
        $isAdmin = session()->has('user') && session('user.role') === 'admin';
        return view('buku', compact('books', 'isAdmin'));
    }

    public function updateBook(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        if (!session()->has('user') || session('user.role') !== 'admin') {
            return redirect('/buku')->with('error', 'Hanya admin!');
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:2100',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $payload = $request->only(['title', 'author', 'publisher', 'year', 'stock', 'category']);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk($this->coverDisk())->delete($book->cover_image);
            }

            $payload['cover_image'] = $request->file('cover_image')->store('books', $this->coverDisk());
        }

        $book->update($payload);
        return redirect('/buku')->with('success', 'Buku berhasil diupdate!');
    }

    public function storeBook(Request $request)
    {
        if (!session()->has('user') || session('user.role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Hanya admin yang bisa menambah buku!');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:2100',
            'stock' => 'required|integer|min:1|max:100',
            'category' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $payload = $request->only(['title', 'author', 'publisher', 'year', 'stock', 'category']);

        if ($request->hasFile('cover_image')) {
            $payload['cover_image'] = $request->file('cover_image')->store('books', $this->coverDisk());
        }

        Book::create($payload);
        return redirect('/buku')->with('success', 'Buku baru ditambahkan!');
    }

    public function peminjaman()
    {
        if (!session()->has('user') || session('user.role') !== 'user') {
            return redirect('/dashboard')->with('error', 'Hanya user!');
        }
        $books = Book::where('stock', '>', 0)->orderBy('title')->get(['id', 'title']);
        return view('peminjaman', compact('books'));
    }

    public function storePinjam(Request $request)
    {
        if (!session()->has('user') || session('user.role') !== 'user') {
            return redirect('/dashboard')->with('error', 'Hanya user yang bisa meminjam!');
        }

        $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_name' => 'required|string|max:255',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after:loan_date',
        ]);

        $userId = session('user.id');

        Loan::create([
            'book_id' => $request->book_id,
            'member_name' => $request->member_name,
            'user_id' => $userId,
            'loan_date' => $request->loan_date,
            'due_date' => $request->due_date,
            'status' => 'pending',
        ]);

        return redirect('/dashboard')->with('success', 'Permintaan peminjaman dikirim! Menunggu persetujuan admin.');
    }

    public function pengembalian()
    {
        if (!session()->has('user')) {
            return redirect('/dashboard')->with('error', 'Login dulu!');
        }
        $userRole = session('user.role');
        $userId = session('user.id');
        
        $activeLoans = Loan::with('book')->whereNull('return_date')
            ->where('status', 'approved')
            ->when($userRole === 'user', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->latest()->get();
        
        return view('pengembalian', compact('activeLoans'));
    }

    public function approvals()
    {
        if (!session()->has('user') || session('user.role') !== 'admin') {
            return redirect('/dashboard')->with('error', 'Hanya admin!');
        }
        
        $pendingLoans = Loan::with(['book', 'user'])->where('status', 'pending')->latest()->get();
        
        return view('admin.approvals', compact('pendingLoans'));
    }

    public function processKembali(Request $request, Loan $loan)
    {
        if (!session()->has('user') || session('user.role') !== 'user') {
            return redirect('/dashboard')->with('error', 'Hanya user yang bisa mengembalikan!');
        }

        if ($loan->status !== 'approved' || $loan->return_date) {
            return redirect('/dashboard')->with('error', 'Peminjaman tidak dapat dikembalikan!');
        }

        $request->validate([
            'return_date' => 'required|date|after_or_equal:loan_date',
        ]);

        $lateDays = $loan->due_date->diffInDays(now()) > 0 ? $loan->due_date->diffInDays(now()) : 0;
        $fine = $lateDays * 1000;

        $loan->update([
            'return_date' => $request->return_date,
            'fine' => $fine,
            'status' => 'returned',
        ]);

        $loan->book->increment('stock');

        return redirect('/dashboard')->with('success', "Buku berhasil dikembalikan. Denda: Rp " . number_format($fine));
    }

    public function approveLoan(Request $request, Loan $loan)
    {
        if (!session()->has('user') || session('user.role') !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin!');
        }

        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Peminjaman tidak pending!');
        }

        $loan->update(['status' => 'approved']);
        $loan->book->decrement('stock');

        return redirect()->back()->with('success', 'Peminjaman disetujui!');
    }

    public function rejectLoan(Request $request, Loan $loan)
    {
        if (!session()->has('user') || session('user.role') !== 'admin') {
            return redirect()->back()->with('error', 'Hanya admin!');
        }

        if ($loan->status !== 'pending') {
            return redirect()->back()->with('error', 'Peminjaman tidak pending!');
        }

        $loan->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Peminjaman ditolak.');
    }

    public function deleteBook($id)
    {
        if (!session()->has('user') || session('user.role') !== 'admin') {
            return redirect('/buku')->with('error', 'Hanya admin!');
        }

        $book = Book::findOrFail($id);

        // Check for active loans (pending or approved)
        $activeLoans = Loan::where('book_id', $id)
            ->whereNotIn('status', ['returned', 'rejected'])
            ->exists();

        if ($activeLoans) {
            return redirect('/buku')->with('error', 'Tidak bisa hapus buku dengan peminjaman aktif!');
        }

        if ($book->cover_image) {
            Storage::disk($this->coverDisk())->delete($book->cover_image);
        }

        $book->delete();

        return redirect('/buku')->with('success', 'Buku berhasil dihapus!');
    }
}

