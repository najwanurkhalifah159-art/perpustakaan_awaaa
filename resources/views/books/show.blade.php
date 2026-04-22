@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="mb-0">
                    <i class="bi bi-book me-2 text-primary"></i>{{ $book->title }}
                </h3>
                <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small mb-2">Judul</label>
                        <h5 class="mb-3">{{ $book->title }}</h5>
                        <label class="form-label fw-semibold text-muted small mb-2">Pengarang</label>
                        <p class="mb-2">{{ $book->author }}</p>
                        <label class="form-label fw-semibold text-muted small mb-2">Penerbit</label>
                        <p>{{ $book->publisher }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small mb-2">Tahun</label>
                        <span class="badge bg-secondary fs-6 mb-3 d-inline-block">{{ $book->year }}</span>
                        <label class="form-label fw-semibold text-muted small mb-2">Kategori</label>
                        @if($book->category)
                            <span class="badge bg-info mb-3 d-inline-block">{{ $book->category }}</span>
                        @else
                            <span class="badge bg-light text-dark mb-3 d-inline-block">Umum</span>
                        @endif
                        <label class="form-label fw-semibold text-muted small mb-2">Stok</label>
                        @if($book->stock > 0)
                            <span class="badge bg-success fs-5">{{ $book->stock }} Tersedia</span>
                        @else
                            <span class="badge bg-danger fs-5">Habis</span>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-md-4">
                        <small class="text-muted">Dibuat</small>
                        <p class="mb-0 fw-semibold">{{ $book->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Diperbarui</small>
                        <p class="mb-0 fw-semibold">{{ $book->updated_at->format('d M Y') }}</p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">ID</small>
                        <p class="mb-0 fw-semibold">#{{ $book->id }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($book->loans()->exists())
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-journal-text me-2"></i>Riwayat Peminjaman ({{ $book->loans()->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Anggota</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Tgl Kembali</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($book->loans()->latest()->take(10) as $loan)
                                    <tr>
                                        <td>{{ $loan->member->name ?? 'N/A' }}</td>
                                        <td>{{ $loan->loan_date->format('d M Y') }}</td>
                                        <td>
                                            @if($loan->return_date)
                                                {{ $loan->return_date->format('d M Y') }}
                                            @else
                                                <span class="badge bg-warning text-dark">Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($loan->return_date)
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-info">Dipinjam</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0 fw-semibold">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('Hapus {{ $book->title }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger" type="submit">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                    <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-list"></i> Daftar Buku
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

