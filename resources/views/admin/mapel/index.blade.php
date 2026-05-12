@extends('layouts.dashboard')
@section('title', 'Mata Pelajaran')
@section('page-title', '📚 Mata Pelajaran')
@section('page-subtitle', 'Kelola mapel Kurikulum Merdeka Fase D')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;gap:1rem;flex-wrap:wrap">
    <form method="GET" action="{{ route('admin.mapel.index') }}" style="display:flex;gap:0.5rem;flex:1;max-width:500px">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama mapel..."
            style="flex:1;padding:0.6rem 1rem;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text);font-family:'Inter',sans-serif;font-size:0.85rem;outline:none">
        <select name="kelompok" style="padding:0.6rem;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text);font-family:'Inter',sans-serif;font-size:0.85rem;appearance:auto" onchange="this.form.submit()">
            <option value="">Semua Kelompok</option>
            <option value="Umum" {{ request('kelompok')=='Umum' ? 'selected' : '' }}>Umum</option>
            <option value="Pilihan" {{ request('kelompok')=='Pilihan' ? 'selected' : '' }}>Pilihan</option>
            <option value="Muatan Lokal" {{ request('kelompok')=='Muatan Lokal' ? 'selected' : '' }}>Muatan Lokal</option>
        </select>
        <button type="submit" style="padding:0.6rem 1rem;background:var(--primary);border:none;border-radius:var(--radius-sm);color:white;cursor:pointer;font-size:0.85rem;font-family:'Inter',sans-serif">🔍</button>
    </form>
    <a href="{{ route('admin.mapel.create') }}"
        style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:var(--radius-sm);color:white;text-decoration:none;font-size:0.85rem;font-weight:600">
        ➕ Tambah Mapel
    </a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th>Kelompok</th>
                    <th>KKTP</th>
                    <th title="Bobot Sumatif Harian / Tengah / Akhir">Bobot SH/STS/SAS</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mapel as $m)
                <tr>
                    <td>
                        <code style="background:rgba(79,70,229,0.15);color:var(--primary-light);padding:2px 8px;border-radius:5px;font-size:0.78rem;font-weight:700">
                            {{ $m->kode_mapel }}
                        </code>
                    </td>
                    <td style="color:var(--text);font-weight:600">{{ $m->nama_mapel }}</td>
                    <td>
                        <span class="badge {{ $m->kelompok === 'Umum' ? 'badge-info' : ($m->kelompok === 'Pilihan' ? 'badge-warning' : 'badge-success') }}">
                            {{ $m->kelompok }}
                        </span>
                    </td>
                    <td>
                        <span style="font-weight:700;color:{{ $m->kktp >= 75 ? '#6ee7b7' : '#fcd34d' }}">{{ $m->kktp }}</span>
                    </td>
                    <td style="font-size:0.8rem;color:var(--text-muted)">
                        <span style="color:var(--text-soft)">{{ $m->bobot_sumatif_harian }}%</span> /
                        <span style="color:var(--text-soft)">{{ $m->bobot_sumatif_tengah }}%</span> /
                        <span style="color:var(--text-soft)">{{ $m->bobot_sumatif_akhir }}%</span>
                    </td>
                    <td>
                        <span class="badge {{ $m->is_active ? 'badge-success' : 'badge-D' }}">
                            {{ $m->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td style="text-align:center;white-space:nowrap">
                        <a href="{{ route('admin.mapel.edit', $m) }}" style="color:var(--primary-light);text-decoration:none;font-size:0.8rem;margin-right:0.5rem">✏️ Edit</a>
                        <form method="POST" action="{{ route('admin.mapel.destroy', $m) }}" style="display:inline"
                              onsubmit="return confirm('Hapus mapel {{ $m->nama_mapel }}? Data nilai terkait bisa terpengaruh.')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#fca5a5;cursor:pointer;font-size:0.8rem">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:2rem">Tidak ada data mata pelajaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($mapel->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:center;gap:0.25rem">
    @foreach($mapel->links()->elements[0] ?? [] as $page => $url)
        <a href="{{ $url }}" style="padding:0.4rem 0.75rem;border-radius:6px;font-size:0.8rem;text-decoration:none;{{ $page == $mapel->currentPage() ? 'background:var(--primary);color:white' : 'background:var(--surface);color:var(--text-soft);border:1px solid var(--border)' }}">{{ $page }}</a>
    @endforeach
</div>
@endif
@endsection
