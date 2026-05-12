@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')
@section('page-title', '🛡️ Dashboard Admin')
@section('page-subtitle', 'Ringkasan sistem E-Rapot SMPN 1 Surabaya')

@section('sidebar-nav')
@include('admin.partials.sidebar')
@endsection

@section('content')

{{-- ── Stat Cards ── --}}
<div class="stat-grid stat-grid-4">
    <div class="stat-card" style="--card-accent: linear-gradient(90deg,#4f46e5,#818cf8)">
        <div class="stat-label">Total Siswa</div>
        <div class="stat-value">{{ number_format($stats['total_siswa']) }}</div>
        <div class="stat-sub">Terdaftar aktif</div>
        <div class="stat-icon">🎓</div>
    </div>
    <div class="stat-card" style="--card-accent: linear-gradient(90deg,#06b6d4,#22d3ee)">
        <div class="stat-label">Total Guru</div>
        <div class="stat-value">{{ number_format($stats['total_guru']) }}</div>
        <div class="stat-sub">Pengajar aktif</div>
        <div class="stat-icon">👨‍🏫</div>
    </div>
    <div class="stat-card" style="--card-accent: linear-gradient(90deg,#10b981,#34d399)">
        <div class="stat-label">Total Kelas</div>
        <div class="stat-value">{{ number_format($stats['total_kelas']) }}</div>
        <div class="stat-sub">Semester aktif</div>
        <div class="stat-icon">🏫</div>
    </div>
    <div class="stat-card" style="--card-accent: linear-gradient(90deg,#f59e0b,#fbbf24)">
        <div class="stat-label">Total Nilai</div>
        <div class="stat-value">{{ number_format($stats['total_nilai']) }}</div>
        <div class="stat-sub">
            <span style="color:#6ee7b7">✓ {{ number_format($stats['nilai_final']) }} final</span>
            · {{ number_format($stats['nilai_draft']) }} draft
        </div>
        <div class="stat-icon">📝</div>
    </div>
</div>

{{-- ── Row 2: Distribusi Predikat + Top Siswa ── --}}
<div class="grid-2" style="margin-bottom:1rem">

    {{-- Distribusi Predikat --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">📊 Distribusi Predikat Nilai</span>
        </div>
        <div class="panel-body">
            @php
                $warna = ['A'=>'#10b981','B'=>'#3b82f6','C'=>'#f59e0b','D'=>'#ef4444'];
                $totalNilai = $distribusiPredikat->sum();
            @endphp
            @foreach(['A','B','C','D'] as $p)
                @php $jml = $distribusiPredikat[$p] ?? 0; $pct = $totalNilai ? round($jml/$totalNilai*100) : 0; @endphp
                <div style="margin-bottom:1rem">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.35rem">
                        <div style="display:flex;align-items:center;gap:0.5rem">
                            <span class="badge badge-{{ $p }}">{{ $p }}</span>
                            <span style="font-size:0.8rem;color:var(--text-soft)">
                                {{ ['A'=>'Sangat Baik','B'=>'Baik','C'=>'Cukup','D'=>'Perlu Bimbingan'][$p] }}
                            </span>
                        </div>
                        <span style="font-size:0.8rem;color:var(--text-muted)">{{ number_format($jml) }} ({{ $pct }}%)</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width:{{ $pct }}%;background:{{ $warna[$p] }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Top 5 Siswa --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">🏆 Top 5 Siswa</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topSiswa as $i => $row)
                    <tr>
                        <td>
                            <span style="font-weight:700;color:{{ ['#fbbf24','#94a3b8','#cd7c54'][$i] ?? 'var(--text-muted)' }}">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td style="font-weight:500;color:var(--text)">{{ $row->siswa?->nama_lengkap ?? '-' }}</td>
                        <td>
                            <span class="badge badge-{{ $row->rata_rata >= 90 ? 'A' : ($row->rata_rata >= 80 ? 'B' : ($row->rata_rata >= 70 ? 'C' : 'D')) }}">
                                {{ number_format($row->rata_rata, 1) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── Row 3: Top Kelas + Recent Users ── --}}
<div class="grid-2">
    {{-- Top Kelas --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">🏫 Rata-rata Nilai Per Kelas</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Kelas</th><th>Rata-rata</th><th>Bar</th></tr></thead>
                <tbody>
                    @foreach($topKelas as $row)
                    <tr>
                        <td style="font-weight:600;color:var(--text)">{{ $row->kelas?->nama_kelas ?? '-' }}</td>
                        <td><span class="badge badge-B">{{ number_format($row->rata_rata, 1) }}</span></td>
                        <td style="width:100px">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width:{{ min(100,round($row->rata_rata)) }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">👥 Akun Terbaru</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama</th><th>Role</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($recentUsers as $u)
                    <tr>
                        <td style="color:var(--text);font-weight:500">{{ $u->name }}</td>
                        <td>
                            <span class="badge {{ $u->role==='admin' ? 'badge-info' : ($u->role==='guru' ? 'badge-success' : 'badge-warning') }}">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $u->is_active ? 'badge-success' : 'badge-D' }}">
                                {{ $u->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
