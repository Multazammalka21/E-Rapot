@extends('layouts.dashboard')
@section('title', 'Anggota Kelas')
@section('page-title', "👥 Anggota {$kelas->nama_kelas}")
@section('page-subtitle', 'Tahun Ajaran: ' . $kelas->tahunAjaran->label . ' | Wali Kelas: ' . ($kelas->waliKelas->nama_lengkap ?? '-'))
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')

<style>
    .grid-2 { display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem; align-items: start; }
    .panel { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 1.5rem; }
    .panel-header { padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); background: rgba(255,255,255,0.02); display: flex; justify-content: space-between; align-items: center; }
    .panel-title { font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 0.5rem; }
    .panel-body { padding: 1.25rem; }
    
    .form-group label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.35rem; }
    .form-group select { width: 100%; padding: 0.6rem 0.85rem; background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 8px; color: var(--text); margin-bottom: 1rem; }
    .btn-add { width: 100%; padding: 0.7rem; background: linear-gradient(135deg, var(--primary), var(--accent)); border: none; border-radius: 8px; color: white; font-weight: 600; cursor: pointer; }
    
    .table-container { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 0.75rem 1rem; border-bottom: 1px solid var(--border); color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; }
    td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border); color: var(--text); font-size: 0.85rem; }
    tr:last-child td { border-bottom: none; }
    .btn-danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); padding: 0.4rem 0.75rem; border-radius: 6px; text-decoration: none; font-size: 0.75rem; cursor: pointer; display: inline-flex; align-items: center; gap: 0.25rem; }
    .btn-danger:hover { background: rgba(239, 68, 68, 0.2); }
</style>

<div class="grid-2">
    <!-- Form Tambah Siswa -->
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">➕ Tambah Siswa</span>
        </div>
        <div class="panel-body">
            @if($kelas->siswa->count() >= $kelas->kapasitas)
                <div style="padding: 1rem; background: rgba(239,68,68,0.1); color: #ef4444; border-radius: 8px; font-size: 0.85rem; text-align: center;">
                    Kapasitas kelas penuh ({{ $kelas->kapasitas }} siswa).
                </div>
            @else
                <form action="{{ route('admin.kelas.tambah-anggota', $kelas) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Pilih Siswa</label>
                        <select name="siswa_id" required>
                            <option value="">— Pilih Siswa —</option>
                            @foreach($siswaTersedia as $s)
                                <option value="{{ $s->id }}">{{ $s->nis }} - {{ $s->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-add">Tambahkan ke Kelas</button>
                </form>
            @endif
        </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">📋 Daftar Siswa ({{ $kelas->siswa->count() }}/{{ $kelas->kapasitas }})</span>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>L/P</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kelas->siswa as $index => $s)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $s->nis }}</td>
                        <td style="font-weight: 500;">{{ $s->nama_lengkap }}</td>
                        <td>{{ $s->jenis_kelamin }}</td>
                        <td style="text-align:right">
                            <form action="{{ route('admin.kelas.hapus-anggota', [$kelas, $s->id]) }}" method="POST" onsubmit="return confirm('Hapus siswa ini dari kelas?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger">🗑️ Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">
                            Belum ada siswa di kelas ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
