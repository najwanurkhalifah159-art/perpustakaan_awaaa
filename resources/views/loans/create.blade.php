@extends('layouts.app')

@section('title', 'Pinjam Buku')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="mb-0">
                    <i class="bi bi-plus-circle me-2 text-warning"></i>Pinjam Buku Baru
                </h3>
                <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('loans.store') }}" method="POST">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Buku <span class="text-danger">*</span></label>
                            <select class="form-select @error('book_id') is-invalid @enderror" name="book_id" required>
                                <option value="">Pilih Buku</option>
                                @foreach($books->where('stock', '>', 0) as $book)
                                    <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                        {{ $book->title }} - {{ $book->author }} (Stok: {{ $book->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('book_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Anggota <span class="text-danger">*</span></label>
                            <select class="form-select @error('member_id') is-invalid @enderror" name="member_id" required>
                                <option value="">Pilih Anggota</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Tgl Pinjam <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('loan_date') is-invalid @enderror" 
                                   name="loan_date" value="{{ old('loan_date', now()->format('Y-m-d')) }}" required>
                            @error('loan_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Batas Kembali <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                   name="due_date" value="{{ old('due_date') }}" required min="{{ now()->format('Y-m-d') }}">
                            @error('due_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>Denda: Rp 1.000/hari setelah batas waktu
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-warning flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Catat Peminjaman
                        </button>
                        <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('input[name="loan_date"]').addEventListener('change', function() {
    const loanDate = new Date(this.value);
    const dueDate = new Date(loanDate.getTime() + 7 * 24 * 60 * 60 * 1000); // +7 days
    document.querySelector('input[name="due_date"]').value = dueDate.toISOString().split('T')[0];
});
</script>
@endsection

