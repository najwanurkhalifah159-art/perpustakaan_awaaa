@extends('layouts.app')

@section('title', 'Persetujuan Peminjaman')
@section('page-title', 'Persetujuan Peminjaman')

@section('content')
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="section-label mb-2">Admin</div>
                <h2 class="mb-1 fw-bold">Persetujuan Peminjaman</h2>
                <p class="text-muted mb-0">{{ $pendingLoans->count() }} permintaan menunggu tinjauan admin.</p>
            </div>
            <div class="badge badge-status badge-pending fs-6 px-3 py-2">
                <i class="bi bi-hourglass-split me-2"></i>{{ $pendingLoans->count() }} pending
            </div>
        </div>
    </div>
</div>

@if($pendingLoans->isEmpty())
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-check-circle fs-1 d-block mb-3 text-success"></i>
                <h4 class="mb-3">Tidak ada peminjaman yang menunggu</h4>
                <a href="{{ route('dashboard') }}" class="btn btn-pink-blue">Kembali ke Dashboard</a>
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
                            <th class="ps-4">ID</th>
                            <th>Buku</th>
                            <th>User</th>
                            <th>Anggota</th>
                            <th>Tgl Pinjam</th>
                            <th>Batas</th>
                            <th class="pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendingLoans as $loan)
                            <tr>
                                <td class="ps-4 fw-semibold">#{{ $loan->id }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($loan->book->title ?? 'N/A', 30) }}</td>
                                <td>{{ $loan->user->name ?? 'Unknown' }}</td>
                                <td>{{ $loan->member_name }}</td>
                                <td>{{ $loan->loan_date->format('d M Y') }}</td>
                                <td>{{ $loan->due_date->format('d M Y') }}</td>
                                <td class="pe-4">
                                    <div class="d-flex flex-wrap gap-2">
                                        <form method="POST" action="{{ route('loans.approve', $loan) }}">
                                            @csrf
                                            <button class="btn btn-success">
                                                <i class="bi bi-check-circle me-1"></i>Setuju
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('loans.reject', $loan) }}">
                                            @csrf
                                            <button class="btn btn-danger" onclick="return confirm('Tolak peminjaman?')">
                                                <i class="bi bi-x-circle me-1"></i>Tolak
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
    </div>
@endif
@endsection

