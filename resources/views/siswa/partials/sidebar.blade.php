{{-- Siswa sidebar navigation partial --}}
<div class="nav-section-title">Utama</div>
<a href="{{ route('siswa.dashboard') }}" class="nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
    <span class="nav-icon">📊</span> Dashboard
</a>

<div class="nav-section-title">Akademik</div>
<a href="{{ route('siswa.nilai') }}" class="nav-item {{ request()->routeIs('siswa.nilai') ? 'active' : '' }}">
    <span class="nav-icon">📝</span> Nilai Saya
</a>
<a href="{{ route('siswa.rapot') }}" class="nav-item {{ request()->routeIs('siswa.rapot') ? 'active' : '' }}">
    <span class="nav-icon">📄</span> Rapot Saya
</a>
<a href="{{ route('siswa.absensi') }}" class="nav-item {{ request()->routeIs('siswa.absensi') ? 'active' : '' }}">
    <span class="nav-icon">📅</span> Kehadiran
</a>
<a href="{{ route('siswa.ekskul') }}" class="nav-item {{ request()->routeIs('siswa.ekskul') ? 'active' : '' }}">
    <span class="nav-icon">🏅</span> Ekstrakurikuler
</a>
