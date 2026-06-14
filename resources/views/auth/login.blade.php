<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SDN Karangnunggal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: #f6f8fb;
            color: #0f172a;
            font-family: Inter, "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }

        .login-page {
            display: grid;
            min-height: 100vh;
            place-items: center;
            padding: 24px;
        }

        .login-shell {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 22px;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .12);
            display: grid;
            max-width: 1040px;
            overflow: hidden;
            width: 100%;
        }

        .login-hero {
            background: #0f172a;
            color: #fff;
            padding: 42px;
        }

        .brand-mark {
            align-items: center;
            background: #0ea5e9;
            border-radius: 14px;
            display: inline-flex;
            font-size: 1.1rem;
            font-weight: 800;
            height: 50px;
            justify-content: center;
            width: 50px;
        }

        .login-hero h1 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 850;
            margin: 26px 0 12px;
        }

        .login-hero p {
            color: #cbd5e1;
            line-height: 1.8;
            margin: 0;
        }

        .feature-list {
            display: grid;
            gap: 12px;
            margin-top: 34px;
        }

        .feature-item {
            align-items: center;
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .1);
            border-radius: 14px;
            display: flex;
            gap: 12px;
            padding: 14px;
        }

        .login-panel {
            padding: 42px;
        }

        .login-panel h2 {
            font-weight: 850;
            margin-bottom: 6px;
        }

        .form-label {
            color: #334155;
            font-size: .9rem;
            font-weight: 800;
        }

        .form-control {
            border: 1px solid #dbe3ea;
            border-radius: 12px;
            padding: 12px 14px;
        }

        .form-control:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 .22rem rgba(14, 165, 233, .14);
        }

        .btn-login {
            background: #0369a1;
            border: none;
            border-radius: 12px;
            color: #fff;
            font-weight: 800;
            padding: 12px 18px;
            width: 100%;
        }

        .btn-login:hover {
            background: #075985;
            color: #fff;
        }

        .message {
            border-radius: 14px;
            margin-bottom: 18px;
            padding: 14px 16px;
        }

        .message-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .message-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .test-credentials {
            background: #f8fafc;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            margin-top: 24px;
            padding: 18px;
        }

        .credential-row {
            border-bottom: 1px solid #e5e7eb;
            padding: 12px 0;
        }

        .credential-row:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        @media (min-width: 900px) {
            .login-shell {
                grid-template-columns: 1.05fr .95fr;
            }
        }

        @media (max-width: 899px) {
            .login-hero,
            .login-panel {
                padding: 28px;
            }
        }
    </style>
</head>
<body>
    <main class="login-page">
        <section class="login-shell">
            <div class="login-hero">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';" style="width: 80px; height: 80px; object-fit: contain; border-radius: 14px;">
                <span class="brand-mark" style="display: none; height: 80px; width: 80px; font-size: 2rem;">SD</span>
                <h1>SDN Karangnunggal</h1>
                <p>Sistem manajemen sekolah dasar untuk memantau presensi, laporan pembelajaran, dan rekap bulanan guru dalam satu aplikasi.</p>

                <div class="feature-list">
                    <div class="feature-item">
                        <i class="fas fa-clock text-info"></i>
                        <span>Presensi guru harian</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-file-lines text-success"></i>
                        <span>Laporan pembelajaran</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-chart-simple text-warning"></i>
                        <span>Rekap performa bulanan</span>
                    </div>
                </div>
            </div>

            <div class="login-panel">
                <h2>Masuk akun</h2>
                <p class="text-muted mb-4">Gunakan akun yang sudah terdaftar untuk mengakses dashboard.</p>

                @if($errors->any())
                    <div class="message message-danger">
                        <strong><i class="fas fa-circle-exclamation"></i> Login gagal</strong>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                @if(session('message'))
                    <div class="message message-info">
                        <i class="fas fa-circle-info"></i> {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            required
                            autofocus
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Ingat saya</label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-right-to-bracket"></i> Login
                    </button>
                </form>

                <div class="test-credentials">
                    <div class="fw-bold mb-1">Akun test</div>
                    <div class="credential-row">
                        <div class="small fw-bold text-primary">Admin</div>
                        <div class="small">admin@example.com / password</div>
                    </div>
                    <div class="credential-row">
                        <div class="small fw-bold text-primary">Kepala Sekolah</div>
                        <div class="small">principal@example.com / password</div>
                    </div>
                    <div class="credential-row">
                        <div class="small fw-bold text-primary">Guru</div>
                        <div class="small">guru1@example.com sampai guru5@example.com / password</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
