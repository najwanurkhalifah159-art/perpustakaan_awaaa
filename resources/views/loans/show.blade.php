@extends('layouts.app')

@section('title', 'Detail Peminjaman #' . $loan->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="bi bi-journal-text me-2 text-warning"></i>Peminjaman #{{ $loan->id }}
                </h3>
                <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-muted mb-3">Buku</h6>
                        <h5>{{ $loan->book->title }}</h5>
                        <p class="mb-1"><strong>{{ $loan->book->author }}</strong></p>
                        <p class="text-muted mb-0">{{ $loan->book->publisher }} • {{ $loan->book->year }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-semibold text-muted mb-3">Anggota</h6>
                        <h5>{{ $loan->member->name }}</h5>
                        <p class="mb-1"><i class="bi bi-telephone me-1"></i>{{ $loan->member->phone }}</p>
                        <p class="text-muted mb-0">{{ Str::limit($loan->member->address, 50) }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <label class="fw-semibold text-muted small">Tgl Pinjam</label>
                        <h6>{{ $loan->loan_date->format('d M Y') }}</h6>
                    </div>
                    <div class="col-md-3">
                        <label class="fw-semibold text-muted small">Batas Kembali</label>
                        <h6 class="{{ $loan->due_date < now()->format('Y-m-d') ? 'text-danger' : '' }}">
                            {{ $loan->due_date->format('d M Y') }}
                        </h6>
                    </div>
                    @if($loan->return_date)
                        <div class="col-md-3">
                            <label class="fw-semibold text-muted small">Tgl Dikembalikan</label>
                            <h6 class="text-success">{{ $loan->return_date->format('d M Y') }}</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="fw-semibold text-muted small">Denda</label>
                            <h6 class="{{ $loan->fine > 0 ? 'text-danger' : 'text-success' }}">
                                {{ $loan->fine > 0 ? 'Rp ' . number_format($loan->fine) : 'Gratis' }}
                            </h6>
                        </div>
                    @endif
                </div>
                <hr class="my-4">
                <div class="row">
                    <div class="col-md-4">
                        <label class="fw-semibold text-muted small">Status</label>
                        @if($loan->return_date)
                            <span class="badge bg-success fs-5 px-3 py-2">Selesai</span>
                        @elseif($loan->due_date < now()->format('Y-m-d'))
                            <span class="badge bg-danger fs-5 px-3 py-2">Terlambat</span>
                        @else
                            <span class="badge bg-warning text-dark fs-5 px-3 py-2">Aktif</span>
                        @endif
                    </div>
                    <div class="col-md-8 text-md-end">
                        @if(!$loan->return_date)
                            <a href="{{ route('loans.returnForm', $loan) }}" class="btn btn-success me-2">
                                <i class="bi bi-arrow-return-left me-1"></i>Kembalikan
                            </a>
                        @endif
                        <form action="{{ route('loans.destroy', $loan) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Hapus peminjaman ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

