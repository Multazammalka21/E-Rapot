@extends('layouts.dashboard')

@section('title', 'Dashboard Siswa')
@section('page-title', '🎓 Dashboard Siswa')
@section('page-subtitle', 'Laporan akademik semester aktif')

@section('sidebar-nav')
@include('siswa.partials.sidebar')
@endsection

@section('content')

{{-- ── Profil Siswa ── --}}
<div class="panel" style="margin-bottom:1rem">
    <div class="panel-body" style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap">
        <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:white;flex-shrink:0">
            {{ strtoupper(substr($siswa?->nama_lengkap ?? 'S',0,1)) }}
        </div>
        <div style="flex:1;min-width:200px">
            <div style="font-size:1.1rem;font-weight:700;color:var(--text)">{{ $siswa?->nama_lengkap ?? auth()->user()->name }}</div>
            <div style="font-size:0.8rem;color:var(--text-muted);margin-top:0.3rem">
                NIS: <strong style="color:var(--primary-light)">{{ $siswa?->nis ?? '-' }}</strong>
                &nbsp;·&nbsp; NISN: {{ $siswa?->nisn ?? '-' }}
                &nbsp;·&nbsp; {{ $siswa?->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
            </div>
        </div>
        @if($siswaKelas)
        <div style="display:flex;gap:0.75rem">
            <div style="background:rgba(79,70,229,0.15);border:1px solid rgba(79,70,229,0.3);border-radius:10px;padding:0.75rem 1.25rem;text-align:center">
                <div style="font-size:1.4rem;font-weight:800;color:var(--primary-light)">{{ $siswaKelas->kelas?->nama_kelas }}</div>
                <div style="font-size:0.7rem;color:var(--text-muted)">Kelas</div>
            </div>
            <div style="background:rgba(6,182,212,0.1);border:1px solid rgba(6,182,212,0.3);border-radius:10px;padding:0.75rem 1.25rem;text-align:center">
                <div style="font-size:0.85rem;font-weight:700;color:#67e8f9">{{ $siswaKelas->kelas?->waliKelas?->nama_lengkap ?? '-' }}</div>
                <div style="font-size:0.7rem;color:var(--text-muted)">Wali Kelas</div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="stat-grid stat-grid-4" style="margin-bottom:1rem">
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#4f46e5,#818cf8)">
        <div class="stat-label">Rata-rata Nilai</div>
        <div class="stat-value" style="font-size:1.75rem">{{ $statsNilai['rata_rata'] }}</div>
        <div class="stat-sub">Semua mata pelajaran</div>
        <div class="stat-icon">📊</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#10b981,#34d399)">
        <div class="stat-label">Nilai Tertinggi</div>
        <div class="stat-value" style="font-size:1.75rem;color:#6ee7b7">{{ $statsNilai['tertinggi'] ?? '-' }}</div>
        <div class="stat-sub">Pencapaian terbaik</div>
        <div class="stat-icon">🏆</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#f59e0b,#fbbf24)">
        <div class="stat-label">Mapel Tuntas</div>
        <div class="stat-value" style="font-size:1.75rem;color:#fcd34d">{{ $statsNilai['lulus'] }}</div>
        <div class="stat-sub">Mencapai KKTP</div>
        <div class="stat-icon">✅</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#06b6d4,#22d3ee)">
        <div class="stat-label">Kehadiran</div>
        <div class="stat-value" style="font-size:1.75rem;color:#67e8f9">
            {{ $absensi ? $absensi->persentase_hadir . '%' : '-' }}
        </div>
        <div class="stat-sub">
            @if($absensi) S:{{ $absensi->sakit }} I:{{ $absensi->izin }} A:{{ $absensi->alpha }} @endif
        </div>
        <div class="stat-icon">📅</div>
    </div>
</div>

{{-- ── Nilai + Ekskul ── --}}
<div class="grid-2" style="margin-bottom:1rem">
    {{-- Daftar Nilai --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">📝 Nilai Per Mata Pelajaran</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Mata Pelajaran</th><th>SH</th><th>STS</th><th>SAS</th><th>Akhir</th><th>P</th></tr></thead>
                <tbody>
                    @forelse($nilaiSemester->sortBy('mataPelajaran.nama_mapel') as $nilai)
                    <tr>
                        <td style="color:var(--text);font-weight:500">{{ $nilai->mataPelajaran?->kode_mapel }}</td>
                        <td>{{ $nilai->nilai_sh ?? '-' }}</td>
                        <td>{{ $nilai->nilai_sts ?? '-' }}</td>
                        <td>{{ $nilai->nilai_sas ?? '-' }}</td>
                        <td style="font-weight:700;color:var(--text)">{{ $nilai->nilai_akhir ?? '-' }}</td>
                        <td><span class="badge badge-{{ $nilai->predikat ?? 'C' }}">{{ $nilai->predikat ?? '-' }}</span></td>
                    </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;color:var(--text-muted)">Belum ada nilai</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Ekskul + Catatan ── --}}
    <div style="display:flex;flex-direction:column;gap:1rem">
        <div class="panel">
            <div class="panel-header"><span class="panel-title">🏅 Ekstrakurikuler</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Nama</th><th>Predikat</th></tr></thead>
                    <tbody>
                        @forelse($ekskul as $e)
                        <tr>
                            <td style="color:var(--text)">{{ $e->ekstrakurikuler?->nama }}</td>
                            <td><span class="badge badge-{{ $e->predikat ?? 'C' }}">{{ $e->predikat ?? '-' }}</span></td>
                        </tr>
                        @empty
                            <tr><td colspan="2" style="text-align:center;color:var(--text-muted)">-</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($catatan)
        <div class="panel">
            <div class="panel-header"><span class="panel-title">💬 Catatan Wali Kelas</span></div>
            <div class="panel-body">
                <p style="font-size:0.875rem;color:var(--text-soft);line-height:1.7;font-style:italic">"{{ $catatan->catatan }}"</p>
                <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.5rem">— {{ $catatan->waliKelas?->nama_lengkap }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
