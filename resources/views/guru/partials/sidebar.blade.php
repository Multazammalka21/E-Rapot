{{-- Guru sidebar navigation partial --}}
<div class="nav-section-title">Utama</div>
<a href="{{ route('guru.dashboard') }}" class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
    <span class="nav-icon">📊</span> Dashboard
</a>

<div class="nav-section-title">Nilai</div>
<a href="{{ route('guru.nilai.index') }}" class="nav-item {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}">
    <span class="nav-icon">📝</span> Input Nilai
</a>

<div class="nav-section-title">Rapot</div>
<a href="{{ route('guru.rapot.index') }}" class="nav-item {{ request()->routeIs('guru.rapot.*') ? 'active' : '' }}">
    <span class="nav-icon">📄</span> Cetak Rapot PDF
</a>

<div class="nav-section-title">Import</div>
<a href="{{ route('guru.import.index') }}" class="nav-item {{ request()->routeIs('guru.import.*') ? 'active' : '' }}">
    <span class="nav-icon">📤</span> Import Nilai Excel
</a>
