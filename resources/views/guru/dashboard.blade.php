@extends('layouts.dashboard')

@section('title', 'Dashboard Guru')
@section('page-title', '👨‍🏫 Dashboard Guru')
@section('page-subtitle', 'Selamat datang, {{ $guru?->nama_gelar ?? auth()->user()->name }}')

@section('sidebar-nav')
@include('guru.partials.sidebar')
@endsection

@section('content')

{{-- ── Profil Guru ── --}}
<div class="panel" style="margin-bottom:1rem">
    <div class="panel-body" style="display:flex;align-items:center;gap:1.5rem">
        <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:white;flex-shrink:0">
            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
        </div>
        <div style="flex:1">
            <div style="font-size:1.1rem;font-weight:700;color:var(--text)">{{ $guru?->nama_gelar ?? auth()->user()->name }}</div>
            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:0.25rem">
                NIP: {{ $guru?->nip ?? '-' }} &nbsp;·&nbsp; {{ $guru?->bidang_studi ?? '-' }}
            </div>
        </div>
        @if($kelasWali)
        <div style="background:rgba(79,70,229,0.15);border:1px solid rgba(79,70,229,0.3);border-radius:10px;padding:0.75rem 1.25rem;text-align:center">
            <div style="font-size:1.5rem;font-weight:800;color:var(--primary-light)">{{ $kelasWali->nama_kelas }}</div>
            <div style="font-size:0.7rem;color:var(--text-muted)">Wali Kelas</div>
        </div>
        @endif
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="stat-grid stat-grid-3" style="margin-bottom:1rem">
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#4f46e5,#818cf8)">
        <div class="stat-label">Nilai Terinput</div>
        <div class="stat-value">{{ $statsNilai['total'] }}</div>
        <div class="stat-sub">Semester aktif</div>
        <div class="stat-icon">📝</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#10b981,#34d399)">
        <div class="stat-label">Sudah Final</div>
        <div class="stat-value">{{ $statsNilai['final'] }}</div>
        <div class="stat-sub" style="color:#6ee7b7">Terkunci oleh wali kelas</div>
        <div class="stat-icon">✅</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#f59e0b,#fbbf24)">
        <div class="stat-label">Masih Draft</div>
        <div class="stat-value">{{ $statsNilai['draft'] }}</div>
        <div class="stat-sub" style="color:#fcd34d">Belum difinalisasi</div>
        <div class="stat-icon">⏳</div>
    </div>
</div>

{{-- ── Kelas yang Diajar + Distribusi Predikat ── --}}
<div class="grid-2">
    {{-- Kelas yang diajar --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">🏫 Kelas yang Diajar</span>
            <span style="font-size:0.75rem;color:var(--text-muted)">{{ $kelasDiajar->count() }} kelas</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kelas</th><th>Mata Pelajaran</th></tr></thead>
                <tbody>
                    @forelse($kelasDiajar as $kelasId => $assignments)
                        @foreach($assignments as $gm)
                        <tr>
                            <td style="font-weight:600;color:var(--text)">{{ $gm->kelas?->nama_kelas }}</td>
                            <td>{{ $gm->mataPelajaran?->nama_mapel }}</td>
                        </tr>
                        @endforeach
                    @empty
                        <tr><td colspan="2" style="text-align:center;color:var(--text-muted)">Belum ada assignment mapel</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Distribusi Predikat --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">📊 Distribusi Predikat</span>
        </div>
        <div class="panel-body">
            @php
                $warna = ['A'=>'#10b981','B'=>'#3b82f6','C'=>'#f59e0b','D'=>'#ef4444'];
                $totalNilai = $distribusiPredikat->sum();
            @endphp
            @if($totalNilai > 0)
                @foreach(['A','B','C','D'] as $p)
                    @php $jml = $distribusiPredikat[$p] ?? 0; $pct = $totalNilai ? round($jml/$totalNilai*100) : 0; @endphp
                    <div style="margin-bottom:0.85rem">
                        <div style="display:flex;justify-content:space-between;margin-bottom:0.3rem">
                            <span class="badge badge-{{ $p }}">{{ $p }} — {{ ['A'=>'Sangat Baik','B'=>'Baik','C'=>'Cukup','D'=>'Perlu Bimbingan'][$p] }}</span>
                            <span style="font-size:0.8rem;color:var(--text-muted)">{{ $jml }} ({{ $pct }}%)</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $warna[$p] }}"></div>
                        </div>
                    </div>
                @endforeach
            @else
                <p style="color:var(--text-muted);font-size:0.85rem;text-align:center">Belum ada data nilai.</p>
            @endif
        </div>
    </div>
</div>

@endsection
