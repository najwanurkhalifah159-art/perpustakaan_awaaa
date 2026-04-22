<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital') - N.Z Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 270px;
            --brand-700: #0f4c5c;
            --brand-600: #146c7f;
            --brand-500: #1b8aa5;
            --brand-100: #e8f4f7;
            --accent-500: #f28482;
            --accent-100: #fde9e8;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --border: #d9e2ec;
            --text: #102a43;
            --muted: #627d98;
            --success-soft: #e8f7ee;
            --warning-soft: #fff4dd;
            --danger-soft: #fdeaea;
            --shadow-sm: 0 8px 24px rgba(15, 76, 92, 0.08);
            --shadow-md: 0 18px 40px rgba(15, 76, 92, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(27, 138, 165, 0.12), transparent 28%),
                radial-gradient(circle at bottom right, rgba(242, 132, 130, 0.12), transparent 24%),
                linear-gradient(180deg, #f7fbfc 0%, #eef5f7 100%);
            color: var(--text);
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        a {
            color: inherit;
        }

        .app-shell {
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--brand-700) 0%, var(--brand-600) 100%);
            color: #fff;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            box-shadow: var(--shadow-md);
            z-index: 1040;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.35rem 0.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.16);
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.14);
            font-size: 1.4rem;
        }

        .brand-copy small {
            display: block;
            font-size: 0.78rem;
            opacity: 0.8;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .brand-copy strong {
            display: block;
            font-size: 1.05rem;
            line-height: 1.3;
        }

        .sidebar-nav {
            display: grid;
            gap: 0.45rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.9rem 1rem;
            border-radius: 14px;
            color: rgba(255, 255, 255, 0.92);
            text-decoration: none;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.14);
            color: #fff;
            transform: translateX(4px);
        }

        .sidebar-footer {
            margin-top: auto;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 18px;
            padding: 1rem;
        }

        .main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 2rem;
        }

        .topbar {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(217, 226, 236, 0.9);
            border-radius: 24px;
            box-shadow: var(--shadow-sm);
            padding: 1.15rem 1.35rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.55rem;
            font-weight: 700;
            margin: 0;
        }

        .page-subtitle {
            margin: 0.25rem 0 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid #edf2f7;
            padding: 1rem 1.25rem 0.85rem;
        }

        .card-body,
        .modal-body {
            padding: 1.25rem;
        }

        .card-glass,
        .glass-card {
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow-md);
        }

        .stat-card {
            height: 100%;
        }

        .stat-icon {
            width: 58px;
            height: 58px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.45rem;
            margin-bottom: 1rem;
        }

        .stat-icon.primary {
            background: var(--brand-100);
            color: var(--brand-600);
        }

        .stat-icon.warning {
            background: var(--warning-soft);
            color: #b7791f;
        }

        .stat-icon.success {
            background: var(--success-soft);
            color: #2f855a;
        }

        .stat-icon.accent {
            background: var(--accent-100);
            color: #c05656;
        }

        .btn {
            border-radius: 12px;
            padding: 0.7rem 1rem;
            font-weight: 600;
        }

        .btn-pink-blue,
        .btn-pink-blue-custom {
            background: linear-gradient(135deg, var(--brand-600) 0%, var(--accent-500) 100%);
            border: none;
            color: #fff;
        }

        .btn-pink-blue:hover,
        .btn-pink-blue-custom:hover {
            color: #fff;
            opacity: 0.95;
        }

        .btn-outline-pink {
            border: 1px solid var(--accent-500);
            color: #c05656;
            background: #fff;
        }

        .btn-outline-pink:hover {
            background: var(--accent-100);
            color: #9b2c2c;
        }

        .text-pink-primary {
            color: #c05656 !important;
        }

        .text-pink-blue {
            color: var(--brand-600) !important;
        }

        .text-blue-primary {
            color: var(--brand-600) !important;
        }

        .shadow-pink-glow {
            box-shadow: var(--shadow-md);
        }

        .table-responsive {
            border-radius: 20px;
        }

        .table {
            margin-bottom: 0;
            vertical-align: middle;
        }

        .table thead th {
            color: var(--muted);
            font-size: 0.88rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            border-bottom-width: 1px;
        }

        .table-hover tbody tr:hover {
            background: rgba(232, 244, 247, 0.65);
        }

        .table-anim {
            background: #fff;
            border-radius: 22px;
            overflow: hidden;
        }

        .badge-status {
            padding: 0.5rem 0.8rem;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .badge-pending {
            background: var(--warning-soft);
            color: #975a16;
        }

        .badge-approved {
            background: var(--success-soft);
            color: #276749;
        }

        .badge-rejected {
            background: var(--danger-soft);
            color: #c53030;
        }

        .badge-returned {
            background: var(--brand-100);
            color: var(--brand-600);
        }

        .form-control,
        .form-select {
            border-radius: 14px;
            border-color: var(--border);
            padding: 0.8rem 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--brand-500);
            box-shadow: 0 0 0 0.2rem rgba(27, 138, 165, 0.15);
        }

        .auth-page .sidebar,
        .auth-page .sidebar-toggle,
        .auth-page .topbar {
            display: none !important;
        }

        .auth-page .main {
            margin-left: 0;
            padding: 0;
        }

        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1050;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            border: none;
            background: var(--brand-700);
            color: #fff;
            box-shadow: var(--shadow-sm);
        }

        .hero-panel {
            background: linear-gradient(160deg, rgba(15, 76, 92, 0.98), rgba(27, 138, 165, 0.9));
            color: #fff;
            border-radius: 30px;
            padding: 2.5rem;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }

        .hero-panel::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            right: -70px;
            top: -70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .hero-panel::before {
            content: "";
            position: absolute;
            width: 160px;
            height: 160px;
            left: -45px;
            bottom: -65px;
            border-radius: 50%;
            background: rgba(242, 132, 130, 0.18);
        }

        .login-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 2rem 1rem;
        }

        .login-intro h1 {
            font-size: 2.4rem;
            line-height: 1.15;
            margin: 1rem 0;
            font-weight: 700;
        }

        .login-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 0.85rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.16);
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .login-logo {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            background: linear-gradient(135deg, var(--brand-600) 0%, var(--accent-500) 100%);
            color: #fff;
            font-size: 1.8rem;
        }

        .divider {
            color: var(--muted);
            font-size: 0.9rem;
        }

        .section-label {
            font-size: 0.82rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 700;
        }

        .empty-state {
            padding: 2rem 1rem;
            text-align: center;
            color: var(--muted);
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.25s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .main {
                margin-left: 0;
                padding: 1rem;
                padding-top: 4.5rem;
            }

            .topbar {
                padding: 1rem 1.1rem;
            }
        }
    </style>
