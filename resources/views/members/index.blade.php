@extends('layouts.app')

@section('title', 'Daftar Anggota')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h2 class="mb-1">
                    <i class="bi bi-people me-2 text-success"></i>Daftar Anggota
                </h2>
                <p class="text-muted mb-0">Kelola data anggota perpustakaan</p>
            </div>
            <a href="{{ route('members.create') }}" class="btn btn-success">
                <i class="bi bi-person-plus me-2"></i>Tambah Anggota
            </a>
        </div>
    </div>
</div>

@if($members->isEmpty())
    <div class="text-center py-5">
        <div class="card border-0 bg-light">
            <div class="card-body">
                <i class="bi bi-people display-1 text-muted mb-3"></i>
                <h4 class="text-muted mb-3">Belum ada anggota</h4>
                <p class="text-muted mb-4">Tambahkan anggota pertama</p>
                <a href="{{ route('members.create') }}" class="btn btn-success btn-lg">
                    <i class="bi bi-person-plus me-2"></i>Tambah Anggota
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
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th>Peminjaman Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
@foreach($members as $index => $member)
                            <tr>
                                <td><strong>{{ ($members->firstItem() ?? 0) + $index + 1 }}</strong></td>
                                <td>
                                    <strong>{{ $member->name }}</strong>
                                    <small class="d-block text-muted">ID: {{ $member->id }}</small>
                                </td>
                                <td>
                                    <i class="bi bi-telephone text-muted me-1"></i>{{ $member->phone }}<br>
                                    <small class="text-muted">{{ Str::limit($member->address, 30) }}</small>
                                </td>
                                <td>{{ Str::limit($member->address, 40) }}</td>
                                <td>
                                    @php $active = $member->loans()->whereNull('return_date')->count(); @endphp
                                    @if($active > 0)
                                        <span class="badge bg-warning text-dark">{{ $active }} Aktif</span>
                                    @else
                                        <span class="badge bg-success">Tersedia</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('members.show', $member) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('members.edit', $member) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('members.destroy', $member) }}" method="POST" class="d-inline" 
                                              onsubmit="return confirm('Hapus {{ $member->name }}?')">
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
        @if($members->hasPages())
            <div class="card-footer bg-light">
                {{ $members->links() }}
            </div>
        @endif
    </div>
@endif
@endsection

