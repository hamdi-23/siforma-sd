<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Siforma SD</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --app-bg: #f6f8fb;
            --app-border: #e5e7eb;
            --app-dark: #0f172a;
            --app-muted: #64748b;
            --app-primary: #0ea5e9;
            --app-primary-dark: #0369a1;
            --app-success: #10b981;
            --app-danger: #ef4444;
            --app-warning: #f59e0b;
            --app-info: #06b6d4;
            --sidebar-width: 276px;
        }

        * {
            letter-spacing: 0;
        }

        body {
            background: var(--app-bg);
            color: var(--app-dark);
            font-family: Inter, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        a {
            text-decoration: none;
        }

        .app-shell {
            min-height: 100vh;
        }

        .app-sidebar {
            background: #fff;
            border-right: 1px solid var(--app-border);
            bottom: 0;
            left: 0;
            padding: 24px 18px;
            position: fixed;
            top: 0;
            width: var(--sidebar-width);
            z-index: 1030;
        }

        .brand-link {
            align-items: center;
            color: var(--app-dark);
            display: flex;
            gap: 12px;
            margin-bottom: 28px;
        }

        .brand-mark {
            align-items: center;
            background: var(--app-dark);
            border-radius: 12px;
            color: #fff;
            display: inline-flex;
            font-weight: 800;
            height: 44px;
            justify-content: center;
            width: 44px;
        }

        .brand-title {
            display: block;
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.1;
        }

        .brand-subtitle {
            color: var(--app-muted);
            display: block;
            font-size: .82rem;
            margin-top: 3px;
        }

        .sidebar-label {
            color: #94a3b8;
            font-size: .72rem;
            font-weight: 800;
            margin: 20px 12px 8px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            display: grid;
            gap: 6px;
        }

        .sidebar-nav .nav-link {
            align-items: center;
            border-radius: 12px;
            color: #475569 !important;
            display: flex;
            font-size: .94rem;
            font-weight: 700;
            gap: 11px;
            padding: 12px 14px;
            transition: .18s ease;
        }

        .sidebar-nav .nav-link i {
            color: #94a3b8;
            text-align: center;
            width: 20px;
        }

        .sidebar-nav .nav-link:hover {
            background: #f1f5f9;
            color: var(--app-dark) !important;
        }

        .sidebar-nav .nav-link.active {
            background: var(--app-dark);
            color: #fff !important;
            box-shadow: 0 12px 24px rgba(15, 23, 42, .14);
        }

        .sidebar-nav .nav-link.active i {
            color: #bae6fd;
        }

        .sidebar-user {
            border: 1px solid var(--app-border);
            border-radius: 14px;
            bottom: 20px;
            left: 18px;
            padding: 14px;
            position: absolute;
            right: 18px;
        }

        .user-avatar {
            align-items: center;
            background: #e0f2fe;
            border-radius: 10px;
            color: var(--app-primary-dark);
            display: inline-flex;
            font-weight: 800;
            height: 38px;
            justify-content: center;
            width: 38px;
        }

        .app-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        .topbar {
            align-items: center;
            background: rgba(255, 255, 255, .9);
            border-bottom: 1px solid var(--app-border);
            display: flex;
            gap: 16px;
            justify-content: space-between;
            min-height: 72px;
            padding: 14px 28px;
            position: sticky;
            top: 0;
            z-index: 1020;
            backdrop-filter: blur(12px);
        }

        .content-wrap {
            padding: 28px;
        }

        .page-header {
            background: #fff;
            border: 1px solid var(--app-border);
            border-radius: 16px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, .05);
            margin-bottom: 24px;
            padding: 24px;
        }

        .page-header h1 {
            color: var(--app-dark);
            font-size: clamp(1.35rem, 3vw, 2rem);
            font-weight: 850;
            margin-bottom: 6px;
        }

        .page-header p {
            color: var(--app-muted);
            margin: 0;
        }

        .card {
            border: 1px solid var(--app-border);
            border-radius: 16px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, .05);
            overflow: hidden;
        }

        .card:hover {
            transform: none;
        }

        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--app-border);
            color: var(--app-dark);
            padding: 18px 20px;
        }

        .card-header h5,
        .card-header h6 {
            color: var(--app-dark);
            font-weight: 800;
        }

        .card-body {
            padding: 22px;
        }

        .stat-card {
            background: #fff;
            border: 1px solid var(--app-border);
            border-radius: 16px;
            box-shadow: 0 16px 40px rgba(15, 23, 42, .05);
            min-height: 145px;
            padding: 20px;
            text-align: left;
        }

        .stat-card .stat-value {
            color: var(--app-dark);
            font-size: 2rem;
            font-weight: 850;
            margin: 14px 0 6px;
        }

        .stat-card .stat-label {
            color: var(--app-muted);
            font-size: .88rem;
            font-weight: 700;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fafc;
            border-bottom: 1px solid var(--app-border);
            color: #475569;
            font-size: .78rem;
            font-weight: 850;
            padding: 14px 16px;
            text-transform: uppercase;
        }

        .table tbody td {
            border-bottom: 1px solid #eef2f7;
            color: #334155;
            padding: 15px 16px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .form-label {
            color: #334155;
            font-size: .88rem;
            font-weight: 800;
        }

        .form-control,
        .form-select {
            border: 1px solid #dbe3ea;
            border-radius: 11px;
            padding: 11px 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--app-primary);
            box-shadow: 0 0 0 .22rem rgba(14, 165, 233, .14);
        }

        textarea.form-control {
            min-height: 120px;
        }

        .btn {
            border-radius: 11px;
            font-weight: 750;
            padding: 10px 16px;
        }

        .btn-sm {
            border-radius: 9px;
            padding: 7px 10px;
        }

        .btn-primary {
            background: var(--app-primary-dark);
            border-color: var(--app-primary-dark);
        }

        .btn-primary:hover {
            background: #075985;
            border-color: #075985;
        }

        .btn-info {
            background: var(--app-info);
            border-color: var(--app-info);
            color: #fff;
        }

        .btn-info:hover {
            background: #0891b2;
            border-color: #0891b2;
            color: #fff;
        }

        .badge {
            border-radius: 999px;
            font-weight: 800;
            padding: .45rem .7rem;
        }

        .badge-present,
        .badge-reviewed {
            background: #dcfce7;
            color: #166534;
        }

        .badge-absent {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-late,
        .badge-draft {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-sick,
        .badge-submitted {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-leave {
            background: #ede9fe;
            color: #5b21b6;
        }

        .progress {
            background: #e2e8f0;
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-bar {
            border-radius: 999px;
            font-weight: 800;
        }

        .alert {
            border: 1px solid transparent;
            border-radius: 14px;
            box-shadow: 0 12px 26px rgba(15, 23, 42, .05);
        }

        .modal-content {
            border: 1px solid var(--app-border);
            border-radius: 16px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, .18);
        }

        .modal-header,
        .modal-footer {
            border-color: var(--app-border);
        }

        .footer {
            color: #94a3b8;
            font-size: .88rem;
            padding: 10px 28px 28px;
        }

        @media (max-width: 991.98px) {
            .app-sidebar {
                display: none;
            }

            .app-main {
                margin-left: 0;
            }

            .topbar,
            .content-wrap {
                padding-left: 16px;
                padding-right: 16px;
            }

            .page-header {
                align-items: flex-start !important;
                flex-direction: column;
                gap: 14px;
            }
        }
    </style>

    @yield('extra_css')
</head>
<body>
    <div class="app-shell">
        <aside class="app-sidebar">
            <a class="brand-link" href="{{ route('dashboard') }}">
                <span class="brand-mark">SD</span>
                <span>
                    <span class="brand-title">Siforma SD</span>
                    <span class="brand-subtitle">Manajemen sekolah</span>
                </span>
            </a>

            <div class="sidebar-label">Menu utama</div>
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>

                @if(Auth::user()->isTeacher())
                    <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i> Presensi
                    </a>
                    <a href="{{ route('daily-report.index') }}" class="nav-link {{ request()->routeIs('daily-report.*') ? 'active' : '' }}">
                        <i class="fas fa-file-lines"></i> Laporan Harian
                    </a>
                    <a href="{{ route('monthly-recap.index') }}" class="nav-link {{ request()->routeIs('monthly-recap.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-simple"></i> Rekap Bulanan
                    </a>
                @else
                    <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                        <i class="fas fa-users-viewfinder"></i> Data Presensi
                    </a>
                    <a href="{{ route('daily-report.index') }}" class="nav-link {{ request()->routeIs('daily-report.*') ? 'active' : '' }}">
                        <i class="fas fa-file-lines"></i> Laporan Guru
                    </a>
                    <a href="{{ route('monthly-recap.index') }}" class="nav-link {{ request()->routeIs('monthly-recap.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-simple"></i> Rekap Bulanan
                    </a>
                @endif
            </nav>

            <div class="sidebar-user">
                <div class="d-flex align-items-center gap-2">
                    <span class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    <div class="min-w-0">
                        <div class="fw-bold text-truncate">{{ Auth::user()->name }}</div>
                        <div class="small text-muted">{{ Auth::user()->role ?? 'Pengguna' }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="app-main">
            <header class="topbar">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-light border d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-label="Buka menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div>
                        <div class="small fw-bold text-muted">Siforma SD</div>
                        <div class="fw-bold">@yield('title')</div>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn btn-light border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown">
                        <span class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">{{ Auth::user()->email }}</li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </header>

            <div class="content-wrap">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            <footer class="footer">
                &copy; {{ date('Y') }} Siforma SD. Sistem manajemen sekolah dasar.
            </footer>
        </main>
    </div>

    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileNav">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title fw-bold">Siforma SD</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a>
                <a href="{{ route('attendance.index') }}" class="nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Presensi
                </a>
                <a href="{{ route('daily-report.index') }}" class="nav-link {{ request()->routeIs('daily-report.*') ? 'active' : '' }}">
                    <i class="fas fa-file-lines"></i> Laporan Harian
                </a>
                <a href="{{ route('monthly-recap.index') }}" class="nav-link {{ request()->routeIs('monthly-recap.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-simple"></i> Rekap Bulanan
                </a>
            </nav>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('extra_js')
</body>
</html>
