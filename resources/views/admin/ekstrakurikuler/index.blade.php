@extends('layouts.dashboard')
@section('title', 'Ekstrakurikuler')
@section('page-title', '🏅 Ekstrakurikuler')
@section('page-subtitle', 'Kelola kegiatan ekstrakurikuler dan pembina')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;gap:1rem;flex-wrap:wrap">
    <form method="GET" action="{{ route('admin.ekstrakurikuler.index') }}" style="display:flex;gap:0.5rem;flex:1;max-width:500px">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama ekstrakurikuler..."
            style="flex:1;padding:0.6rem 1rem;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text);font-family:'Inter',sans-serif;font-size:0.85rem;outline:none">
        <button type="submit" style="padding:0.6rem 1rem;background:var(--primary);border:none;border-radius:var(--radius-sm);color:white;cursor:pointer;font-size:0.85rem;font-family:'Inter',sans-serif">🔍</button>
    </form>
    <a href="{{ route('admin.ekstrakurikuler.create') }}"
        style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:var(--radius-sm);color:white;text-decoration:none;font-size:0.85rem;font-weight:600">
        ➕ Tambah Ekstrakurikuler
    </a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Ekstrakurikuler</th>
                    <th>Deskripsi</th>
                    <th>Pembina</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ekskul as $e)
                <tr>
                    <td style="color:var(--text);font-weight:600">{{ $e->nama }}</td>
                    <td style="font-size:0.85rem;color:var(--text-muted);max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $e->deskripsi }}">
                        {{ $e->deskripsi ?: '-' }}
                    </td>
                    <td>
                        @if($e->pembina)
                            <span style="color:var(--text-soft);font-size:0.85rem">{{ $e->pembina->nama_gelar }}</span>
                        @else
                            <span style="color:var(--text-muted);font-size:0.85rem;font-style:italic">Belum ada pembina</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $e->is_active ? 'badge-success' : 'badge-D' }}">
                            {{ $e->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td style="text-align:center;white-space:nowrap">
                        <a href="{{ route('admin.ekstrakurikuler.show', $e) }}" style="color:var(--info);text-decoration:none;font-size:0.8rem;margin-right:0.5rem">👥 Anggota</a>
                        <a href="{{ route('admin.ekstrakurikuler.edit', $e) }}" style="color:var(--primary-light);text-decoration:none;font-size:0.8rem;margin-right:0.5rem">✏️ Edit</a>
                        <form method="POST" action="{{ route('admin.ekstrakurikuler.destroy', $e) }}" style="display:inline"
                              onsubmit="return confirm('Hapus ekstrakurikuler {{ $e->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#fca5a5;cursor:pointer;font-size:0.8rem">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem">Tidak ada data ekstrakurikuler.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($ekskul->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:center;gap:0.25rem">
    @foreach($ekskul->links()->elements[0] ?? [] as $page => $url)
        <a href="{{ $url }}" style="padding:0.4rem 0.75rem;border-radius:6px;font-size:0.8rem;text-decoration:none;{{ $page == $ekskul->currentPage() ? 'background:var(--primary);color:white' : 'background:var(--surface);color:var(--text-soft);border:1px solid var(--border)' }}">{{ $page }}</a>
    @endforeach
</div>
@endif
@endsection
