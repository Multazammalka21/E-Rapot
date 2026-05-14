@extends('layouts.dashboard')
@section('title', 'Tahun Ajaran')
@section('page-title', '🗓️ Tahun Ajaran')
@section('page-subtitle', 'Kelola data tahun ajaran dan semester')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')

<div style="display:flex;justify-content:flex-end;margin-bottom:1rem">
    <a href="{{ route('admin.tahun-ajaran.create') }}"
        style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:var(--radius-sm);color:white;text-decoration:none;font-size:0.85rem;font-weight:600">
        ➕ Tambah Tahun Ajaran
    </a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama Tahun Ajaran</th>
                    <th>Semester</th>
                    <th>Periode</th>
                    <th>Status</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataTa as $t)
                <tr>
                    <td style="color:var(--text);font-weight:600">{{ $t->nama }}</td>
                    <td>{{ ucfirst($t->semester) }}</td>
                    <td style="font-size:0.85rem;color:var(--text-muted)">
                        {{ $t->tanggal_mulai->format('d M Y') }} - {{ $t->tanggal_selesai->format('d M Y') }}
                    </td>
                    <td>
                        <span class="badge {{ $t->is_active ? 'badge-success' : 'badge-danger' }}">
                            {{ $t->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td style="text-align:center;white-space:nowrap">
                        <a href="{{ route('admin.tahun-ajaran.edit', $t) }}" style="color:var(--primary-light);text-decoration:none;font-size:0.8rem;margin-right:0.5rem">✏️ Edit</a>
                        <form method="POST" action="{{ route('admin.tahun-ajaran.destroy', $t) }}" style="display:inline"
                              onsubmit="return confirm('Hapus Tahun Ajaran {{ $t->nama }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;color:#fca5a5;cursor:pointer;font-size:0.8rem">🗑️ Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem">Tidak ada data tahun ajaran.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($dataTa->hasPages())
<div style="margin-top:1rem;display:flex;justify-content:center;gap:0.25rem">
    {{ $dataTa->links('pagination::bootstrap-4') }}
</div>
@endif
@endsection
