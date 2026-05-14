@extends('layouts.dashboard')
@section('title', 'Wali Kelas')
@section('page-title', '👥 Wali Kelas')
@section('page-subtitle', 'Manajemen absensi dan catatan wali kelas untuk siswa')

@section('sidebar-nav')
    @include('guru.partials.sidebar')
@endsection

@section('content')
@include('components.alerts')

<div style="margin-bottom:1.5rem">
    <div style="font-size:0.8rem;font-weight:700;color:var(--primary-light);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem">
        <span style="background:rgba(79,70,229,0.2);padding:2px 10px;border-radius:20px">Tahun Ajaran Aktif</span>
        <span style="color:var(--text-muted)">— {{ $ta->label }}</span>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem">
        @foreach($kelasList as $kelas)
        <div style="background:var(--surface);border:1px solid var(--border);border-radius:12px;padding:1.25rem;transition:all 0.2s">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
                <div style="font-size:1.1rem;font-weight:700;color:var(--text)">Kelas {{ $kelas->nama_kelas }}</div>
                <span style="background:rgba(16,185,129,0.15);color:#6ee7b7;padding:4px 10px;border-radius:6px;font-size:0.75rem;font-weight:700">
                    Wali Kelas
                </span>
            </div>

            <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:1rem;display:flex;gap:1rem">
                <div><i data-lucide="users" style="width:14px;height:14px;display:inline-block;vertical-align:-2px"></i> {{ $kelas->siswa_count }} Siswa</div>
                <div><i data-lucide="bar-chart" style="width:14px;height:14px;display:inline-block;vertical-align:-2px"></i> Tingkat {{ $kelas->tingkat }}</div>
            </div>

            <a href="{{ route('guru.walikelas.input', $kelas->id) }}"
                style="display:block;text-align:center;padding:0.6rem;background:rgba(79,70,229,0.15);border:1px solid rgba(79,70,229,0.3);border-radius:8px;color:var(--primary-light);text-decoration:none;font-size:0.85rem;font-weight:600;transition:all 0.2s">
                📝 Input Absensi & Catatan
            </a>
        </div>
        @endforeach
    </div>

    @if($kelasList->isEmpty())
    <div class="panel" style="margin-top:1rem">
        <div class="panel-body" style="text-align:center;padding:3rem;color:var(--text-muted)">
            <div style="font-size:3rem;margin-bottom:1rem">👥</div>
            <div>Anda tidak ditugaskan sebagai wali kelas pada tahun ajaran ini.</div>
        </div>
    </div>
    @endif
</div>
@endsection
