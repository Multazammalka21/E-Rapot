@extends('layouts.dashboard')
@section('title', 'Data Kelas')
@section('page-title', '🏫 Data Kelas')
@section('page-subtitle', 'Kelola kelas per tahun ajaran')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;gap:1rem;flex-wrap:wrap">
    <form method="GET" action="{{ route('admin.kelas.index') }}" style="display:flex;gap:0.5rem;flex:1;max-width:500px">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelas..."
            style="flex:1;padding:0.6rem 1rem;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text);font-family:'Inter',sans-serif;font-size:0.85rem;outline:none">
        <select name="tahun_ajaran_id" style="padding:0.6rem;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text);font-family:'Inter',sans-serif;font-size:0.85rem;appearance:auto" onchange="this.form.submit()">
            <option value="">Semua TA</option>
            @foreach($tahunAjaran as $ta)
                <option value="{{ $ta->id }}" {{ request('tahun_ajaran_id') == $ta->id ? 'selected' : '' }}>{{ $ta->label }}</option>
            @endforeach
        </select>
        <button type="submit" style="padding:0.6rem 1rem;background:var(--primary);border:none;border-radius:var(--radius-sm);color:white;cursor:pointer;font-size:0.85rem;font-family:'Inter',sans-serif">🔍</button>
    </form>
    <a href="{{ route('admin.kelas.create') }}"
        style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:var(--radius-sm);color:white;text-decoration:none;font-size:0.85rem;font-weight:600">
        ➕ Tambah Kelas
    </a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Kelas</th><th>Tingkat</th><th>Tahun Ajaran</th><th>Wali Kelas</th><th>Siswa</th><th>Kapasitas</th><th style="text-align:center">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($kelas as $k)
                <tr>
                    <td style="color:var(--text);font-weight:700;font-size:1rem">{{ $k->nama_kelas }}</td>
                    <td><span class="badge badge-info">Kelas {{ $k->tingkat }}</span></td>
                    <td style="font-size:0.8rem">{{ $k->tahunAjaran?->label }}</td>
                    <td style="color:var(--text)">{{ $k->waliKelas?->nama_lengkap ?? '—' }}</td>
                    <td>
                        <span style="font-weight:700;color:{{ $k->siswa->count() >= $k->kapasitas ? '#fca5a5' : 'var(--text)' }}">{{ $k->siswa->count() }}</span>
                    </td>
                    <td style="color:var(--text-muted)">{{ $k->kapasitas }}</td>
                    <td style="text-align:center;white-space:nowrap">
                        <a href="{{ route('admin.kelas.show', $k) }}" style="color:var(--text-soft);text-decoration:none;font-size:0.8rem;margin-right:0.5rem" title="Lihat Anggota">👥</a>
                        <a href="{{ route('admin.kelas.edit', $k) }}" style="color:var(--primary-light);text-decoration:none;font-size:0.8rem;margin-right:0.5rem" title="Edit">✏️</a>
                        <form method="POST" action="{{ route('admin.kelas.destroy', $k) }}" style="display:inline" onsubmit="return confirm('Hapus kelas {{ $k->nama_kelas }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#fca5a5;cursor:pointer;font-size:0.8rem">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem">Tidak ada data kelas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($kelas->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:center;gap:0.25rem">
    @foreach($kelas->links()->elements[0] ?? [] as $page => $url)
        <a href="{{ $url }}" style="padding:0.4rem 0.75rem;border-radius:6px;font-size:0.8rem;text-decoration:none;{{ $page == $kelas->currentPage() ? 'background:var(--primary);color:white' : 'background:var(--surface);color:var(--text-soft);border:1px solid var(--border)' }}">{{ $page }}</a>
    @endforeach
</div>
@endif
@endsection
