@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-1">
                    <i class="bi bi-book me-2 text-primary"></i>Daftar Buku
                </h2>
                <p class="text-muted mb-0">Kelola koleksi perpustakaan</p>
            </div>
            <a href="{{ route('books.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tambah Buku
            </a>
        </div>
    </div>
</div>

@if($books->isEmpty())
    <div class="text-center py-5">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <i class="bi bi-book display-1 text-muted mb-3"></i>
                <h4 class="text-muted mb-3">Belum ada buku</h4>
                <p class="text-muted mb-4">Tambahkan buku pertama ke perpustakaan Anda</p>
                <a href="{{ route('books.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Tambah Buku
                </a>
            </div>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $index => $book)
                            <tr>
                                <td>
                                    <strong>{{ $index + 1 }}</strong>
                                </td>
                                <td>
                                    <strong>{{ Str::limit($book->title, 30) }}</strong>
                                    @if(strlen($book->title) > 30) <br><small class="text-muted">{{ Str::substr($book->title, 30) }}</small> @endif
                                </td>
                                <td>{{ Str::limit($book->author, 20) }}</td>
                                <td>{{ Str::limit($book->publisher, 15) }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $book->year }}</span>
                                </td>
                                <td>
                                    @if($book->category)
                                        <span class="badge bg-info">{{ $book->category }}</span>
                                    @else
                                        <span class="badge bg-light text-dark">Umum</span>
                                    @endif
                                </td>
                                <td>
                                    @if($book->stock > 5)
                                        <span class="badge bg-success">{{ $book->stock }}</span>
                                    @elseif($book->stock > 0)
                                        <span class="badge bg-warning text-dark">{{ $book->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('books.show', $book) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus buku {{ $book->title }}?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if(method_exists($books, 'links'))
            <div class="card-footer bg-light border-top">
                {{ $books->links() }}
            </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white text-center">
                <div class="card-body">
                    <i class="bi bi-book display-6 mb-2 d-block"></i>
                    <h5>{{ count($books) }}</h5>
                    <small>Total Buku</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white text-center">
                <div class="card-body">
                    <i class="bi bi-check-circle display-6 mb-2 d-block"></i>
                    <h5>{{ $books->where('stock', '>', 0)->count() }}</h5>
                    <small>Tersedia</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark text-center">
                <div class="card-body">
                    <i class="bi bi-exclamation-triangle display-6 mb-2 d-block"></i>
                    <h5>{{ $books->where('stock', 0)->count() }}</h5>
                    <small>Habis</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white text-center">
                <div class="card-body">
                    <i class="bi bi-box display-6 mb-2 d-block"></i>
                    <h5>{{ $books->sum('stock') }}</h5>
                    <small>Total Stok</small>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

