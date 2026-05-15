@extends('layouts.dashboard')
@section('title', 'Cetak Rapot PDF')
@section('page-title', '📄 Cetak Rapot PDF')
@section('page-subtitle', 'Pilih siswa untuk lihat preview atau cetak rapot')

@section('sidebar-nav')
@include('guru.partials.sidebar')
@endsection

@section('content')
@include('components.alerts')

<div class="grid-2">
    {{-- Pilih Kelas --}}
    <div class="panel">
        <div class="panel-header"><span class="panel-title">🏫 Pilih Kelas</span></div>
        <div class="panel-body">
            <div style="display:flex;flex-direction:column;gap:0.5rem">
                @foreach($kelasList as $k)
                <a href="{{ route('guru.rapot.index', ['kelas_id' => $k->id]) }}"
                    style="display:flex;align-items:center;justify-content:space-between;padding:0.75rem 1rem;
                    background:{{ $selectedKelas?->id === $k->id ? 'rgba(79,70,229,0.15)' : 'var(--surface)' }};
                    border:1px solid {{ $selectedKelas?->id === $k->id ? 'rgba(79,70,229,0.4)' : 'var(--border)' }};
                    border-radius:10px;text-decoration:none;transition:all 0.2s">
                    <div>
                        <div style="font-weight:700;font-size:0.9rem;color:{{ $selectedKelas?->id === $k->id ? 'var(--primary-light)' : 'var(--text)' }}">
                            Kelas {{ $k->nama_kelas }}
                        </div>
                        <div style="font-size:0.72rem;color:var(--text-muted)">{{ $k->tahunAjaran?->label }}</div>
                    </div>
                    <span class="badge badge-info">{{ $k->siswa->count() }} siswa</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Daftar Siswa --}}
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">
                🎓 Siswa Kelas {{ $selectedKelas?->nama_kelas ?? '-' }}
            </span>
        </div>
        @if($selectedKelas)
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>#</th><th>Nama Siswa</th><th>NIS</th><th style="text-align:center">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($selectedKelas->siswa->sortBy('nama_lengkap') as $i => $s)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td style="color:var(--text);font-weight:600">{{ $s->nama_lengkap }}</td>
                        <td><code style="font-size:0.75rem;color:var(--accent)">{{ $s->nis }}</code></td>
                        <td style="text-align:center;white-space:nowrap">
                            <a href="{{ route('guru.rapot.preview', $s) }}" target="_blank"
                                class="badge badge-info" style="text-decoration:none;margin-right:0.5rem">
                                <i data-lucide="eye" style="width:12px;height:12px"></i> Preview
                            </a>
                            <a href="{{ route('guru.rapot.cetak', $s) }}"
                                class="badge badge-success" style="text-decoration:none">
                                <i data-lucide="download" style="width:12px;height:12px"></i> Unduh
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem">Tidak ada siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @else
        <div class="panel-body" style="text-align:center;color:var(--text-muted);padding:2rem">
            Pilih kelas di panel kiri untuk melihat daftar siswa.
        </div>
        @endif
    </div>
</div>
@endsection
