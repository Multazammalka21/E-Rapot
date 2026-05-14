@extends('layouts.dashboard')
@section('title', 'Anggota Ekstrakurikuler')
@section('page-title', '👥 Anggota: ' . $ekstrakurikuler->nama)
@section('page-subtitle', 'Kelola data siswa yang mengikuti kegiatan ini')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')

<div style="display:flex;gap:1.5rem;flex-wrap:wrap">
    <!-- Form Tambah Anggota -->
    <div class="panel" style="flex:1;min-width:300px;align-self:flex-start">
        <div class="panel-header"><span class="panel-title">➕ Tambah Anggota</span></div>
        <div class="panel-body">
            @if(!$ta)
                <div style="color:var(--danger);font-size:0.85rem;margin-bottom:1rem">Belum ada Tahun Ajaran aktif.</div>
            @else
                <form method="POST" action="{{ route('admin.ekstrakurikuler.tambah-anggota', $ekstrakurikuler) }}">
                    @csrf
                    <div style="margin-bottom:1rem">
                        <label for="siswa_id" style="display:block;font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;margin-bottom:0.35rem">Pilih Siswa</label>
                        <select id="siswa_id" name="siswa_id" required style="width:100%;padding:0.6rem;border:1px solid var(--border);border-radius:8px;font-family:'Inter',sans-serif;font-size:0.85rem">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaLain as $s)
                                <option value="{{ $s->id }}">{{ $s->nis }} - {{ $s->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" style="padding:0.6rem 1.25rem;background:var(--primary);border:none;border-radius:8px;color:white;font-weight:600;cursor:pointer;width:100%">Tambah</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Daftar Anggota -->
    <div class="panel" style="flex:2;min-width:400px">
        <div class="panel-header"><span class="panel-title">📋 Daftar Anggota (Tahun Ajaran: {{ $ta ? $ta->nama : '-' }})</span></div>
        <div class="table-wrap" style="margin:0;padding:0">
            <table style="margin:0;width:100%">
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th style="text-align:center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($anggota as $a)
                    <tr>
                        <td style="color:var(--text);font-weight:600">{{ $a->siswa->nis }}</td>
                        <td>{{ $a->siswa->nama_lengkap }}</td>
                        <td style="text-align:center">
                            <form method="POST" action="{{ route('admin.ekstrakurikuler.hapus-anggota', [$ekstrakurikuler->id, $a->id]) }}" style="display:inline"
                                  onsubmit="return confirm('Hapus siswa {{ $a->siswa->nama_lengkap }} dari ekstrakurikuler ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" style="background:none;border:none;color:#fca5a5;cursor:pointer;font-size:0.8rem">🗑️ Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem">Belum ada anggota.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div style="margin-top:1.5rem">
    <a href="{{ route('admin.ekstrakurikuler.index') }}" style="padding:0.6rem 1.25rem;background:var(--surface);border:1px solid var(--border);border-radius:10px;color:var(--text-soft);text-decoration:none;font-size:0.85rem">← Kembali</a>
</div>
@endsection
