@extends('layouts.dashboard')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')
@section('page-subtitle', 'Selamat datang, ' . ($guru?->nama_gelar ?? auth()->user()->name))

@section('sidebar-nav')
@include('guru.partials.sidebar')
@endsection

@section('content')

{{-- ── Profil Guru ── --}}
<div class="panel" style="margin-bottom:1.5rem">
    <div class="panel-body" style="display:flex;align-items:center;gap:1.5rem">
        <div style="width:64px;height:64px;border-radius:20px;background:linear-gradient(135deg,var(--primary),var(--info));display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:white;flex-shrink:0;box-shadow:var(--shadow)">
            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
        </div>
        <div style="flex:1">
            <div style="font-size:1.25rem;font-weight:800;color:var(--accent);letter-spacing:-0.02em">{{ $guru?->nama_gelar ?? auth()->user()->name }}</div>
            <div style="font-size:0.875rem;color:var(--text-muted);margin-top:0.25rem;font-weight:600">
                <i data-lucide="id-card" style="width:14px;height:14px;vertical-align:middle;margin-right:4px"></i> NIP: {{ $guru?->nip ?? '-' }} &nbsp;·&nbsp; <i data-lucide="book" style="width:14px;height:14px;vertical-align:middle;margin-right:4px"></i> {{ $guru?->bidang_studi ?? '-' }}
            </div>
        </div>
        @if($kelasWali)
        <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:16px;padding:0.75rem 1.5rem;text-align:center;box-shadow:var(--shadow)">
            <div style="font-size:1.5rem;font-weight:800;color:var(--primary)">{{ $kelasWali->nama_kelas }}</div>
            <div style="font-size:0.7rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em">Wali Kelas</div>
        </div>
        @endif
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="stat-grid stat-grid-3" style="margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-label">Nilai Terinput</div>
        <div class="stat-value">{{ $statsNilai['total'] }}</div>
        <div class="stat-sub">Semester aktif</div>
        <div class="stat-icon-bg"><i data-lucide="file-text"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Sudah Final</div>
        <div class="stat-value">{{ $statsNilai['final'] }}</div>
        <div class="stat-sub trend-up">Terkunci oleh wali kelas</div>
        <div class="stat-icon-bg"><i data-lucide="check-circle-2"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Masih Draft</div>
        <div class="stat-value">{{ $statsNilai['draft'] }}</div>
        <div class="stat-sub" style="color:var(--warning)">Belum difinalisasi</div>
        <div class="stat-icon-bg"><i data-lucide="clock"></i></div>
    </div>
</div>

{{-- ── Kelas yang Diajar + Distribusi Predikat ── --}}
<div class="grid-2">
    {{-- Kelas yang diajar --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i data-lucide="school" style="width:18px"></i> Kelas yang Diajar</span>
            <span style="font-size:0.75rem;color:var(--text-muted);font-weight:700">{{ $kelasDiajar->count() }} KELAS</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kelas</th><th>Mata Pelajaran</th></tr></thead>
                <tbody>
                    @forelse($kelasDiajar as $kelasId => $assignments)
                        @foreach($assignments as $gm)
                        <tr>
                            <td style="font-weight:700;color:var(--accent)">{{ $gm->kelas?->nama_kelas }}</td>
                            <td style="font-weight:500">{{ $gm->mataPelajaran?->nama_mapel }}</td>
                        </tr>
                        @endforeach
                    @empty
                        <tr><td colspan="2" style="text-align:center;color:var(--text-muted);padding:2rem">Belum ada assignment mapel</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Distribusi Predikat --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i data-lucide="bar-chart-3" style="width:18px"></i> Distribusi Predikat</span>
        </div>
        <div class="panel-body">
            @php
                $warna = ['A'=>'#10b981','B'=>'#3b82f6','C'=>'#f59e0b','D'=>'#ef4444'];
                $totalNilai = $distribusiPredikat->sum();
            @endphp
            @if($totalNilai > 0)
                @foreach(['A','B','C','D'] as $p)
                    @php $jml = $distribusiPredikat[$p] ?? 0; $pct = $totalNilai ? round($jml/$totalNilai*100) : 0; @endphp
                    <div style="margin-bottom:1.25rem">
                        <div style="display:flex;justify-content:space-between;margin-bottom:0.5rem;align-items:center">
                            <div style="display:flex;align-items:center;gap:0.75rem">
                                <span class="badge badge-{{ $p }}">{{ $p }}</span>
                                <span style="font-size:0.875rem;font-weight:600;color:var(--text-soft)">{{ ['A'=>'Sangat Baik','B'=>'Baik','C'=>'Cukup','D'=>'Perlu Bimbingan'][$p] }}</span>
                            </div>
                            <span style="font-size:0.85rem;font-weight:700;color:var(--text-muted)">{{ $jml }} ({{ $pct }}%)</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $warna[$p] }}"></div>
                        </div>
                    </div>
                @endforeach
            @else
                <div style="text-align:center;padding:2rem">
                    <i data-lucide="info" style="width:32px;height:32px;color:var(--text-muted);margin-bottom:0.75rem"></i>
                    <p style="color:var(--text-muted);font-size:0.875rem;font-weight:500">Belum ada data nilai.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
