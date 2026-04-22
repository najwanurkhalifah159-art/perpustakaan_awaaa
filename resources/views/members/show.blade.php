@extends('layouts.app')

@section('title', $member->name)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="mb-0">
                    <i class="bi bi-person me-2 text-success"></i>{{ $member->name }}
                </h3>
                <a href="{{ route('members.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small mb-2">Nama</label>
                        <h5>{{ $member->name }}</h5>
                        <label class="form-label fw-semibold text-muted small mb-2">No HP</label>
                        <p class="mb-0">{{ $member->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-muted small mb-2">Alamat</label>
                        <p>{{ $member->address }}</p>
                        <label class="form-label fw-semibold text-muted small mb-2">Terdaftar</label>
                        <p class="mb-0">{{ $member->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if($member->loans()->exists())
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-journal-text me-2"></i>Riwayat Peminjaman ({{ $member->loans->count() }})
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Buku</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Batas</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($member->loans()->latest()->take(10) as $loan)
                                    <tr>
                                        <td>{{ Str::limit($loan->book->title, 30) }}</td>
                                        <td>{{ $loan->loan_date->format('d M Y') }}</td>
                                        <td>{{ $loan->due_date->format('d M Y') }}</td>
                                        <td>
                                            @if($loan->return_date)
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-info">Aktif</span>
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
                <h6 class="mb-0 fw-semibold">Aksi</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('members.edit', $member) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('loans.create') }}?member_id={{ $member->id }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Pinjam Buku
                    </a>
                    <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-grid" 
                          onsubmit="return confirm('Hapus {{ $member->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

