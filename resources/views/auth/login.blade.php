<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Rapot SMPN 1 Surabaya — Sistem Informasi Manajemen Rapot Elektronik">
    <title>Login — E-Rapot SMPN 1 Surabaya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #1e3a8a; /* Deep Navy Blue */
            --primary-light: #2563eb;
            --primary-dark: #1e293b;
            --accent: #d97706; /* Elegant Gold */
            --success: #059669;
            --danger: #dc2626;
            --bg: #f1f5f9;
            --surface: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background-image: 
                radial-gradient(circle at 100% 0%, rgba(37, 99, 235, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 0% 100%, rgba(217, 119, 6, 0.05) 0%, transparent 40%);
            -webkit-font-smoothing: antialiased;
        }

        .login-container {
            display: flex;
            width: 100%;
            max-width: 960px;
            background: var(--surface);
            border-radius: 24px;
            box-shadow: 0 20px 40px -10px rgba(15, 23, 42, 0.1);
            overflow: hidden;
            animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes scaleIn {
            from { opacity: 0; transform: scale(0.97) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* ── Branding Panel (Left) ── */
        .login-branding {
            flex: 1.1;
            background: linear-gradient(145deg, var(--primary) 0%, var(--primary-dark) 100%);
            padding: 4rem;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .login-branding::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAzNHYtNGgtMnY0aC00djJoNHY0aDJ2LTRoNHYtMmgtNHptMC0zMFYwaC0ydjRoLTR2Mmg0djRoMnYtNGg0VjRoLTR6TTI2IDEydC00aC0ydjRoLTR2Mmg0djRoMnYtNGg0di0yaC00ek0xMiAyMnYtNGgtMnY0SDZ2Mmg0djRoMnYtNGg0di0yaC00em0wIDMwdC00aC0ydjRINnYyaDR2NGgydi00aDR2LTJoLTR6IiBmaWxsPSIjZmZmIiBmaWxsLW9wYWNpdHk9IjAuMDUiLz48L2c+PC9zdmc+') repeat;
            pointer-events: none;
        }

        .branding-header {
            position: relative;
            z-index: 10;
        }

        .logo-box {
            width: 64px; height: 64px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            backdrop-filter: blur(10px);
            margin-bottom: 1.5rem;
            color: var(--accent);
        }

        .branding-title {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .branding-title span {
            color: var(--accent);
        }

        .branding-subtitle {
            font-size: 1rem;
            color: #94a3b8;
            line-height: 1.6;
            max-width: 85%;
        }

        .branding-footer {
            position: relative;
            z-index: 10;
            font-size: 0.875rem;
            color: #64748b;
        }

        /* ── Form Panel (Right) ── */
        .login-form-panel {
            flex: 1;
            padding: 4rem;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        /* ── Alert ── */
        .alert {
            border-radius: 12px; padding: 1rem;
            font-size: 0.875rem; margin-bottom: 1.5rem;
            display: flex; align-items: center; gap: 0.75rem;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; }

        /* ── Form ── */
        .form-group { margin-bottom: 1.5rem; }
        .form-label {
            display: block; font-size: 0.875rem; font-weight: 600;
            color: var(--text); margin-bottom: 0.5rem;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: #94a3b8; width: 20px; height: 20px; pointer-events: none;
            transition: color 0.2s ease;
        }
        .form-input {
            width: 100%; padding: 0.875rem 1rem 0.875rem 3rem;
            background: #f8fafc;
            border: 1px solid var(--border);
            border-radius: 12px; color: var(--text);
            font-family: inherit; font-size: 0.95rem; font-weight: 500;
            transition: all 0.2s ease; outline: none;
        }
        .form-input::placeholder { color: #cbd5e1; font-weight: 400; }
        .form-input:focus {
            border-color: var(--primary-light);
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        .form-input:focus + .input-icon, 
        .input-wrap:focus-within .input-icon {
            color: var(--primary-light);
        }
        .form-input.is-invalid { border-color: var(--danger); }

        .password-toggle {
            position: absolute; right: 16px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #94a3b8; cursor: pointer;
            padding: 0; display: flex; align-items: center; justify-content: center;
            transition: color 0.2s ease;
        }
        .password-toggle:hover { color: var(--primary); }

        /* ── Remember ── */
        .form-extras {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 2rem;
        }
        .remember-label {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.875rem; color: var(--text-muted); cursor: pointer;
            font-weight: 500;
        }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px; accent-color: var(--primary);
            cursor: pointer; border-radius: 4px;
        }

        /* ── Submit button ── */
        .btn-login {
            width: 100%; padding: 0.875rem;
            background: var(--primary);
            border: none; border-radius: 12px; color: white;
            font-family: inherit; font-size: 1rem; font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-login:hover { 
            background: var(--primary-light); 
            transform: translateY(-2px); 
            box-shadow: 0 10px 20px -10px rgba(37, 99, 235, 0.5); 
        }
        .btn-login:active { transform: translateY(0); }

        /* ── Demo ── */
        .demo-section {
            margin-top: 2.5rem;
            border-top: 1px solid var(--border);
            padding-top: 1.5rem;
        }
        .demo-title {
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;
            color: #94a3b8; font-weight: 700; margin-bottom: 1rem; text-align: center;
        }
        .demo-badges {
            display: flex; gap: 0.5rem; justify-content: center;
        }
        .demo-badge {
            padding: 0.4rem 0.75rem;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.75rem; font-weight: 600; color: var(--text-muted);
            cursor: pointer; transition: all 0.2s;
            display: flex; align-items: center; gap: 0.4rem;
        }
        .demo-badge:hover {
            border-color: var(--primary-light);
            color: var(--primary);
            background: white;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .login-container { flex-direction: column; max-width: 480px; }
            .login-branding { padding: 3rem 2rem; }
            .login-form-panel { padding: 3rem 2rem; }
            .branding-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <!-- Left Branding Panel -->
        <div class="login-branding">
            <div class="branding-header">
                <div class="logo-box">
                    <i data-lucide="book-open" style="width:32px; height:32px"></i>
                </div>
                <h1 class="branding-title">Sistem Informasi<br><span>E-Rapot</span></h1>
                <p class="branding-subtitle">Platform manajemen akademik profesional untuk memantau perkembangan dan hasil belajar peserta didik secara komprehensif.</p>
            </div>
            <div class="branding-footer">
                <p>&copy; {{ date('Y') }} SMPN 1 Surabaya</p>
                <p style="opacity: 0.6; margin-top: 0.25rem;">NPSN: 20532385</p>
            </div>
        </div>

        <!-- Right Form Panel -->
        <div class="login-form-panel">
            <div class="form-header">
                <h2 class="form-title">Selamat Datang</h2>
                <p class="form-subtitle">Silakan masuk menggunakan akun Anda</p>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i data-lucide="check-circle" style="width:20px"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <i data-lucide="alert-circle" style="width:20px"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email Pengguna</label>
                    <div class="input-wrap">
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ old('email') }}"
                            placeholder="nama@smpn1sby.sch.id"
                            autocomplete="email"
                            autofocus
                            required
                        >
                        <i data-lucide="mail" class="input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                        >
                        <i data-lucide="lock" class="input-icon"></i>
                        <button type="button" id="togglePassword" class="password-toggle" title="Tampilkan/Sembunyikan password">
                            <i data-lucide="eye" id="toggleIcon" style="width:18px"></i>
                        </button>
                    </div>
                </div>

                <div class="form-extras">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat sesi saya
                    </label>
                </div>

                <button id="loginBtn" type="submit" class="btn-login">
                    Masuk <i data-lucide="arrow-right" style="width:18px"></i>
                </button>
            </form>

            <!-- Quick fill demo accounts -->
            <div class="demo-section">
                <div class="demo-title">Akses Cepat Demo</div>
                <div class="demo-badges">
                    <div class="demo-badge" onclick="fillDemo('admin@smpn1sby.sch.id','Admin@1234')">
                        <i data-lucide="shield-check" style="width:14px"></i> Admin
                    </div>
                    <div class="demo-badge" onclick="fillDemo('guru01@smpn1sby.sch.id','Guru@1234')">
                        <i data-lucide="users" style="width:14px"></i> Guru
                    </div>
                    <div class="demo-badge" onclick="fillDemo('siswa001@smpn1sby.sch.id','Siswa@1234')">
                        <i data-lucide="user" style="width:14px"></i> Siswa
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function fillDemo(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
            document.getElementById('email').focus();
        }

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                toggleIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        });

        const successAlerts = document.querySelectorAll('.alert-success');
        if (successAlerts.length > 0) {
            setTimeout(() => {
                successAlerts.forEach(alert => {
                    alert.style.transition = 'all 0.4s ease';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 400);
                });
            }, 3000);
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.innerHTML = 'Memproses... <i data-lucide="loader-2" class="animate-spin" style="width:18px"></i>';
            lucide.createIcons();
            btn.style.opacity = '0.9';
            btn.disabled = true;
        });
    </script>
</body>
</html>
