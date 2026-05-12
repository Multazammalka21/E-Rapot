<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Rapot SMPN 1 Surabaya — Sistem Informasi Manajemen Rapot Elektronik">
    <title>Login — E-Rapot SMPN 1 Surabaya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #4f46e5;
            --primary-light: #818cf8;
            --primary-dark: #3730a3;
            --accent: #06b6d4;
            --success: #10b981;
            --danger: #ef4444;
            --bg: #0f0f1a;
            --surface: rgba(255,255,255,0.05);
            --surface-hover: rgba(255,255,255,0.08);
            --border: rgba(255,255,255,0.1);
            --text: #f1f5f9;
            --text-muted: #94a3b8;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* ── Animated background ── */
        .bg-orbs {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
        }
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: 0.35; animation: float 8s ease-in-out infinite;
        }
        .orb-1 { width: 500px; height: 500px; background: radial-gradient(circle, #4f46e5, transparent); top: -100px; left: -100px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: radial-gradient(circle, #06b6d4, transparent); bottom: -80px; right: -80px; animation-delay: 3s; }
        .orb-3 { width: 300px; height: 300px; background: radial-gradient(circle, #8b5cf6, transparent); top: 50%; left: 50%; animation-delay: 1.5s; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.05); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
        }

        /* ── Grid lines bg ── */
        .bg-grid {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        /* ── Login card ── */
        .login-wrapper {
            position: relative; z-index: 10;
            width: 100%; max-width: 440px;
            padding: 1.5rem;
        }

        .login-card {
            background: rgba(15, 15, 30, 0.8);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem;
            backdrop-filter: blur(20px);
            box-shadow: 0 25px 50px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.1);
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo ── */
        .logo-wrap {
            text-align: center; margin-bottom: 2rem;
        }
        .logo-icon {
            width: 64px; height: 64px; border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 28px; margin-bottom: 1rem;
            box-shadow: 0 8px 32px rgba(79,70,229,0.4);
            animation: pulse-glow 3s ease-in-out infinite;
        }
        @keyframes pulse-glow {
            0%, 100% { box-shadow: 0 8px 32px rgba(79,70,229,0.4); }
            50% { box-shadow: 0 8px 48px rgba(79,70,229,0.7); }
        }
        .school-name {
            font-size: 1.25rem; font-weight: 700; color: var(--text);
            letter-spacing: -0.02em;
        }
        .app-name {
            font-size: 0.8rem; color: var(--primary-light);
            font-weight: 500; letter-spacing: 0.05em; text-transform: uppercase;
        }

        /* ── Alert ── */
        .alert-error {
            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);
            color: #fca5a5; border-radius: 10px; padding: 0.75rem 1rem;
            font-size: 0.875rem; margin-bottom: 1.5rem;
            display: flex; align-items: flex-start; gap: 0.5rem;
        }
        .alert-success {
            background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3);
            color: #6ee7b7; border-radius: 10px; padding: 0.75rem 1rem;
            font-size: 0.875rem; margin-bottom: 1.5rem;
        }

        /* ── Form ── */
        .form-group { margin-bottom: 1.25rem; }
        .form-label {
            display: block; font-size: 0.8rem; font-weight: 600;
            color: var(--text-muted); margin-bottom: 0.5rem;
            text-transform: uppercase; letter-spacing: 0.05em;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: var(--text-muted); font-size: 16px; pointer-events: none;
        }
        .form-input {
            width: 100%; padding: 0.75rem 1rem 0.75rem 2.75rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 12px; color: var(--text);
            font-family: 'Inter', sans-serif; font-size: 0.9rem;
            transition: all 0.2s ease; outline: none;
        }
        .form-input::placeholder { color: var(--text-muted); }
        .form-input:focus {
            border-color: var(--primary-light);
            background: rgba(79,70,229,0.08);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.2);
        }
        .form-input.is-invalid { border-color: var(--danger); }
        .invalid-feedback { color: #fca5a5; font-size: 0.8rem; margin-top: 0.4rem; }

        /* ── Remember & Forgot ── */
        .form-extras {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .remember-label {
            display: flex; align-items: center; gap: 0.5rem;
            font-size: 0.85rem; color: var(--text-muted); cursor: pointer;
        }
        .remember-label input[type="checkbox"] {
            width: 16px; height: 16px; accent-color: var(--primary);
        }

        /* ── Submit button ── */
        .btn-login {
            width: 100%; padding: 0.875rem;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            border: none; border-radius: 12px; color: white;
            font-family: 'Inter', sans-serif; font-size: 0.95rem; font-weight: 600;
            cursor: pointer; letter-spacing: 0.02em;
            transition: all 0.2s ease; position: relative; overflow: hidden;
        }
        .btn-login::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0; transition: opacity 0.2s;
        }
        .btn-login:hover::before { opacity: 1; }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 12px 30px rgba(79,70,229,0.4); }
        .btn-login:active { transform: translateY(0); }

        /* ── Role hint cards ── */
        .role-hint { margin-top: 2rem; }
        .role-hint-title {
            text-align: center; font-size: 0.75rem; color: var(--text-muted);
            margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;
        }
        .role-cards { display: grid; grid-template-columns: repeat(3,1fr); gap: 0.5rem; }
        .role-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 10px; padding: 0.6rem 0.5rem; text-align: center;
            cursor: pointer; transition: all 0.2s ease;
        }
        .role-card:hover { background: var(--surface-hover); border-color: var(--primary-light); }
        .role-card-icon { font-size: 1.2rem; margin-bottom: 0.25rem; }
        .role-card-label { font-size: 0.7rem; color: var(--text-muted); font-weight: 500; }

        /* ── Footer ── */
        .login-footer {
            text-align: center; margin-top: 1.5rem;
            font-size: 0.75rem; color: var(--text-muted);
        }
    </style>
