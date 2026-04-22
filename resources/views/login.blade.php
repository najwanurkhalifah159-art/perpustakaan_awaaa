@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="login-shell">
    <div class="container">
        <div class="row align-items-center justify-content-center g-4">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-panel">
                    <span class="login-badge">
                        <i class="bi bi-book-half"></i> Perpustakaan Digital
                    </span>
                    <div class="login-intro position-relative">
                        <h1>hallo selamat datang di perpustakaan awa</h1>
                        <p class="mb-0 fs-5 text-white-50">
                            Akses buku, peminjaman, dan pengembalian dalam satu tempat.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5 col-md-7">
                <div class="card glass-card border-0">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <div class="login-logo mb-3">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <h2 class="fw-bold mb-1">Masuk</h2>
                            <p class="text-muted mb-0">Masuk ke sistem perpustakaan digital.</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.process') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-pink-blue w-100 mb-3">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                            </button>

                            <div class="divider text-center mb-3">Belum punya akun?</div>

                            <a href="{{ route('daftar') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-person-plus me-2"></i>Buat Akun
                            </a>
                        </form>

                        <div class="mt-4 text-center">
                            <small class="text-muted">Demo admin: admin@perpustakaan.local / admin</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
