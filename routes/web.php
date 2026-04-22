<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SimpleLoanController;

Route::get('/', function () {
    if (session('user')) {
        return redirect()->route('dashboard');
    }
    return view('login');
})->name('login');

Route::get('/daftar', function () {
    return view('register');
})->name('daftar');

Route::post('/daftar', [AuthController::class, 'register'])->name('register.process');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/buku', [SimpleLoanController::class, 'buku'])->name('buku'); // Admin CRUD\nRoute::get('/daftar-buku', [SimpleLoanController::class, 'userBooks'])->name('user.books'); // User view only
Route::get('/peminjaman', [SimpleLoanController::class, 'peminjaman'])->name('peminjaman');
Route::post('/peminjaman/store', [SimpleLoanController::class, 'storePinjam'])->name('peminjaman.store')->middleware('user');
Route::post('/books', [SimpleLoanController::class, 'storeBook'])->name('books.store')->middleware('admin');
Route::put('/books/{id}', [SimpleLoanController::class, 'updateBook'])->middleware('admin');
Route::delete('/books/{id}/delete', [SimpleLoanController::class, 'deleteBook'])->middleware('admin');
Route::get('/pengembalian', [SimpleLoanController::class, 'pengembalian'])->name('pengembalian');
Route::post('/pengembalian/process/{loan}', [SimpleLoanController::class, 'processKembali'])->name('pengembalian.process');
Route::post('/loans/{loan}/approve', [SimpleLoanController::class, 'approveLoan'])->name('loans.approve')->middleware('admin');
Route::post('/loans/{loan}/reject', [SimpleLoanController::class, 'rejectLoan'])->name('loans.reject')->middleware('admin');
Route::get('/admin/approvals', [SimpleLoanController::class, 'approvals'])->name('admin.approvals')->middleware('admin');
?>