</head>
<body class="@if(request()->routeIs('login', 'daftar')) auth-page @endif">
    @php
        $isLoggedIn = session()->has('user');
        $userRole = session('user.role');
    @endphp

    <div class="app-shell">
        @unless(request()->routeIs('login', 'daftar'))
            <button class="sidebar-toggle" type="button" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>

            <aside class="sidebar" id="sidebar">
                <div class="brand-mark">
                    <span class="brand-icon"><i class="bi bi-book-half"></i></span>
                    <div class="brand-copy">
                        <small>Perpustakaan</small>
                        <strong>N.Z Perpustakaan</strong>
                    </div>
                </div>

                <nav class="sidebar-nav">
                    @if($isLoggedIn && $userRole === 'admin')
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('buku') }}" class="{{ request()->routeIs('buku') ? 'active' : '' }}">
                            <i class="bi bi-book"></i>
                            <span>Manajemen Buku</span>
                        </a>
                        <a href="{{ route('admin.approvals') }}" class="{{ request()->routeIs('admin.approvals') ? 'active' : '' }}">
                            <i class="bi bi-check2-square"></i>
                            <span>Persetujuan</span>
                        </a>
                        <a href="{{ route('pengembalian') }}" class="{{ request()->routeIs('pengembalian') ? 'active' : '' }}">
                            <i class="bi bi-arrow-return-left"></i>
                            <span>Pengembalian</span>
                        </a>
                    @elseif($isLoggedIn)
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-house-door"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('buku') }}" class="{{ request()->routeIs('buku') ? 'active' : '' }}">
                            <i class="bi bi-book-half"></i>
                            <span>Koleksi Buku</span>
                        </a>
                        <a href="{{ route('peminjaman') }}" class="{{ request()->routeIs('peminjaman') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle"></i>
                            <span>Peminjaman</span>
                        </a>
                        <a href="{{ route('pengembalian') }}" class="{{ request()->routeIs('pengembalian') ? 'active' : '' }}">
                            <i class="bi bi-arrow-return-left"></i>
                            <span>Pengembalian</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right"></i>
                            <span>Masuk</span>
                        </a>
                    @endif
                </nav>

                @if($isLoggedIn)
                    <div class="sidebar-footer">
                        <div class="fw-semibold">{{ session('user.name') }}</div>
                        <div class="small opacity-75 mb-3">{{ ucfirst($userRole) }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light w-100">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </div>
                @endif
            </aside>
        @endunless

        <main class="main">
            @unless(request()->routeIs('login', 'daftar'))
                <div class="topbar">
                    <div>
                        <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                        <p class="page-subtitle">{{ now()->format('d F Y, H:i') }}</p>
                    </div>
                    @if($isLoggedIn)
                        <div class="text-end">
                            <div class="fw-semibold">{{ session('user.name') }}</div>
                            <div class="small text-muted">{{ ucfirst($userRole) }}</div>
                        </div>
                    @endif
                </div>
            @endunless

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('show');
            }
        }
    </script>
</body>
</html>
