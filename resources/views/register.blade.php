@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="login-shell">
    <div class="container">
        <div class="row align-items-center justify-content-center g-4">
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-panel">
                    <span class="login-badge">
                        <i class="bi bi-person-plus"></i> Registrasi Anggota
                    </span>
                    <div class="login-intro position-relative">
                        <h1>Buat akun baru</h1>
                        <p class="mb-0 fs-5 text-white-50">
                            Daftar sebagai anggota untuk mulai meminjam dan mengembalikan buku secara digital.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="card glass-card border-0">
                    <div class="card-body p-4 p-lg-5">
                        <div class="text-center mb-4">
                            <div class="login-logo mb-3">
                                <i class="bi bi-person-plus-fill"></i>
                            </div>
                            <h2 class="fw-bold mb-1">Daftar Akun</h2>
                            <p class="text-muted mb-0">Lengkapi data di bawah untuk membuat akun.</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.process') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-pink-blue w-100 mt-4 mb-3">
                                <i class="bi bi-person-check me-2"></i>Buat Akun
                            </button>

                            <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Kembali ke Login
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
