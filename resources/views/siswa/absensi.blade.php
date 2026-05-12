@extends('layouts.dashboard')
@section('title', 'Kehadiran') @section('page-title', '📅 Kehadiran')
@section('page-subtitle', '{{ $ta->label }}')
@section('sidebar-nav') @include('siswa.partials.sidebar') @endsection

@section('content')
<div class="stat-grid stat-grid-4" style="margin-bottom:1rem">
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#10b981,#34d399)">
        <div class="stat-label">Hadir</div>
        <div class="stat-value" style="color:#6ee7b7">{{ $absensi?->hadir ?? 0 }}</div>
        <div class="stat-sub">hari</div><div class="stat-icon">✅</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#3b82f6,#60a5fa)">
        <div class="stat-label">Sakit</div>
        <div class="stat-value" style="color:#93c5fd">{{ $absensi?->sakit ?? 0 }}</div>
        <div class="stat-sub">hari</div><div class="stat-icon">🤒</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#f59e0b,#fbbf24)">
        <div class="stat-label">Izin</div>
        <div class="stat-value" style="color:#fcd34d">{{ $absensi?->izin ?? 0 }}</div>
        <div class="stat-sub">hari</div><div class="stat-icon">📝</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#ef4444,#f87171)">
        <div class="stat-label">Tanpa Keterangan</div>
        <div class="stat-value" style="color:#fca5a5">{{ $absensi?->alpha ?? 0 }}</div>
        <div class="stat-sub">hari</div><div class="stat-icon">⚠️</div>
    </div>
</div>
<div class="panel">
    <div class="panel-body" style="text-align:center;padding:2rem">
        <div style="font-size:3rem;font-weight:800;color:{{ ($absensi?->persentase_hadir ?? 100) >= 80 ? '#6ee7b7' : '#fca5a5' }}">
            {{ $absensi ? $absensi->persentase_hadir . '%' : '100%' }}
        </div>
        <div style="font-size:0.9rem;color:var(--text-muted);margin-top:0.5rem">Persentase kehadiran dari {{ $absensi?->total_hari ?? 100 }} hari efektif</div>
        <div class="progress-bar" style="max-width:400px;margin:1rem auto 0;height:10px">
            <div class="progress-fill" style="width:{{ $absensi?->persentase_hadir ?? 100 }}%;background:{{ ($absensi?->persentase_hadir ?? 100) >= 80 ? '#10b981' : '#ef4444' }}"></div>
        </div>
    </div>
</div>
@endsection
