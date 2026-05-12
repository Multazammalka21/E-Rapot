@extends('layouts.dashboard')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Siswa')
@section('page-subtitle', 'Laporan akademik semester aktif')

@section('sidebar-nav')
@include('siswa.partials.sidebar')
@endsection

@section('content')

{{-- ── Profil Siswa ── --}}
<div class="panel" style="margin-bottom:1.5rem">
    <div class="panel-body" style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap">
        <div style="width:64px;height:64px;border-radius:20px;background:linear-gradient(135deg,var(--primary),var(--info));display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:white;flex-shrink:0;box-shadow:var(--shadow)">
            {{ strtoupper(substr($siswa?->nama_lengkap ?? 'S',0,1)) }}
        </div>
        <div style="flex:1;min-width:200px">
            <div style="font-size:1.25rem;font-weight:800;color:var(--accent);letter-spacing:-0.02em">{{ $siswa?->nama_lengkap ?? auth()->user()->name }}</div>
            <div style="font-size:0.875rem;color:var(--text-muted);margin-top:0.4rem;font-weight:600;display:flex;gap:1rem;flex-wrap:wrap">
                <span><i data-lucide="fingerprint" style="width:14px;height:14px;vertical-align:middle;margin-right:4px"></i> NIS: <strong style="color:var(--primary)">{{ $siswa?->nis ?? '-' }}</strong></span>
                <span><i data-lucide="hash" style="width:14px;height:14px;vertical-align:middle;margin-right:4px"></i> NISN: {{ $siswa?->nisn ?? '-' }}</span>
                <span><i data-lucide="user" style="width:14px;height:14px;vertical-align:middle;margin-right:4px"></i> {{ $siswa?->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
            </div>
        </div>
        @if($siswaKelas)
        <div style="display:flex;gap:1rem">
            <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:16px;padding:0.75rem 1.25rem;text-align:center;box-shadow:var(--shadow)">
                <div style="font-size:1.4rem;font-weight:800;color:var(--primary)">{{ $siswaKelas->kelas?->nama_kelas }}</div>
                <div style="font-size:0.68rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em">Kelas</div>
            </div>
            <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:16px;padding:0.75rem 1.25rem;text-align:center;box-shadow:var(--shadow)">
                <div style="font-size:0.95rem;font-weight:700;color:var(--accent)">{{ $siswaKelas->kelas?->waliKelas?->nama_lengkap ?? '-' }}</div>
                <div style="font-size:0.68rem;color:var(--text-muted);font-weight:700;text-transform:uppercase;letter-spacing:0.05em">Wali Kelas</div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ── Stat Cards ── --}}
<div class="stat-grid stat-grid-4" style="margin-bottom:1.5rem">
    <div class="stat-card">
        <div class="stat-label">Rata-rata Nilai</div>
        <div class="stat-value">{{ $statsNilai['rata_rata'] }}</div>
        <div class="stat-sub">Semua mata pelajaran</div>
        <div class="stat-icon-bg"><i data-lucide="bar-chart-3"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Nilai Tertinggi</div>
        <div class="stat-value" style="color:var(--success)">{{ $statsNilai['tertinggi'] ?? '-' }}</div>
        <div class="stat-sub">Pencapaian terbaik</div>
        <div class="stat-icon-bg"><i data-lucide="trophy"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Mapel Tuntas</div>
        <div class="stat-value" style="color:var(--primary)">{{ $statsNilai['lulus'] }}</div>
        <div class="stat-sub">Mencapai KKTP</div>
        <div class="stat-icon-bg"><i data-lucide="check-circle-2"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Kehadiran</div>
        <div class="stat-value" style="color:var(--info)">
            {{ $absensi ? $absensi->persentase_hadir . '%' : '-' }}
        </div>
        <div class="stat-sub">
            @if($absensi) 
                <span class="badge badge-A" style="font-size:0.65rem;padding:2px 6px">S:{{ $absensi->sakit }}</span>
                <span class="badge badge-B" style="font-size:0.65rem;padding:2px 6px">I:{{ $absensi->izin }}</span>
                <span class="badge badge-D" style="font-size:0.65rem;padding:2px 6px">A:{{ $absensi->alpha }}</span>
            @endif
        </div>
        <div class="stat-icon-bg"><i data-lucide="calendar-check"></i></div>
    </div>
</div>

{{-- ── Nilai + Ekskul ── --}}
<div class="grid-2" style="margin-bottom:1.5rem">
    {{-- Daftar Nilai --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title"><i data-lucide="file-spreadsheet" style="width:18px"></i> Nilai Per Mata Pelajaran</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Mata Pelajaran</th><th>SH</th><th>STS</th><th>SAS</th><th>Akhir</th><th>P</th></tr></thead>
                <tbody>
                    @forelse($nilaiSemester->sortBy('mataPelajaran.nama_mapel') as $nilai)
                    <tr>
                        <td style="color:var(--accent);font-weight:700">{{ $nilai->mataPelajaran?->kode_mapel }}</td>
                        <td>{{ $nilai->nilai_sh ?? '-' }}</td>
                        <td>{{ $nilai->nilai_sts ?? '-' }}</td>
                        <td>{{ $nilai->nilai_sas ?? '-' }}</td>
                        <td style="font-weight:800;color:var(--primary)">{{ $nilai->nilai_akhir ?? '-' }}</td>
                        <td><span class="badge badge-{{ $nilai->predikat ?? 'C' }}">{{ $nilai->predikat ?? '-' }}</span></td>
                    </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;color:var(--text-muted);padding:2rem">Belum ada nilai</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Ekskul + Catatan ── --}}
    <div style="display:flex;flex-direction:column;gap:1.5rem">
        <div class="panel">
            <div class="panel-header"><span class="panel-title"><i data-lucide="award" style="width:18px"></i> Ekstrakurikuler</span></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Nama</th><th>Predikat</th></tr></thead>
                    <tbody>
                        @forelse($ekskul as $e)
                        <tr>
                            <td style="color:var(--accent);font-weight:600">{{ $e->ekstrakurikuler?->nama }}</td>
                            <td><span class="badge badge-{{ $e->predikat ?? 'C' }}">{{ $e->predikat ?? '-' }}</span></td>
                        </tr>
                        @empty
                            <tr><td colspan="2" style="text-align:center;color:var(--text-muted);padding:2rem">- Belum ada data -</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($catatan)
        <div class="panel">
            <div class="panel-header"><span class="panel-title"><i data-lucide="message-square-quote" style="width:18px"></i> Catatan Wali Kelas</span></div>
            <div class="panel-body" style="background:var(--surface-2)">
                <p style="font-size:0.95rem;color:var(--text-soft);line-height:1.7;font-style:italic;font-weight:500">"{{ $catatan->catatan }}"</p>
                <p style="font-size:0.8rem;color:var(--text-muted);margin-top:0.75rem;font-weight:700;text-align:right">— {{ $catatan->waliKelas?->nama_lengkap }}</p>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
