@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="mb-3">
            <i class="bi bi-house-door me-2 text-primary"></i>Selamat Datang di Perpustakaan
        </h1>
        <p class="lead text-muted">Kelola buku, anggota, dan peminjaman dengan mudah.</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-5 g-4">
    <div class="col-xl-3 col-md-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-book display-4 text-primary mb-3"></i>
                <h3 class="mb-1">{{ \App\Models\Book::count() }}</h3>
                <p class="text-muted mb-0">Total Buku</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-people display-4 text-success mb-3"></i>
                <h3 class="mb-1">{{ \App\Models\Member::count() }}</h3>
                <p class="text-muted mb-0">Total Anggota</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-journal-text display-4 text-warning mb-3"></i>
                <h3 class="mb-1">{{ \App\Models\Loan::whereNull('return_date')->count() }}</h3>
                <p class="text-muted mb-0">Peminjaman Aktif</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="bi bi-exclamation-triangle display-4 text-danger mb-3"></i>
                <h3 class="mb-1">{{ \App\Models\Loan::whereNull('return_date')->where('due_date', '<', now())->count() }}</h3>
                <p class="text-muted mb-0">Terlambat</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4">
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="bi bi-book display-4 text-primary mb-3"></i>
                <h5 class="card-title">Kelola Buku</h5>
                <p class="card-text text-muted">Lihat, tambah, edit koleksi buku</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary w-100 mb-2">Lihat Buku</a>
                <a href="{{ route('books.create') }}" class="btn btn-outline-primary w-100">Tambah Buku</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="bi bi-people display-4 text-success mb-3"></i>
                <h5 class="card-title">Kelola Anggota</h5>
                <p class="card-text text-muted">Daftar dan detail anggota</p>
                <a href="{{ route('members.index') }}" class="btn btn-success w-100 mb-2">Lihat Anggota</a>
                <a href="{{ route('members.create') }}" class="btn btn-outline-success w-100">Tambah Anggota</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6">
        <div class="card h-100 text-center">
            <div class="card-body">
                <i class="bi bi-journal-text display-4 text-warning mb-3"></i>
                <h5 class="card-title">Peminjaman</h5>
                <p class="card-text text-muted">Pinjam dan kembalikan buku</p>
                <a href="{{ route('loans.index') }}" class="btn btn-warning w-100 mb-2">Lihat Peminjaman</a>
                <a href="{{ route('loans.create') }}" class="btn btn-outline-warning w-100">Pinjam Baru</a>
            </div>
        </div>
    </div>
</div>
@endsection

