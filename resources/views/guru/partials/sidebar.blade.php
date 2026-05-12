{{-- Guru sidebar navigation partial --}}
<div class="nav-section-title">Utama</div>
<a href="{{ route('guru.dashboard') }}" class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
    <i data-lucide="layout-dashboard" class="nav-icon"></i> Dashboard
</a>

<div class="nav-section-title">Nilai</div>
<a href="{{ route('guru.nilai.index') }}" class="nav-item {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}">
    <i data-lucide="file-edit" class="nav-icon"></i> Input Nilai
</a>

<div class="nav-section-title">Rapot</div>
<a href="{{ route('guru.rapot.index') }}" class="nav-item {{ request()->routeIs('guru.rapot.*') ? 'active' : '' }}">
    <i data-lucide="printer" class="nav-icon"></i> Cetak Rapot PDF
</a>

<div class="nav-section-title">Import</div>
<a href="{{ route('guru.import.index') }}" class="nav-item {{ request()->routeIs('guru.import.*') ? 'active' : '' }}">
    <i data-lucide="file-up" class="nav-icon"></i> Import Nilai Excel
</a>
