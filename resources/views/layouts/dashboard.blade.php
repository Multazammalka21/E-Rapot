<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — E-Rapot SMPN 1 Surabaya</title>
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
            --warning: #f59e0b;
            --danger: #ef4444; 
            --info: #3b82f6;
            --bg: #f8fafc; 
            --sidebar-bg: #ffffff;
            --surface: #ffffff; 
            --surface-2: #f1f5f9;
            --border: #e2e8f0; 
            --border-2: #cbd5e1;
            --text: #1e293b; 
            --text-muted: #64748b; 
            --text-soft: #475569;
            --sidebar-w: 270px; 
            --header-h: 72px;
            --radius: 16px; 
            --radius-sm: 10px;
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: var(--bg); 
            color: var(--text); 
            min-height: 100vh; 
            display: flex;
            -webkit-font-smoothing: antialiased;
        }

        /* ══ SIDEBAR ══════════════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w); height: 100vh; background: var(--sidebar-bg);
            border-right: 1px solid var(--border); position: fixed; left: 0; top: 0; z-index: 100;
            display: flex; flex-direction: column; transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-logo {
            padding: 1.5rem; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 0.85rem;
        }
        .logo-icon {
            width: 42px; height: 42px; border-radius: 12px;
            background: linear-gradient(135deg, var(--primary), var(--info));
            display: flex; align-items: center; justify-content: center;
            color: white; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .logo-text { line-height: 1.2; }
        .logo-text strong { font-size: 0.95rem; font-weight: 800; color: var(--accent); display: block; letter-spacing: -0.02em; }
        .logo-text span { font-size: 0.72rem; color: var(--text-muted); font-weight: 500; }

        .sidebar-user {
            padding: 1.25rem 1.5rem; background: var(--surface-2);
            margin: 1rem 0.75rem; border-radius: var(--radius);
            display: flex; align-items: center; gap: 0.85rem;
            border: 1px solid var(--border);
        }
        .user-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; font-weight: 700; color: white; flex-shrink: 0;
            border: 2px solid white; box-shadow: var(--shadow);
        }
        .user-info { overflow: hidden; }
        .user-name { font-size: 0.85rem; font-weight: 700; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 0.7rem; color: var(--primary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 1px; }

        .sidebar-nav { flex: 1; padding: 0.5rem 0.75rem; overflow-y: auto; }
        .nav-section-title {
            font-size: 0.68rem; font-weight: 700; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.1em;
            padding: 1.25rem 0.75rem 0.5rem;
        }
        .nav-item {
            display: flex; align-items: center; gap: 0.85rem;
            padding: 0.75rem 0.85rem; border-radius: var(--radius-sm);
            color: var(--text-soft); font-size: 0.875rem; font-weight: 600;
            text-decoration: none; transition: all 0.2s ease; margin-bottom: 4px;
        }
        .nav-item:hover { background: var(--surface-2); color: var(--primary); }
        .nav-item.active { background: rgba(37, 99, 235, 0.08); color: var(--primary); }
        .nav-item.active .nav-icon { color: var(--primary); }
        .nav-icon { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto; font-size: 0.65rem; font-weight: 700;
            background: var(--primary); color: white; padding: 2px 8px; border-radius: 20px;
        }

        .sidebar-bottom {
            padding: 1rem 0.75rem; border-top: 1px solid var(--border);
        }
        .btn-logout {
            display: flex; align-items: center; gap: 0.85rem; width: 100%;
            padding: 0.75rem 0.85rem; border-radius: var(--radius-sm);
            background: transparent; border: none; cursor: pointer;
            color: var(--danger); font-size: 0.875rem; font-family: inherit;
            font-weight: 600; transition: all 0.2s ease;
        }
        .btn-logout:hover { background: #fee2e2; }

        /* ══ MAIN CONTENT ══════════════════════════════════════════════ */
        .main {
            margin-left: var(--sidebar-w); flex: 1; min-height: 100vh;
            display: flex; flex-direction: column; width: calc(100% - var(--sidebar-w));
        }

        /* ── Header ── */
        .header {
            height: var(--header-h); background: rgba(255, 255, 255, 0.8);
            border-bottom: 1px solid var(--border); backdrop-filter: blur(12px);
            position: sticky; top: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem;
        }
        .header-title h1 { font-size: 1.15rem; font-weight: 800; color: var(--accent); letter-spacing: -0.02em; }
        .header-title p { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; }
        .header-actions { display: flex; align-items: center; gap: 1.25rem; }
        .tahun-badge {
            background: var(--surface-2); border: 1px solid var(--border);
            color: var(--text); font-size: 0.75rem; font-weight: 700;
            padding: 0.4rem 0.85rem; border-radius: 30px;
            display: flex; align-items: center; gap: 0.4rem;
        }

        /* ── Page content ── */
        .content { flex: 1; padding: 2rem; max-width: 1400px; margin: 0 auto; width: 100%; }

        /* ══ CARDS & STATS ═══════════════════════════════════════════ */
        .stat-grid { display: grid; gap: 1.5rem; margin-bottom: 2rem; }
        .stat-grid-4 { grid-template-columns: repeat(4, 1fr); }
        .stat-grid-3 { grid-template-columns: repeat(3, 1fr); }
        .stat-grid-2 { grid-template-columns: repeat(2, 1fr); }
        
        .grid-2 { display: grid; grid-template-columns: 300px 1fr; gap: 1.5rem; }
        .grid-3 { display: grid; grid-template-columns: 250px 1fr 1fr; gap: 1.5rem; }

        .stat-card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); padding: 1.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            position: relative; overflow: hidden;
            box-shadow: var(--shadow);
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: var(--primary-light); }
        .stat-label { font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem; }
        .stat-value { font-size: 2.25rem; font-weight: 800; color: var(--accent); line-height: 1; letter-spacing: -0.02em; }
        .stat-icon-bg { position: absolute; right: -10px; bottom: -10px; font-size: 5rem; opacity: 0.05; transform: rotate(-10deg); color: var(--primary); }
        .stat-sub { font-size: 0.8rem; color: var(--text-soft); margin-top: 0.75rem; display: flex; align-items: center; gap: 0.35rem; }
        .trend-up { color: var(--success); font-weight: 700; }
        .trend-down { color: var(--danger); font-weight: 700; }

        /* ── Panels ── */
        .panel {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: var(--radius); overflow: hidden;
            box-shadow: var(--shadow); transition: box-shadow 0.3s ease;
        }
        .panel:hover { box-shadow: var(--shadow-md); }
        .panel-header {
            padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(248, 250, 252, 0.5);
        }
        .panel-title { font-size: 1rem; font-weight: 700; color: var(--accent); display: flex; align-items: center; gap: 0.6rem; }
        .panel-body { padding: 1.5rem; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; margin: -1.5rem; padding: 1.5rem; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 0.875rem; }
        th {
            padding: 1rem 1.25rem; text-align: left; font-size: 0.72rem;
            font-weight: 700; color: var(--text-muted); text-transform: uppercase;
            letter-spacing: 0.05em; border-bottom: 2px solid var(--border);
            background: var(--surface);
        }
        td { padding: 1.1rem 1.25rem; border-bottom: 1px solid var(--border); color: var(--text-soft); transition: all 0.2s; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: var(--surface-2); color: var(--accent); }

        /* ── Badges ── */
        .badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; gap: 0.35rem; }
        .badge-A { background: #ecfdf5; color: #059669; }
        .badge-B { background: #eff6ff; color: #2563eb; }
        .badge-C { background: #fffbeb; color: #d97706; }
        .badge-D { background: #fef2f2; color: #dc2626; }
        .badge-success { background: #ecfdf5; color: #059669; }
        .badge-warning { background: #fffbeb; color: #d97706; }
        .badge-info { background: #eff6ff; color: #2563eb; }

        /* ── Progress bars ── */
        .progress-container { margin-top: 0.5rem; }
        .progress-label { display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.35rem; }
        .progress-bar { background: var(--surface-2); border-radius: 20px; overflow: hidden; height: 8px; border: 1px solid var(--border); }
        .progress-fill { height: 100%; border-radius: 20px; background: linear-gradient(90deg, var(--primary), var(--info)); transition: width 1s cubic-bezier(0.4, 0, 0.2, 1); }

        /* ── Responsive ── */
        @media (max-width: 1200px) { .stat-grid-4 { grid-template-columns: repeat(2,1fr); } }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); width: 280px; }
            .sidebar.show { transform: translateX(0); }
            .main { margin-left: 0; width: 100%; }
            .header { padding: 0 1rem; }
            .content { padding: 1.25rem; }
            .stat-grid-4, .stat-grid-3 { grid-template-columns: 1fr; }
            .grid-2, .grid-3 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i data-lucide="graduation-cap"></i>
            </div>
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
                    <i data-lucide="log-out" class="nav-icon"></i> Logout
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
                    <div class="tahun-badge">
                        <i data-lucide="calendar" style="width:14px; height:14px"></i>
                        {{ $ta->nama }} • {{ ucfirst($ta->semester) }}
                    </div>
                @endif
                <div style="font-size:0.85rem; color:var(--text-muted); font-weight:600">
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </div>
            </div>
        </header>

        <div class="content">
            @yield('content')
        </div>
    </main>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
