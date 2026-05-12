<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan | E-Rapot SMPN 1</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #0f0f1a; color: #e2e8f0; font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .container { text-align: center; padding: 2rem; max-width: 480px; }
        .code { font-size: 7rem; font-weight: 800; background: linear-gradient(135deg, #4f46e5, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1; margin-bottom: 0.5rem; }
        .emoji { font-size: 4rem; margin-bottom: 1rem; }
        h1 { font-size: 1.5rem; font-weight: 700; color: #f1f5f9; margin-bottom: 0.5rem; }
        p { color: #94a3b8; font-size: 0.9rem; line-height: 1.6; margin-bottom: 1.5rem; }
        .btn-group { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }
        a { display: inline-block; padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #4f46e5, #7c3aed); border-radius: 10px; color: white; text-decoration: none; font-weight: 600; font-size: 0.875rem; transition: transform 0.2s; }
        a.secondary { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1); }
        a:hover { transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="container">
        <div class="emoji">🔍</div>
        <div class="code">404</div>
        <h1>Halaman Tidak Ditemukan</h1>
        <p>Halaman yang Anda cari tidak ada atau mungkin telah dipindahkan. Periksa kembali URL atau kembali ke beranda.</p>
        <div class="btn-group">
            <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}">← Kembali</a>
            <a href="{{ route('login') }}" class="secondary">🏠 Beranda</a>
        </div>
    </div>
</body>
</html>
