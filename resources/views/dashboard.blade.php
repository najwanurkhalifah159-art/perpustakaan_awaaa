@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
@php
    $recentLoans = \App\Models\Loan::with('book', 'user')
        ->when($userRole === 'user', function ($query) {
            $query->where('user_id', session('user.id'));
        })
        ->latest()
        ->limit(5)
        ->get();
@endphp

<div class="card border-0 mb-4">
    <div class="card-body p-4 p-lg-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <div class="section-label">Ringkasan</div>
                <h2 class="mt-2 mb-2 fw-bold">Selamat datang, {{ session('user.name') }}.</h2>
                <p class="text-muted mb-0">
                    Kelola koleksi buku, peminjaman, dan pengembalian dari satu dashboard yang lebih rapi dan mudah dipakai.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <span class="badge text-bg-light border px-3 py-2">
                    <i class="bi bi-person-badge me-1"></i>{{ ucfirst($userRole) }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon primary">
                    <i class="bi bi-book-half"></i>
                </div>
                <div class="text-muted small mb-2">Total Buku</div>
                <h3 class="fw-bold mb-2">{{ $books }}</h3>
                <p class="text-muted mb-3">Seluruh koleksi yang tersedia di perpustakaan.</p>
                <a href="{{ route('buku') }}" class="btn btn-outline-pink">Lihat Koleksi</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon success">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div class="text-muted small mb-2">Peminjaman Aktif</div>
                <h3 class="fw-bold mb-2">{{ $activeLoans }}</h3>
                <p class="text-muted mb-3">Buku yang sedang dipinjam dan belum dikembalikan.</p>
                <a href="{{ route('pengembalian') }}" class="btn btn-outline-secondary">Lihat Pengembalian</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card stat-card">
            <div class="card-body">
                <div class="stat-icon {{ $userRole === 'admin' ? 'warning' : 'accent' }}">
                    <i class="bi {{ $userRole === 'admin' ? 'bi-hourglass-split' : 'bi-plus-circle' }}"></i>
                </div>
                <div class="text-muted small mb-2">
                    {{ $userRole === 'admin' ? 'Menunggu Persetujuan' : 'Aksi Cepat' }}
                </div>
                <h3 class="fw-bold mb-2">{{ $userRole === 'admin' ? $pendingLoans : 'Siap' }}</h3>
                <p class="text-muted mb-3">
                    {{ $userRole === 'admin'
                        ? 'Permintaan peminjaman yang perlu ditinjau admin.'
                        : 'Ajukan pinjaman baru atau cek status buku Anda.' }}
                </p>
                @if($userRole === 'admin')
                    <a href="{{ route('admin.approvals') }}" class="btn btn-warning">Kelola Persetujuan</a>
                @else
                    <a href="{{ route('peminjaman') }}" class="btn btn-pink-blue">Ajukan Peminjaman</a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    @if($userRole === 'admin')
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="section-label mb-2">Admin</div>
                    <h5 class="fw-bold">Kelola Buku</h5>
                    <p class="text-muted">Tambah, ubah, atau hapus koleksi buku perpustakaan.</p>
                    <a href="{{ route('buku') }}" class="btn btn-pink-blue">Buka Manajemen Buku</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="section-label mb-2">Admin</div>
                    <h5 class="fw-bold">Persetujuan Peminjaman</h5>
                    <p class="text-muted">Tinjau permintaan user dan putuskan disetujui atau ditolak.</p>
                    <a href="{{ route('admin.approvals') }}" class="btn btn-outline-secondary">Buka Persetujuan</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="section-label mb-2">Admin</div>
                    <h5 class="fw-bold">Data Pengembalian</h5>
                    <p class="text-muted">Pantau buku yang sudah jatuh tempo atau sudah kembali.</p>
                    <a href="{{ route('pengembalian') }}" class="btn btn-outline-secondary">Lihat Pengembalian</a>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="section-label mb-2">Peminjaman</div>
                    <h5 class="fw-bold">Ajukan Pinjaman</h5>
                    <p class="text-muted">Pilih buku yang tersedia dan kirim permintaan peminjaman.</p>
                    <a href="{{ route('peminjaman') }}" class="btn btn-pink-blue">Pinjam Buku</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="section-label mb-2">Pengembalian</div>
                    <h5 class="fw-bold">Kembalikan Buku</h5>
                    <p class="text-muted">Lihat buku aktif Anda dan kembalikan tepat waktu.</p>
                    <a href="{{ route('pengembalian') }}" class="btn btn-outline-secondary">Buka Pengembalian</a>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="card" id="riwayat">
    <div class="card-header">
        <h5 class="mb-0 fw-bold">Aktivitas Terbaru</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Buku</th>
                        <th>Nama</th>
                        <th>Tanggal Pinjam</th>
                        <th>Status</th>
                        <th class="pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLoans as $loan)
                        <tr>
                            <td class="ps-4 fw-semibold">#{{ $loan->id }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($loan->book->title ?? 'N/A', 32) }}</td>
                            <td>{{ $loan->member_name ?? $loan->user->name ?? 'Manual' }}</td>
                            <td>{{ optional($loan->loan_date)->format('d M Y') }}</td>
                            <td>
                                @if($loan->status === 'pending')
                                    <span class="badge-status badge-pending">Pending</span>
                                @elseif($loan->status === 'approved')
                                    <span class="badge-status badge-approved">Disetujui</span>
                                @elseif($loan->status === 'rejected')
                                    <span class="badge-status badge-rejected">Ditolak</span>
                                @else
                                    <span class="badge-status badge-returned">Dikembalikan</span>
                                @endif
                            </td>
                            <td class="pe-4">
                                @if($loan->status === 'approved' && is_null($loan->return_date) && $userRole === 'user')
                                    <form method="POST" action="{{ route('pengembalian.process', $loan->id) }}">
                                        @csrf
                                        <input type="hidden" name="return_date" value="{{ now()->format('Y-m-d') }}">
                                        <button type="submit" class="btn btn-sm btn-warning">Kembalikan</button>
                                    </form>
                                @elseif($userRole === 'admin' && $loan->status === 'pending')
                                    <a href="{{ route('admin.approvals') }}" class="btn btn-sm btn-outline-secondary">Tinjau</a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Belum ada aktivitas terbaru.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
