@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2 text-success"></i>Peminjaman Buku Baru
                </h5>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('peminjaman.store') }}">
                    @csrf
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Buku <span class="text-danger">*</span></label>
                            <select class="form-select @error('book_id') is-invalid @enderror" name="book_id" required>
                                <option value="">Pilih Buku Tersedia</option>
                                @foreach(\App\Models\Book::where('stock', '>', 0)->orderBy('title')->get() as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }} ({{ $book->stock }} sisa)</option>
                                @endforeach
                            </select>
                            @error('book_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nama Anggota <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('member_name') is-invalid @enderror" 
                                   name="member_name" value="{{ old('member_name') }}" placeholder="Tulis nama lengkap" required>
                            @error('member_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tgl Pinjam <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('loan_date') is-invalid @enderror" 
                                   name="loan_date" value="{{ old('loan_date', date('Y-m-d')) }}" required>
                            @error('loan_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Batas Kembali <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+7 days'))) }}" required>
                            @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <small><i class="bi bi-info-circle me-1"></i>Denda Rp1.000/hari keterlambatan</small>
                    </div>
                    <div class="d-grid gap-2 d-md-flex">
<button type="submit" class="btn btn-pink-blue-custom flex-fill btn-lg shadow-lg">
                            <i class="bi bi-hand-thumbs-up-fill me-2 fs-5"></i>
                            <span class="fw-bold">Pinjam Buku</span>
                        </button>
                        <a href="/dashboard" class="btn btn-outline-secondary btn-lg">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">
                    <i class="bi bi-plus-square me-2"></i>Tambah Buku Baru
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="/books">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small mb-2">Judul Buku</label>
                        <input type="text" class="form-control form-control-sm" name="title" placeholder="Judul" required>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <input type="text" class="form-control form-control-sm" name="author" placeholder="Pengarang" required>
                        </div>
                        <div class="col-6">
                            <input type="text" class="form-control form-control-sm" name="publisher" placeholder="Penerbit" required>
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <input type="number" class="form-control form-control-sm" name="year" placeholder="Tahun" required min="1900">
                        </div>
                        <div class="col-6">
                            <input type="number" class="form-control form-control-sm" name="stock" value="1" min="1" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-plus-circle me-1"></i>Tambah & Pinjam
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

