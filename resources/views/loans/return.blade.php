@extends('layouts.app')

@section('title', 'Kembalikan Buku #' . $loan->id)

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="mb-0">
                    <i class="bi bi-arrow-return-left me-2 text-success"></i>Pengembalian Buku
                </h3>
                <a href="{{ route('loans.show', $loan) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Detail
                </a>
            </div>
            <div class="card-body">
                <!-- Loan Info Summary -->
                <div class="row mb-4 p-3 bg-light rounded">
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Buku</h6>
                        <h5 class="mb-0">{{ Str::limit($loan->book->title, 30) }}</h5>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Anggota</h6>
                        <h5 class="mb-0">{{ $loan->member->name }}</h5>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-2">Batas Kembali</h6>
                        <h5 class="{{ $loan->due_date < now()->format('Y-m-d') ? 'text-danger' : 'text-success' }} mb-0">
                            {{ $loan->due_date->format('d M Y') }}
                            @if($loan->due_date < now()->format('Y-m-d'))
                                <span class="badge bg-danger ms-1">Terlambat</span>
                            @endif
                        </h5>
                    </div>
                </div>

                <form action="{{ route('loans.processReturn', $loan) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Tanggal Pengembalian <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('return_date') is-invalid @enderror" 
                               name="return_date" value="{{ old('return_date', now()->format('Y-m-d')) }}" 
                               max="{{ now()->format('Y-m-d') }}" required min="{{ $loan->loan_date->format('Y-m-d') }}">
                        @error('return_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">Denda Rp 1.000/hari jika terlambat</div>
                    </div>

                    <!-- Live Denda Preview -->
                    <div id="denda-preview" class="alert alert-warning" style="display: none;">
                        <h6><i class="bi bi-exclamation-triangle"></i> Estimasi Denda</h6>
                        <div id="denda-amount" class="fs-4 fw-bold text-danger">Rp 0</div>
                        <small>Rp 1.000 per hari keterlambatan</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Proses Pengembalian
                        </button>
                        <a href="{{ route('loans.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const dueDate = new Date('{{ $loan->due_date->format('Y-m-d') }}');
const returnInput = document.querySelector('input[name="return_date"]');
const preview = document.getElementById('denda-preview');
const amount = document.getElementById('denda-amount');

function calcDenda() {
    const returnDate = new Date(returnInput.value);
    if (returnDate > dueDate) {
        const daysLate = Math.ceil((returnDate - dueDate) / (1000 * 60 * 60 * 24));
        const denda = daysLate * 1000;
        amount.textContent = 'Rp ' + denda.toLocaleString('id-ID');
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}

returnInput.addEventListener('change', calcDenda);
returnInput.addEventListener('input', calcDenda);
calcDenda();
</script>
@endsection

