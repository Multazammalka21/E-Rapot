@extends('layouts.dashboard')
@section('title', 'Rapot Saya')
@section('page-title', '📄 Rapot Saya')
@section('page-subtitle', "{$ta->label} — Kurikulum Merdeka Fase D")
@section('sidebar-nav') @include('siswa.partials.sidebar') @endsection

@section('content')
@include('components.alerts')

@if(!$isReady)
<div style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1rem;font-size:0.85rem;color:#fcd34d">
    ⚠️ Rapot belum difinalisasi oleh wali kelas. Nilai masih berstatus <strong>Draft</strong>.
</div>
@endif

{{-- Stat summary --}}
<div class="stat-grid stat-grid-4" style="margin-bottom:1rem">
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#4f46e5,#818cf8)">
        <div class="stat-label">Rata-rata</div>
        <div class="stat-value" style="font-size:1.75rem">{{ number_format($rataRata,1) }}</div>
        <div class="stat-icon">📊</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#10b981,#34d399)">
        <div class="stat-label">Peringkat</div>
        <div class="stat-value" style="font-size:1.75rem;color:#6ee7b7">{{ $ranking['urutan'] }}</div>
        <div class="stat-sub">dari {{ $ranking['dari'] }} siswa</div>
        <div class="stat-icon">🏆</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#f59e0b,#fbbf24)">
        <div class="stat-label">Mapel Tuntas</div>
        <div class="stat-value" style="font-size:1.75rem;color:#fcd34d">{{ $nilai->where('is_lulus',true)->count() }}</div>
        <div class="stat-sub">dari {{ $nilai->count() }} mapel</div>
        <div class="stat-icon">✅</div>
    </div>
    <div class="stat-card" style="--card-accent:linear-gradient(90deg,#06b6d4,#22d3ee)">
        <div class="stat-label">Kehadiran</div>
        <div class="stat-value" style="font-size:1.75rem;color:#67e8f9">{{ $absensi?->persentase_hadir ?? 100 }}%</div>
        <div class="stat-sub">Dari 100 hari efektif</div>
        <div class="stat-icon">📅</div>
    </div>
</div>

{{-- Download button --}}
<div class="panel" style="margin-bottom:1rem">
    <div class="panel-body" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem">
        <div>
            <div style="font-size:1rem;font-weight:700;color:var(--text)">Laporan Hasil Belajar Semester {{ ucfirst($ta->semester) }}</div>
            <div style="font-size:0.8rem;color:var(--text-muted)">{{ $ta->nama }} · Kurikulum Merdeka Fase D · Kelas {{ $siswaKelas?->kelas?->nama_kelas }}</div>
        </div>
        <div style="display:flex;gap:0.75rem">
            <a href="{{ route('siswa.rapot.preview') }}" target="_blank"
                style="padding:0.7rem 1.5rem;background:linear-gradient(135deg,var(--primary-light),var(--primary));border-radius:10px;color:white;text-decoration:none;font-weight:700;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem">
                👁️ Preview PDF
            </a>
            <a href="{{ route('siswa.rapot.download') }}"
                style="padding:0.7rem 1.5rem;background:linear-gradient(135deg,#10b981,#059669);border-radius:10px;color:white;text-decoration:none;font-weight:700;font-size:0.875rem;display:flex;align-items:center;gap:0.5rem">
                📥 Unduh PDF
            </a>
        </div>
    </div>
</div>

{{-- Nilai Table --}}
<div class="panel" style="margin-bottom:1rem">
    <div class="panel-header"><span class="panel-title">📝 Nilai Per Mata Pelajaran</span></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th class="left" style="text-align:left">Mata Pelajaran</th>
                    <th>SH</th><th>STS</th><th>SAS</th>
                    <th>Nilai Akhir</th>
                    <th>Predikat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($nilai as $i => $n)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="color:var(--text);font-weight:600">
                        <code style="background:rgba(79,70,229,0.15);color:var(--primary-light);padding:1px 6px;border-radius:4px;font-size:0.72rem;margin-right:0.4rem">{{ $n->mataPelajaran?->kode_mapel }}</code>
                        {{ $n->mataPelajaran?->nama_mapel }}
                    </td>
                    <td style="text-align:center">{{ $n->nilai_sh ?? '—' }}</td>
                    <td style="text-align:center">{{ $n->nilai_sts ?? '—' }}</td>
                    <td style="text-align:center">{{ $n->nilai_sas ?? '—' }}</td>
                    <td style="text-align:center;font-weight:800;font-size:1rem;color:{{ $n->is_lulus ? '#6ee7b7' : '#fca5a5' }}">{{ $n->nilai_akhir ? number_format($n->nilai_akhir,1) : '—' }}</td>
                    <td style="text-align:center"><span class="badge badge-{{ $n->predikat ?? 'C' }}">{{ $n->predikat ?? '—' }}</span></td>
                    <td style="text-align:center">
                        <span class="badge {{ $n->status === 'final' ? 'badge-success' : 'badge-warning' }}">{{ $n->status === 'final' ? 'Final' : 'Draft' }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Catatan --}}
@if($catatan)
<div class="panel">
    <div class="panel-header"><span class="panel-title">💬 Catatan Wali Kelas</span></div>
    <div class="panel-body">
        <p style="font-size:0.9rem;color:var(--text-soft);line-height:1.8;font-style:italic">"{{ $catatan->catatan }}"</p>
        <p style="font-size:0.78rem;color:var(--text-muted);margin-top:0.5rem">— {{ $siswaKelas?->kelas?->waliKelas?->nama_gelar }}</p>
    </div>
</div>
@endif
@endsection
