@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-1">
                    <i class="bi bi-journal-text me-2 text-warning"></i>Daftar Peminjaman
                </h2>
                <p class="text-muted mb-0">Pantau semua transaksi peminjaman</p>
            </div>
            <a href="{{ route('loans.create') }}" class="btn btn-warning">
                <i class="bi bi-plus-circle me-2"></i>Pinjam Baru
            </a>
        </div>
    </div>
</div>

@if($loans->isEmpty())
    <div class="text-center py-5">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <i class="bi bi-journal-text display-1 text-muted mb-3"></i>
                <h4 class="text-muted mb-3">Belum ada peminjaman</h4>
                <p class="text-muted mb-4">Mulai peminjaman buku pertama</p>
                <a href="{{ route('loans.create') }}" class="btn btn-warning btn-lg">
                    <i class="bi bi-plus-circle me-2"></i>Pinjam Buku
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
                            <th>Buku</th>
                            <th>Anggota</th>
                            <th>Tanggal Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Status</th>
                            <th>Denda</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
@foreach($loans as $index => $loan)
                            <tr class="{{ $loan->return_date ? '' : ($loan->due_date < now() ? 'table-danger' : 'table-warning') }}">
                                <td><strong>{{ ($loans->firstItem() ?? 0) + $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ Str::limit($loan->book->title, 25) }}</strong>
                                    <small class="d-block text-muted">{{ $loan->book->author }}</small>
                                </td>
                                <td>{{ Str::limit($loan->member->name, 20) }}</td>
                                <td>{{ $loan->loan_date->format('d M Y') }}</td>
                                <td>
                                    <span class="badge {{ $loan->due_date < now() ? 'bg-danger' : 'bg-info' }}">
                                        {{ $loan->due_date->format('d M Y') }}
                                    </span>
                                </td>
                                <td>
                                    @if($loan->return_date)
                                        <span class="badge bg-success">Selesai</span>
                                    @elseif($loan->due_date < now())
                                        <span class="badge bg-danger">Terlambat</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    @if($loan->fine > 0)
                                        <span class="badge bg-danger">Rp {{ number_format($loan->fine) }}</span>
                                    @else
                                        <span class="text-success">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('loans.show', $loan) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(!$loan->return_date)
                                            <a href="{{ route('loans.returnForm', $loan) }}" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-arrow-return-left"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Hapus peminjaman ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
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
        @if($loans->hasPages())
            <div class="card-footer bg-light">
                {{ $loans->links() }}
            </div>
        @endif
    </div>

    <!-- Stats -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-warning text-dark text-center">
                <div class="card-body">
                    <i class="bi bi-journal-text display-6 mb-2"></i>
                    <h5>{{ $loans->total() }}</h5>
                    <small>Total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white text-center">
                <div class="card-body">
                    <i class="bi bi-clock display-6 mb-2"></i>
                    <h5>{{ $loans->whereNull('return_date')->count() }}</h5>
                    <small>Aktif</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white text-center">
                <div class="card-body">
                    <i class="bi bi-exclamation-triangle display-6 mb-2"></i>
                    <h5>{{ $loans->whereNull('return_date')->where('due_date', '<', now())->count() }}</h5>
                    <small>Terlambat</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white text-center">
                <div class="card-body">
                    <i class="bi bi-currency-rupee display-6 mb-2"></i>
                    <h5>Rp {{ number_format($loans->sum('fine')) }}</h5>
                    <small>Denda</small>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

