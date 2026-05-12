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
            --primary: #2563eb;
            --primary-light: #60a5fa;
            --primary-dark: #1d4ed8;
            --accent: #0f172a;
            --success: #10b981;
            --danger: #ef4444;
            --bg: #f8fafc;
            --surface: #ffffff;
            --border: #e2e8f0;
            --text: #1e293b;
            --text-muted: #64748b;
            --shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ── Decorative background ── */
        .bg-decoration {
            position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden;
        }
        .blob {
            position: absolute; border-radius: 50%; filter: blur(100px); opacity: 0.2; animation: float 10s ease-in-out infinite;
        }
        .blob-1 { width: 500px; height: 500px; background: #2563eb; top: -10%; left: -10%; }
        .blob-2 { width: 400px; height: 400px; background: #06b6d4; bottom: -5%; right: -5%; animation-delay: -2s; }
        .blob-3 { width: 300px; height: 300px; background: #8b5cf6; top: 40%; right: 10%; animation-delay: -5s; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 30px) scale(0.9); }
        }

        /* ── Login card ── */
        .login-wrapper {
            position: relative; z-index: 10;
            width: 100%; max-width: 460px;
            padding: 1.5rem;
        }

        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 28px;
            padding: 2.75rem;
            box-shadow: var(--shadow);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo ── */
        .logo-wrap {
            text-align: center; margin-bottom: 2.5rem;
        }
        .logo-icon {
            width: 68px; height: 68px; border-radius: 20px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: inline-flex; align-items: center; justify-content: center;
            color: white; margin-bottom: 1.25rem;
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }
        .school-name {
            font-size: 1.35rem; font-weight: 800; color: var(--accent);
            letter-spacing: -0.025em; margin-bottom: 0.25rem;
        }
        .app-name {
            font-size: 0.8rem; color: var(--text-muted);
            font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;
        }

        /* ── Alert ── */
        .alert {
            border-radius: 12px; padding: 0.85rem 1rem;
            font-size: 0.875rem; margin-bottom: 1.5rem;
            display: flex; align-items: center; gap: 0.75rem;
            font-weight: 500;
        }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
        .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; }

        /* ── Form ── */
        .form-group { margin-bottom: 1.5rem; }
        .form-label {
            display: block; font-size: 0.8rem; font-weight: 700;
            color: var(--text); margin-bottom: 0.6rem;
            letter-spacing: 0.02em;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
            color: var(--text-muted); width: 18px; height: 18px; pointer-events: none;
        }
        .form-input {
            width: 100%; padding: 0.85rem 1rem 0.85rem 3rem;
            background: #f8fafc;
            border: 2px solid var(--border);
            border-radius: 14px; color: var(--text);
            font-family: inherit; font-size: 0.95rem; font-weight: 500;
            transition: all 0.2s ease; outline: none;
        }
        .form-input::placeholder { color: #94a3b8; }
        .form-input:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        .form-input.is-invalid { border-color: var(--danger); }

        /* ── Remember ── */
        .form-extras {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.75rem;
        }
        .remember-label {
            display: flex; align-items: center; gap: 0.6rem;
            font-size: 0.9rem; color: var(--text-soft); cursor: pointer;
            font-weight: 500;
        }
        .remember-label input[type="checkbox"] {
            width: 18px; height: 18px; accent-color: var(--primary);
            cursor: pointer;
        }

        /* ── Submit button ── */
        .btn-login {
            width: 100%; padding: 1rem;
            background: var(--primary);
            border: none; border-radius: 14px; color: white;
            font-family: inherit; font-size: 1rem; font-weight: 700;
            cursor: pointer; letter-spacing: 0.01em;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }
        .btn-login:hover { 
            background: var(--primary-dark); 
            transform: translateY(-1px); 
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3); 
        }
        .btn-login:active { transform: translateY(0); }

        /* ── Role hint cards ── */
        .role-hint { margin-top: 2.5rem; }
        .role-hint-title {
            text-align: center; font-size: 0.75rem; color: var(--text-muted);
            margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.1em;
            font-weight: 700; display: flex; align-items: center; gap: 1rem;
        }
        .role-hint-title::before, .role-hint-title::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }
        .role-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem; }
        .role-card {
            background: #f8fafc; border: 1px solid var(--border);
            border-radius: 14px; padding: 0.75rem 0.5rem; text-align: center;
            cursor: pointer; transition: all 0.2s ease;
        }
        .role-card:hover { 
            background: white; 
            border-color: var(--primary); 
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .role-card-icon { 
            width: 24px; height: 24px; margin: 0 auto 0.5rem; color: var(--primary);
        }
        .role-card-label { font-size: 0.75rem; color: var(--text); font-weight: 700; }

        /* ── Footer ── */
        .login-footer {
            text-align: center; margin-top: 2rem;
            font-size: 0.85rem; color: var(--text-muted);
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="bg-decoration">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-wrap">
                <div class="logo-icon">
                    <i data-lucide="graduation-cap" style="width:36px; height:36px"></i>
                </div>
                <div class="school-name">SMPN 1 Surabaya</div>
                <div class="app-name">Sistem Rapot Elektronik</div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i data-lucide="check-circle" style="width:18px"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">
                    <i data-lucide="alert-circle" style="width:18px"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email / Akun</label>
                    <div class="input-wrap">
                        <i data-lucide="mail" class="input-icon"></i>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ old('email') }}"
                            placeholder="nama@sekolah.sch.id"
                            autocomplete="email"
                            autofocus
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap">
                        <i data-lucide="lock" class="input-icon"></i>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="••••••••"
                            autocomplete="current-password"
                            required
                        >
                    </div>
                </div>

                <div class="form-extras">
                    <label class="remember-label">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Ingat saya
                    </label>
                </div>

                <button id="loginBtn" type="submit" class="btn-login">
                    Masuk ke Sistem <i data-lucide="arrow-right" style="width:18px"></i>
                </button>
            </form>

            <!-- Quick fill demo accounts -->
            <div class="role-hint">
                <div class="role-hint-title">Demo Akun</div>
                <div class="role-cards">
                    <div class="role-card" onclick="fillDemo('admin@smpn1sby.sch.id','Admin@1234')">
                        <i data-lucide="shield-check" class="role-card-icon"></i>
                        <div class="role-card-label">Admin</div>
                    </div>
                    <div class="role-card" onclick="fillDemo('guru01@smpn1sby.sch.id','Guru@1234')">
                        <i data-lucide="users" class="role-card-icon"></i>
                        <div class="role-card-label">Guru</div>
                    </div>
                    <div class="role-card" onclick="fillDemo('siswa001@smpn1sby.sch.id','Siswa@1234')">
                        <i data-lucide="user" class="role-card-icon"></i>
                        <div class="role-card-label">Siswa</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-footer">
            &copy; {{ date('Y') }} SMPN 1 Surabaya — E-Rapot v1.1
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();

        function fillDemo(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
            document.getElementById('email').focus();
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.innerHTML = 'Memproses... <i data-lucide="loader-2" class="animate-spin" style="width:18px"></i>';
            lucide.createIcons();
            btn.style.opacity = '0.7';
            btn.disabled = true;
        });
    </script>
</body>
</html>
