@extends('layouts.dashboard')
@section('title', 'Data Guru')
@section('page-title', '👨‍🏫 Data Guru')
@section('page-subtitle', 'Kelola data guru pengajar SMPN 1 Surabaya')

@section('sidebar-nav')
@include('admin.partials.sidebar')
@endsection

@section('content')
@include('components.alerts')

{{-- Header: Search + Add --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;gap:1rem;flex-wrap:wrap">
    <form method="GET" action="{{ route('admin.guru.index') }}" style="display:flex;gap:0.5rem;flex:1;max-width:400px">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, bidang studi..."
            style="flex:1;padding:0.6rem 1rem;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);color:var(--text);font-family:'Inter',sans-serif;font-size:0.85rem;outline:none">
        <button type="submit" style="padding:0.6rem 1rem;background:var(--primary);border:none;border-radius:var(--radius-sm);color:white;cursor:pointer;font-size:0.85rem;font-family:'Inter',sans-serif">🔍 Cari</button>
    </form>
    <a href="{{ route('admin.guru.create') }}"
        style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:var(--radius-sm);color:white;text-decoration:none;font-size:0.85rem;font-weight:600;white-space:nowrap">
        ➕ Tambah Guru
    </a>
</div>

{{-- Table --}}
<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Lengkap</th>
                    <th>NIP</th>
                    <th>JK</th>
                    <th>Bidang Studi</th>
                    <th>No HP</th>
                    <th>Email</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($guru as $i => $g)
                <tr>
                    <td>{{ $guru->firstItem() + $i }}</td>
                    <td style="color:var(--text);font-weight:600">{{ $g->nama_gelar }}</td>
                    <td><code style="font-size:0.75rem;color:var(--accent)">{{ $g->nip ?? '-' }}</code></td>
                    <td><span class="badge {{ $g->jenis_kelamin === 'L' ? 'badge-info' : 'badge-warning' }}">{{ $g->jenis_kelamin }}</span></td>
                    <td>{{ $g->bidang_studi ?? '-' }}</td>
                    <td>{{ $g->no_hp ?? '-' }}</td>
                    <td style="font-size:0.78rem">{{ $g->user?->email ?? '-' }}</td>
                    <td style="text-align:center;white-space:nowrap">
                        <a href="{{ route('admin.guru.edit', $g) }}" style="color:var(--primary-light);text-decoration:none;font-size:0.8rem;margin-right:0.5rem">✏️ Edit</a>
                        <form method="POST" action="{{ route('admin.guru.destroy', $g) }}" style="display:inline" onsubmit="return confirm('Hapus guru {{ $g->nama_lengkap }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#fca5a5;cursor:pointer;font-size:0.8rem">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:2rem">Tidak ada data guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Pagination --}}
@if($guru->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:center;gap:0.25rem">
    @foreach($guru->links()->elements[0] ?? [] as $page => $url)
        <a href="{{ $url }}" style="padding:0.4rem 0.75rem;border-radius:6px;font-size:0.8rem;text-decoration:none;
            {{ $page == $guru->currentPage() ? 'background:var(--primary);color:white' : 'background:var(--surface);color:var(--text-soft);border:1px solid var(--border)' }}">
            {{ $page }}
        </a>
    @endforeach
</div>
@endif
@endsection
