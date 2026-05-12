@extends('layouts.dashboard')
@section('title', 'Data Siswa')
@section('page-title', '🎓 Data Siswa')
@section('page-subtitle', 'Kelola data siswa SMPN 1 Surabaya')

@section('sidebar-nav')
@include('admin.partials.sidebar')
@endsection

@section('content')
@include('components.alerts')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;gap:1rem;flex-wrap:wrap">
    <form method="GET" action="{{ route('admin.siswa.index') }}" style="display:flex;gap:0.5rem;flex:1;max-width:400px">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIS, NISN..."
            style="flex:1;padding:0.6rem 1rem;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text);font-family:'Inter',sans-serif;font-size:0.85rem;outline:none">
        <button type="submit" style="padding:0.6rem 1rem;background:var(--primary);border:none;border-radius:var(--radius-sm);color:white;cursor:pointer;font-size:0.85rem;font-family:'Inter',sans-serif">🔍</button>
    </form>
    <a href="{{ route('admin.siswa.create') }}"
        style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:var(--radius-sm);color:white;text-decoration:none;font-size:0.85rem;font-weight:600">
        ➕ Tambah Siswa
    </a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>#</th><th>Nama Lengkap</th><th>NIS</th><th>NISN</th><th>JK</th><th>Kelas</th><th>Agama</th><th style="text-align:center">Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($siswa as $i => $s)
                @php $kelasAktif = $s->siswaKelas->first()?->kelas; @endphp
                <tr>
                    <td>{{ $siswa->firstItem() + $i }}</td>
                    <td style="color:var(--text);font-weight:600">{{ $s->nama_lengkap }}</td>
                    <td><code style="font-size:0.75rem;color:var(--accent)">{{ $s->nis }}</code></td>
                    <td style="font-size:0.78rem">{{ $s->nisn }}</td>
                    <td><span class="badge {{ $s->jenis_kelamin === 'L' ? 'badge-info' : 'badge-warning' }}">{{ $s->jenis_kelamin }}</span></td>
                    <td><span class="badge badge-success">{{ $kelasAktif?->nama_kelas ?? '-' }}</span></td>
                    <td style="font-size:0.8rem">{{ $s->agama }}</td>
                    <td style="text-align:center;white-space:nowrap">
                        <a href="{{ route('admin.siswa.edit', $s) }}" style="color:var(--primary-light);text-decoration:none;font-size:0.8rem;margin-right:0.5rem">✏️</a>
                        <form method="POST" action="{{ route('admin.siswa.destroy', $s) }}" style="display:inline" onsubmit="return confirm('Hapus siswa {{ $s->nama_lengkap }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#fca5a5;cursor:pointer;font-size:0.8rem">🗑️</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem">Tidak ada data siswa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($siswa->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:center;gap:0.25rem">
    @foreach($siswa->links()->elements[0] ?? [] as $page => $url)
        <a href="{{ $url }}" style="padding:0.4rem 0.75rem;border-radius:6px;font-size:0.8rem;text-decoration:none;
            {{ $page == $siswa->currentPage() ? 'background:var(--primary);color:white' : 'background:var(--surface);color:var(--text-soft);border:1px solid var(--border)' }}">
            {{ $page }}
        </a>
    @endforeach
</div>
@endif
@endsection
