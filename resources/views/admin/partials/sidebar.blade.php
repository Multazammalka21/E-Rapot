{{-- Shared admin sidebar nav with active state highlighting --}}
<div class="nav-section-title">Utama</div>
<a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i data-lucide="layout-dashboard" class="nav-icon"></i> Dashboard
</a>

<div class="nav-section-title">Manajemen</div>
<a href="{{ route('admin.guru.index') }}" class="nav-item {{ request()->routeIs('admin.guru.*') ? 'active' : '' }}">
    <i data-lucide="users" class="nav-icon"></i> Data Guru
    <span class="nav-badge">{{ \App\Models\Guru::count() }}</span>
</a>
<a href="{{ route('admin.siswa.index') }}" class="nav-item {{ request()->routeIs('admin.siswa.*') ? 'active' : '' }}">
    <i data-lucide="user-round" class="nav-icon"></i> Data Siswa
    <span class="nav-badge">{{ \App\Models\Siswa::count() }}</span>
</a>
<a href="{{ route('admin.kelas.index') }}" class="nav-item {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}">
    <i data-lucide="school" class="nav-icon"></i> Data Kelas
</a>
<a href="{{ route('admin.mapel.index') }}" class="nav-item {{ request()->routeIs('admin.mapel.*') ? 'active' : '' }}">
    <i data-lucide="book-open" class="nav-icon"></i> Mata Pelajaran
</a>

<div class="nav-section-title">Akademik</div>
<a href="#" class="nav-item"><i data-lucide="file-text" class="nav-icon"></i> Nilai Siswa</a>
<a href="#" class="nav-item"><i data-lucide="calendar-days" class="nav-icon"></i> Tahun Ajaran</a>
<a href="#" class="nav-item"><i data-lucide="award" class="nav-icon"></i> Ekstrakurikuler</a>

<div class="nav-section-title">Laporan</div>
<a href="#" class="nav-item"><i data-lucide="printer" class="nav-icon"></i> Cetak Rapot PDF</a>
<a href="#" class="nav-item"><i data-lucide="clipboard-list" class="nav-icon"></i> Audit Log</a>
