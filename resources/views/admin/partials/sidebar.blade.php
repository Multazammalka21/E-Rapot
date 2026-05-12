{{-- Shared admin sidebar nav with active state highlighting --}}
<div class="nav-section-title">Utama</div>
<a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <span class="nav-icon">📊</span> Dashboard
</a>

<div class="nav-section-title">Manajemen</div>
<a href="{{ route('admin.guru.index') }}" class="nav-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
    <span class="nav-icon">👨‍🏫</span> Data Guru
    <span class="nav-badge">{{ \App\Models\Guru::count() }}</span>
</a>
<a href="{{ route('admin.siswa.index') }}" class="nav-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
    <span class="nav-icon">🎓</span> Data Siswa
    <span class="nav-badge">{{ \App\Models\Siswa::count() }}</span>
</a>
<a href="{{ route('admin.kelas.index') }}" class="nav-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
    <span class="nav-icon">🏫</span> Data Kelas
</a>
<a href="{{ route('admin.mapel.index') }}" class="nav-item {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
    <span class="nav-icon">📚</span> Mata Pelajaran
</a>

<div class="nav-section-title">Akademik</div>
<a href="#" class="nav-item"><span class="nav-icon">📝</span> Nilai Siswa</a>
<a href="#" class="nav-item"><span class="nav-icon">🗓️</span> Tahun Ajaran</a>
<a href="#" class="nav-item"><span class="nav-icon">🏅</span> Ekstrakurikuler</a>

<div class="nav-section-title">Laporan</div>
<a href="#" class="nav-item"><span class="nav-icon">📄</span> Cetak Rapot PDF</a>
<a href="#" class="nav-item"><span class="nav-icon">📋</span> Audit Log</a>
