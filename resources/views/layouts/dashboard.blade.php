<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — E-Rapot SMPN 1 Surabaya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --primary: #4f46e5; --primary-light: #818cf8; --primary-dark: #3730a3;
            --accent: #06b6d4; --success: #10b981; --warning: #f59e0b;
            --danger: #ef4444; --info: #3b82f6;
            --bg: #0f0f1a; --sidebar-bg: #0d0d1f;
            --surface: rgba(255,255,255,0.04); --surface-2: rgba(255,255,255,0.07);
            --border: rgba(255,255,255,0.08); --border-2: rgba(255,255,255,0.12);
            --text: #f1f5f9; --text-muted: #64748b; --text-soft: #94a3b8;
            --sidebar-w: 260px; --header-h: 64px;
            --radius: 14px; --radius-sm: 8px;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        /* ══ SIDEBAR ══════════════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w); min-height: 100vh; background: var(--sidebar-bg);
            border-right: 1px solid var(--border); position: fixed; left: 0; top: 0; z-index: 100;
            display: flex; flex-direction: column; transition: transform 0.3s ease;
        }
        .sidebar-logo {
            padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .logo-icon {
            width: 40px; height: 40px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; flex-shrink: 0;
        }
        .logo-text { line-height: 1.2; }
        .logo-text strong { font-size: 0.85rem; font-weight: 700; color: var(--text); display: block; }
        .logo-text span { font-size: 0.7rem; color: var(--text-muted); }

        .sidebar-user {
            padding: 1rem 1.5rem; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 0.75rem;
        }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: white; flex-shrink: 0;
        }
        .user-info { overflow: hidden; }
        .user-name { font-size: 0.8rem; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 0.68rem; color: var(--primary-light); text-transform: uppercase; letter-spacing: 0.05em; }

        .sidebar-nav { flex: 1; padding: 1rem 0.75rem; overflow-y: auto; }
        .nav-section-title {
            font-size: 0.65rem; font-weight: 600; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.08em;
            padding: 0.5rem 0.75rem; margin-top: 0.5rem;
        }
        .nav-item {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.65rem 0.75rem; border-radius: var(--radius-sm);
            color: var(--text-soft); font-size: 0.875rem; font-weight: 500;
            text-decoration: none; transition: all 0.15s ease; margin-bottom: 2px;
        }
        .nav-item:hover { background: var(--surface-2); color: var(--text); }
        .nav-item.active { background: rgba(79,70,229,0.15); color: var(--primary-light); }
        .nav-item.active .nav-icon { color: var(--primary-light); }
        .nav-icon { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto; font-size: 0.65rem; font-weight: 700;
            background: var(--primary); color: white; padding: 2px 7px; border-radius: 20px;
        }

        .sidebar-bottom {
            padding: 0.75rem; border-top: 1px solid var(--border);
        }
        .btn-logout {
            display: flex; align-items: center; gap: 0.75rem; width: 100%;
            padding: 0.65rem 0.75rem; border-radius: var(--radius-sm);
            background: none; border: none; cursor: pointer;
            color: var(--text-muted); font-size: 0.875rem; font-family: 'Inter', sans-serif;
            transition: all 0.15s ease;
        }
        .btn-logout:hover { background: rgba(239,68,68,0.1); color: #fca5a5; }

        /* ══ MAIN CONTENT ══════════════════════════════════════════════ */
        .main {
            margin-left: var(--sidebar-w); flex: 1; min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── Header ── */
        .header {
            height: var(--header-h); background: rgba(15,15,26,0.9);
            border-bottom: 1px solid var(--border); backdrop-filter: blur(12px);
            position: sticky; top: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem;
        }
        .header-title h1 { font-size: 1rem; font-weight: 700; color: var(--text); }
        .header-title p { font-size: 0.75rem; color: var(--text-muted); }
        .header-actions { display: flex; align-items: center; gap: 1rem; }
        .tahun-badge {
            background: rgba(79,70,229,0.15); border: 1px solid rgba(79,70,229,0.3);
            color: var(--primary-light); font-size: 0.75rem; font-weight: 600;
            padding: 0.35rem 0.75rem; border-radius: 20px;
        }

        /* ── Page content ── */
        .content { flex: 1; padding: 1.5rem; }

        /* ══ CARDS & STATS ═══════════════════════════════════════════ */
        .stat-grid { display: grid; gap: 1rem; margin-bottom: 1.5rem; }
        .stat-grid-4 { grid-template-columns: repeat(4, 1fr); }
        .stat-grid-3 { grid-template-columns: repeat(3, 1fr); }
        .stat-grid-2 { grid-template-columns: repeat(2, 1fr); }

        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.25rem;
            transition: all 0.2s ease; position: relative; overflow: hidden;
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: var(--card-accent, linear-gradient(90deg, var(--primary), var(--accent)));
        }
        .stat-card:hover { background: var(--surface-2); transform: translateY(-2px); }
        .stat-label { font-size: 0.72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.5rem; }
        .stat-value { font-size: 2rem; font-weight: 800; color: var(--text); line-height: 1; }
        .stat-icon { position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); font-size: 2.5rem; opacity: 0.1; }
        .stat-sub { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.4rem; }

        /* ── Panels ── */
        .panel {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); overflow: hidden;
        }
        .panel-header {
            padding: 1rem 1.25rem; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }
        .panel-title { font-size: 0.875rem; font-weight: 700; color: var(--text); }
        .panel-body { padding: 1.25rem; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.8rem; }
        th {
            padding: 0.6rem 1rem; text-align: left; font-size: 0.7rem;
            font-weight: 700; color: var(--text-muted); text-transform: uppercase;
            letter-spacing: 0.06em; border-bottom: 1px solid var(--border);
        }
        td { padding: 0.75rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.04); color: var(--text-soft); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--surface-2); color: var(--text); }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 20px; font-size: 0.7rem; font-weight: 600; }
        .badge-A { background: rgba(16,185,129,0.15); color: #6ee7b7; }
        .badge-B { background: rgba(59,130,246,0.15); color: #93c5fd; }
        .badge-C { background: rgba(245,158,11,0.15); color: #fcd34d; }
        .badge-D { background: rgba(239,68,68,0.15); color: #fca5a5; }
        .badge-success { background: rgba(16,185,129,0.15); color: #6ee7b7; }
        .badge-warning { background: rgba(245,158,11,0.15); color: #fcd34d; }
        .badge-info { background: rgba(59,130,246,0.15); color: #93c5fd; }

        /* ── Progress bars ── */
        .progress-bar { background: rgba(255,255,255,0.06); border-radius: 20px; overflow: hidden; height: 6px; }
        .progress-fill { height: 100%; border-radius: 20px; background: linear-gradient(90deg, var(--primary), var(--accent)); transition: width 1s ease; }

        /* ── Chart donut (pure CSS) ── */
        .donut-wrap { display: flex; align-items: center; gap: 1.5rem; }
        .donut-legend { display: grid; gap: 0.5rem; }
        .legend-item { display: flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; }
        .legend-dot { width: 10px; height: 10px; border-radius: 50%; }

        /* ── Grid layouts ── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; }

        /* ── Responsive ── */
        @media (max-width: 1024px) { .stat-grid-4 { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .stat-grid-4, .stat-grid-3 { grid-template-columns: 1fr 1fr; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">📋</div>
            <div class="logo-text">
                <strong>E-Rapot</strong>
                <span>SMPN 1 Surabaya</span>
            </div>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            @yield('sidebar-nav')
        </nav>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <span>🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="main">
        <header class="header">
            <div class="header-title">
                <h1>@yield('page-title', 'Dashboard')</h1>
                <p>@yield('page-subtitle', 'Selamat datang di E-Rapot')</p>
            </div>
            <div class="header-actions">
                @if(isset($ta))
                    <span class="tahun-badge">📅 {{ $ta->nama }} {{ ucfirst($ta->semester) }}</span>
                @endif
                <span style="font-size:0.8rem; color:var(--text-muted)">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