</head>
<body>
    <div class="bg-orbs">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>
    <div class="bg-grid"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <!-- Logo -->
            <div class="logo-wrap">
                <div class="logo-icon">📋</div>
                <div class="school-name">SMPN 1 Surabaya</div>
                <div class="app-name">E-Rapot • Sistem Rapot Elektronik</div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert-success">✅ {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert-error">
                    <span>⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Login Form -->
            <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email / Akun</label>
                    <div class="input-wrap">
                        <span class="input-icon">✉️</span>
                        <input
                            id="email"
                            type="email"
                            name="email"
                            class="form-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            value="{{ old('email') }}"
                            placeholder="contoh@smpn1sby.sch.id"
                            autocomplete="email"
                            autofocus
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Masukkan password"
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
                    Masuk ke E-Rapot →
                </button>
            </form>

            <!-- Quick fill demo accounts -->
            <div class="role-hint">
                <div class="role-hint-title">Demo akun</div>
                <div class="role-cards">
                    <div class="role-card" onclick="fillDemo('admin@smpn1sby.sch.id','Admin@1234')" title="Admin">
                        <div class="role-card-icon">🛡️</div>
                        <div class="role-card-label">Admin</div>
                    </div>
                    <div class="role-card" onclick="fillDemo('guru01@smpn1sby.sch.id','Guru@1234')" title="Guru">
                        <div class="role-card-icon">👨‍🏫</div>
                        <div class="role-card-label">Guru</div>
                    </div>
                    <div class="role-card" onclick="fillDemo('siswa001@smpn1sby.sch.id','Siswa@1234')" title="Siswa">
                        <div class="role-card-icon">🎓</div>
                        <div class="role-card-label">Siswa</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="login-footer">
            &copy; {{ date('Y') }} SMPN 1 Surabaya — E-Rapot v1.0
        </div>
    </div>

    <script>
        function fillDemo(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
            document.getElementById('email').focus();
        }

        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('loginBtn');
            btn.textContent = 'Memproses...';
            btn.style.opacity = '0.7';
            btn.disabled = true;
        });
    </script>
</body>
</html>
