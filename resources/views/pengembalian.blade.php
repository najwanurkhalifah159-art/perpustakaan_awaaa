@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
<h2 class="mb-1 fw-bold">
                    <i class="bi bi-arrow-return-right-circle me-3 text-blue-primary"></i>
                    Pengembalian Buku
                </h2>
                <p class="text-muted mb-0">Kembalikan buku tepat waktu untuk menghindari denda</p>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

@php 
    $userId = session('user.id');
    $activeLoans = \App\Models\Loan::with('book', 'user')->whereNull('return_date')
        ->where('status', 'approved')
        ->where('user_id', $userId)
        ->latest()->get(); 
@endphp

                @if($activeLoans->isEmpty())
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle display-4 text-success mb-3"></i>
                        <h4 class="text-muted mb-4">Tidak ada peminjaman aktif</h4>
                        <a href="/dashboard" class="btn btn-primary">
                            Kembali ke Dashboard
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Buku</th>
                                    <th>Anggota</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Batas</th>
                                    <th>Denda</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($activeLoans as $index => $loan)
                                    <tr class="{{ $loan->due_date < now() ? 'table-warning' : '' }}">
                                        <td><strong>{{ $index + 1 }}</strong></td>
                                        <td>{{ Str::limit($loan->book->title ?? 'Unknown', 30) }}</td>
                                        <td>{{ $loan->member_name ?? 'Manual' }}</td>
                                        <td>{{ $loan->loan_date->format('d M Y') }}</td>
                                        <td class="{{ $loan->due_date < now() ? 'text-danger fw-bold' : '' }}">
                                            {{ $loan->due_date->format('d M Y') }}
                                        </td>
                                        <td>
                                            @if($loan->due_date < now())
                                                <span class="badge bg-danger">
                                                    Rp {{ number_format(now()->diffInDays($loan->due_date) * 1000) }}
                                                </span>
                                            @else
                                                <span class="badge bg-success">Rp 0</span>
                                            @endif
                                        </td>
                                        <td>
                                            <form method="POST" action="{{ route('pengembalian.process', $loan->id) }}" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="return_date" value="{{ now()->format('Y-m-d') }}">
                                                <button type="submit" class="btn btn-pink-blue-custom px-4 py-2 fw-bold shadow-lg" data-aos="pulse">
                                                    <i class="bi bi-check-circle-fill me-2"></i>
                                                    Kembalikan Sekarang
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <a href="/dashboard" class="btn btn-outline-secondary">
                        <i class="bi bi-house me-1"></i>Kembali Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

