@extends('layouts.dashboard')
@section('title', 'Input Nilai')
@section('page-title', '📝 Input Nilai')
@section('page-subtitle', 'Pilih kelas & mata pelajaran untuk input nilai')

@section('sidebar-nav')
<div class="nav-section-title">Utama</div>
<a href="{{ route('guru.dashboard') }}" class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
    <span class="nav-icon">📊</span> Dashboard
</a>
<div class="nav-section-title">Nilai</div>
<a href="{{ route('guru.nilai.index') }}" class="nav-item active">
    <span class="nav-icon">📝</span> Input Nilai
</a>
<div class="nav-section-title">Rapot</div>
<a href="#" class="nav-item"><span class="nav-icon">📄</span> Cetak Rapot PDF</a>
@endsection

@section('content')
@include('components.alerts')

{{-- Finalisasi kelas wali ─────────────────────────────────────────── --}}
@if($kelasWali)
<div style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.3);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap">
    <div>
        <div style="font-size:0.875rem;font-weight:700;color:#fcd34d">🔑 Wali Kelas {{ $kelasWali->nama_kelas }}</div>
        <div style="font-size:0.78rem;color:var(--text-muted)">Anda adalah wali kelas. Setelah semua nilai diinput, Anda dapat memfinalisasi nilai kelas ini.</div>
    </div>
    @if($isWaliKelasFinal)
        <form method="POST" action="{{ route('guru.nilai.unfinalize', $kelasWali->id) }}"
              onsubmit="return confirm('Batal Finalisasi semua nilai Kelas {{ $kelasWali->nama_kelas }}? Nilai akan kembali menjadi Draft dan dapat diedit oleh guru mata pelajaran.')">
            @csrf
            <button type="submit" style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,#ef4444,#b91c1c);border:none;border-radius:8px;color:white;font-weight:700;cursor:pointer;font-size:0.85rem;font-family:'Inter',sans-serif">
                🔓 Batal Finalisasi Kelas {{ $kelasWali->nama_kelas }}
            </button>
        </form>
    @else
        <form method="POST" action="{{ route('guru.nilai.finalize', $kelasWali->id) }}"
              onsubmit="return confirm('Finalisasi semua nilai Kelas {{ $kelasWali->nama_kelas }}? Nilai tidak dapat diedit setelah difinalisasi.')">
            @csrf
            <button type="submit" style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,#f59e0b,#d97706);border:none;border-radius:8px;color:white;font-weight:700;cursor:pointer;font-size:0.85rem;font-family:'Inter',sans-serif">
                🔒 Finalisasi Semua Nilai Kelas {{ $kelasWali->nama_kelas }}
            </button>
        </form>
    @endif
</div>
@endif

{{-- Pantauan Wali Kelas ─────────────────────────────────────────── --}}
@if($kelasWali && $waliAssignments->isNotEmpty())
<div style="margin-bottom:2rem;background:rgba(16,185,129,0.05);border:1px dashed rgba(16,185,129,0.3);border-radius:12px;padding:1.5rem">
    <div style="font-size:0.8rem;font-weight:700;color:#059669;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem">
        <span style="background:rgba(16,185,129,0.2);padding:2px 10px;border-radius:20px">Pantauan Wali Kelas {{ $kelasWali->nama_kelas }}</span>
        <span style="color:var(--text-muted)">— Progres Input Nilai Semua Mata Pelajaran</span>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:0.75rem">
        @foreach($waliAssignments as $gm)
        @php $prog = $waliProgress[$gm->id]; @endphp
        <div style="background:var(--surface);border:1px solid {{ $prog['is_final'] ? 'rgba(16,185,129,0.3)' : 'var(--border)' }};border-radius:12px;padding:1rem;transition:all 0.2s">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem">
                <code style="background:rgba(16,185,129,0.15);color:#059669;padding:2px 8px;border-radius:5px;font-size:0.75rem;font-weight:700">
                    {{ $gm->mataPelajaran?->kode_mapel }}
                </code>
                @if($prog['is_final'])
                    <span class="badge badge-success">🔒 Final</span>
                @elseif($prog['sudah'] > 0)
                    <span class="badge badge-warning">⏳ Draft</span>
                @else
                    <span class="badge" style="background:rgba(100,116,139,0.15);color:var(--text-muted)">Belum</span>
                @endif
            </div>
            <div style="font-size:0.82rem;font-weight:600;color:var(--text);margin-bottom:0.25rem;line-height:1.3">
                {{ $gm->mataPelajaran?->nama_mapel }}
            </div>
            <div style="font-size:0.7rem;color:var(--text-muted);margin-bottom:0.75rem">
                Guru: {{ $gm->guru?->nama_lengkap ?? '-' }}
            </div>
            <div style="margin-bottom:0.5rem">
                <div style="display:flex;justify-content:space-between;font-size:0.72rem;color:var(--text-muted);margin-bottom:0.25rem">
                    <span>Progress</span>
                    <span>{{ $prog['sudah'] }}/{{ $prog['total'] }} ({{ $prog['pct'] }}%)</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:{{ $prog['pct'] }}%;background:{{ $prog['is_final'] ? '#10b981' : ($prog['pct'] == 100 ? '#f59e0b' : 'var(--primary)') }}"></div>
                </div>
            </div>
            @if($gm->guru_id === $guru->id)
                <div style="font-size:0.7rem;color:var(--primary);text-align:center;margin-top:0.5rem;font-weight:600">(Mata Pelajaran Anda)</div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Grid assignment cards (Mata Pelajaran yang diajar) ─────────────────────────────────────────── --}}
