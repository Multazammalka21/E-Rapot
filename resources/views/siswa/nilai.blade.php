@extends('layouts.dashboard')
@section('title', 'Nilai Saya')
@section('page-title', '📝 Nilai Saya')
@section('page-subtitle', 'Capaian pembelajaran semester {{ $ta->label }}')
@section('sidebar-nav') @include('siswa.partials.sidebar') @endsection

@section('content')
<div class="stat-grid stat-grid-4" style="margin-bottom:1rem">
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#4f46e5,#818cf8)">
        <div class="stat-label">Rata-rata</div>
        <div class="stat-value" style="font-size:1.75rem">{{ $statsNilai['rata_rata'] }}</div>
        <div class="stat-icon">📊</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#10b981,#34d399)">
        <div class="stat-label">Tertinggi</div>
        <div class="stat-value" style="font-size:1.75rem;color:#6ee7b7">{{ $statsNilai['tertinggi'] ?? '—' }}</div>
        <div class="stat-icon">🏆</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#f59e0b,#fbbf24)">
        <div class="stat-label">Tuntas KKTP</div>
        <div class="stat-value" style="font-size:1.75rem;color:#fcd34d">{{ $statsNilai['lulus'] }}</div>
        <div class="stat-sub">dari {{ $nilai->count() }} mapel</div>
        <div class="stat-icon">✅</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#ef4444,#f87171)">
        <div class="stat-label">Belum Tuntas</div>
        <div class="stat-value" style="font-size:1.75rem;color:#fca5a5">{{ $statsNilai['tidak_lulus'] }}</div>
        <div class="stat-icon">⚠️</div>
    </div>
</div>

<div class="panel">
    <div class="panel-header"><span class="panel-title">📋 Detail Nilai Per Mata Pelajaran</span></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Mata Pelajaran</th><th>SH</th><th>STS</th><th>SAS</th><th>Nilai Akhir</th><th>KKTP</th><th>Predikat</th><th>Keterangan</th></tr>
            </thead>
            <tbody>
                @foreach($nilai as $i => $n)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="color:var(--text);font-weight:600">{{ $n->mataPelajaran?->nama_mapel }}</td>
                    <td style="text-align:center">{{ $n->nilai_sh ?? '—' }}</td>
                    <td style="text-align:center">{{ $n->nilai_sts ?? '—' }}</td>
                    <td style="text-align:center">{{ $n->nilai_sas ?? '—' }}</td>
                    <td style="text-align:center;font-weight:800;color:{{ $n->is_lulus ? '#6ee7b7' : '#fca5a5' }}">{{ $n->nilai_akhir ? number_format($n->nilai_akhir,1) : '—' }}</td>
                    <td style="text-align:center;color:var(--text-muted)">{{ $n->mataPelajaran?->kktp }}</td>
                    <td style="text-align:center"><span class="badge badge-{{ $n->predikat ?? 'C' }}">{{ $n->predikat ?? '—' }}</span></td>
                    <td style="font-size:0.75rem;color:var(--text-muted)">{{ $n->is_lulus ? '✅ Tuntas' : '⚠️ Belum tuntas' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
