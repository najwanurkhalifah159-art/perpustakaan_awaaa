@extends('layouts.app')

@section('title', 'Daftar Buku')
@section('page-title', 'Koleksi Buku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h2 class="mb-1 fw-bold">
            <i class="fas fa-book-open me-2 text-pink-primary"></i>
            Rak Buku Koleksi Utama
        </h2>
        <p class="text-muted mb-0">Jelajahi koleksi buku dan kelola cover buku dari satu halaman.</p>
    </div>

    @if(isset($isAdmin) && $isAdmin)
        <button class="btn btn-pink-blue-custom" data-bs-toggle="modal" data-bs-target="#addBookModal">
            <i class="bi bi-plus-circle me-2"></i>Tambah Buku Baru
        </button>
    @endif
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-anim mb-0">
                <thead class="table-dark">
                    <tr>
                        <th class="border-0">#</th>
                        <th class="border-0">Cover</th>
                        <th class="border-0">Judul Buku</th>
                        <th class="border-0">Pengarang</th>
                        <th class="border-0">Penerbit</th>
                        <th class="border-0">Tahun</th>
                        <th class="border-0">Stok</th>
                        <th class="border-0">Kategori</th>
                        @if(isset($isAdmin) && $isAdmin)
                            <th class="border-0">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($books as $index => $book)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                @if($book->cover_image_url)
                                    <img src="{{ $book->cover_image_url }}" alt="Cover {{ $book->title }}" style="width:56px; height:78px; object-fit:cover; border-radius:10px; border:1px solid #d9e2ec;">
                                @else
                                    <div class="d-inline-flex align-items-center justify-content-center text-muted small" style="width:56px; height:78px; border-radius:10px; background:#eef5f7; border:1px dashed #cbd5e0;">
                                        No Cover
                                    </div>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $book->title }}</strong>
                            </td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->publisher }}</td>
                            <td>{{ $book->year }}</td>
                            <td>
                                @if($book->stock > 0)
                                    <span class="badge badge-status badge-approved fs-6 px-3 py-2 shadow-sm">{{ $book->stock }} Tersedia</span>
                                @else
                                    <span class="badge badge-status badge-rejected fs-6 px-3 py-2 shadow-sm">Habis</span>
                                @endif
                            </td>
                            <td>{{ $book->category ?? 'Umum' }}</td>
                            @if(isset($isAdmin) && $isAdmin)
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editBook{{ $book->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form method="POST" action="/books/{{ $book->id }}/delete" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus buku?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>

                        @if(isset($isAdmin) && $isAdmin)
                            <div class="modal fade" id="editBook{{ $book->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit {{ $book->title }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST" action="/books/{{ $book->id }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Cover Buku</label>
                                                    @if($book->cover_image_url)
                                                        <div class="mb-2">
                                                            <img src="{{ $book->cover_image_url }}" alt="Cover {{ $book->title }}" style="width:84px; height:116px; object-fit:cover; border-radius:12px; border:1px solid #d9e2ec;">
                                                        </div>
                                                    @endif
                                                    <input type="file" class="form-control" name="cover_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti cover.</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Judul</label>
                                                    <input type="text" class="form-control" name="title" value="{{ $book->title }}" required>
                                                </div>
                                                <div class="row g-2">
                                                    <div class="col">
                                                        <input type="text" class="form-control" name="author" value="{{ $book->author }}" placeholder="Pengarang" required>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control" name="publisher" value="{{ $book->publisher }}" placeholder="Penerbit" required>
                                                    </div>
                                                </div>
                                                <div class="row g-2 mt-2">
                                                    <div class="col">
                                                        <input type="number" class="form-control" name="year" value="{{ $book->year }}" placeholder="Tahun" required>
                                                    </div>
                                                    <div class="col">
                                                        <input type="number" class="form-control" name="stock" value="{{ $book->stock }}" min="0" required>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <input type="text" class="form-control" name="category" value="{{ $book->category }}" placeholder="Kategori (opsional)">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Update Buku</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="{{ (isset($isAdmin) && $isAdmin) ? 9 : 8 }}">
                                <div class="empty-state">
                                    <i class="bi bi-journal-x fs-1 d-block mb-2"></i>
                                    Belum ada buku yang ditambahkan.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(isset($isAdmin) && $isAdmin)
    <div class="modal fade" id="addBookModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content card-glass shadow-pink-glow">
                <div class="modal-header border-0 pb-4">
                    <h5 class="modal-title fs-4 fw-bold text-pink-blue">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Buku Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/books" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cover Buku</label>
                            <input type="file" class="form-control" name="cover_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                            <small class="text-muted">Format JPG, PNG, atau WebP. Maksimal 2 MB.</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Judul Buku</label>
                            <input type="text" class="form-control shadow-sm" name="title" required autofocus>
                        </div>
                        <div class="row g-2">
                            <div class="col">
                                <input type="text" class="form-control" name="author" placeholder="Pengarang" required>
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" name="publisher" placeholder="Penerbit" required>
                            </div>
                        </div>
                        <div class="row g-2 mt-2">
                            <div class="col">
                                <input type="number" class="form-control" name="year" placeholder="Tahun" required>
                            </div>
                            <div class="col">
                                <input type="number" class="form-control" name="stock" value="1" min="1" required>
                            </div>
                        </div>
                        <div class="mt-2">
                            <input type="text" class="form-control" name="category" placeholder="Kategori (opsional)">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Tambah Buku</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<a href="/dashboard" class="btn btn-outline-secondary mt-3">
    <i class="bi bi-house"></i> Dashboard
</a>
@endsection