@php $grouped = $assignments->groupBy(fn($a) => $a->kelas->nama_kelas); @endphp

@foreach($grouped as $namaKelas => $kelasAssignments)
<div style="margin-bottom:1.5rem">
    <div style="font-size:0.8rem;font-weight:700;color:var(--primary-light);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem">
        <span style="background:rgba(79,70,229,0.2);padding:2px 10px;border-radius:20px">Kelas {{ $namaKelas }}</span>
        <span style="color:var(--text-muted)">— {{ $kelasAssignments->first()->kelas->tahunAjaran?->label }}</span>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:0.75rem">
        @foreach($kelasAssignments as $gm)
        @php $prog = $progress[$gm->id]; @endphp
        <div style="background:var(--surface);border:1px solid {{ $prog['is_final'] ? 'rgba(16,185,129,0.3)' : 'var(--border)' }};border-radius:12px;padding:1rem;transition:all 0.2s">
            {{-- Mapel header --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem">
                <code style="background:rgba(79,70,229,0.15);color:var(--primary-light);padding:2px 8px;border-radius:5px;font-size:0.75rem;font-weight:700">
                    {{ $gm->mataPelajaran?->kode_mapel }}
                </code>
                @if($prog['is_final'])
                    <span class="badge badge-success">🔒 Final</span>
                @elseif($prog['sudah'] > 0)
                    <span class="badge badge-warning">⏳ Draft</span>
                @else
                    <span class="badge" style="background:rgba(100,116,139,0.15);color:var(--text-muted)">Belum</span>
                @endif
            </div>

            {{-- Nama mapel --}}
            <div style="font-size:0.82rem;font-weight:600;color:var(--text);margin-bottom:0.75rem;line-height:1.3">
                {{ $gm->mataPelajaran?->nama_mapel }}
            </div>

            {{-- Progress bar --}}
            <div style="margin-bottom:0.5rem">
                <div style="display:flex;justify-content:space-between;font-size:0.72rem;color:var(--text-muted);margin-bottom:0.25rem">
                    <span>Progress input</span>
                    <span>{{ $prog['sudah'] }}/{{ $prog['total'] }} ({{ $prog['pct'] }}%)</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width:{{ $prog['pct'] }}%;background:{{ $prog['is_final'] ? '#10b981' : ($prog['pct'] == 100 ? '#f59e0b' : 'var(--primary)') }}"></div>
                </div>
            </div>

            {{-- Action button --}}
            @if(!$prog['is_final'])
            <a href="{{ route('guru.nilai.input', [$gm->kelas_id, $gm->mata_pelajaran_id]) }}"
                style="display:block;text-align:center;padding:0.5rem;background:rgba(79,70,229,0.15);border:1px solid rgba(79,70,229,0.3);border-radius:7px;color:var(--primary-light);text-decoration:none;font-size:0.8rem;font-weight:600;margin-top:0.75rem;transition:all 0.2s">
                {{ $prog['sudah'] > 0 ? '✏️ Edit Nilai' : '➕ Input Nilai' }}
            </a>
            @else
            <a href="{{ route('guru.nilai.input', [$gm->kelas_id, $gm->mata_pelajaran_id]) }}"
                style="display:block;text-align:center;padding:0.5rem;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);border-radius:7px;color:#6ee7b7;text-decoration:none;font-size:0.8rem;margin-top:0.75rem">
                👁️ Lihat Nilai
            </a>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endforeach

@if($assignments->isEmpty())
<div class="panel">
    <div class="panel-body" style="text-align:center;padding:3rem;color:var(--text-muted)">
        <div style="font-size:3rem;margin-bottom:1rem">📚</div>
        <div>Anda belum memiliki assignment mata pelajaran di semester aktif.</div>
    </div>
</div>
@endif
@endsection
