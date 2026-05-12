{{-- Siswa sidebar navigation partial --}}
<div class="nav-section-title">Utama</div>
<a href="{{ route('siswa.dashboard') }}" class="nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
    <i data-lucide="layout-dashboard" class="nav-icon"></i> Dashboard
</a>

<div class="nav-section-title">Akademik</div>
<a href="{{ route('siswa.nilai') }}" class="nav-item {{ request()->routeIs('siswa.nilai') ? 'active' : '' }}">
    <i data-lucide="file-text" class="nav-icon"></i> Nilai Saya
</a>
<a href="{{ route('siswa.rapot') }}" class="nav-item {{ request()->routeIs('siswa.rapot') ? 'active' : '' }}">
    <i data-lucide="printer" class="nav-icon"></i> Rapot Saya
</a>
<a href="{{ route('siswa.absensi') }}" class="nav-item {{ request()->routeIs('siswa.absensi') ? 'active' : '' }}">
    <i data-lucide="calendar-check" class="nav-icon"></i> Kehadiran
</a>
<a href="{{ route('siswa.ekskul') }}" class="nav-item {{ request()->routeIs('siswa.ekskul') ? 'active' : '' }}">
    <i data-lucide="award" class="nav-icon"></i> Ekstrakurikuler
</a>
