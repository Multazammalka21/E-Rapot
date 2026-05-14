@extends('layouts.dashboard')
@section('title', 'Rekap Nilai Siswa')
@section('page-title', '📝 Rekap Nilai Siswa')
@section('page-subtitle', 'Pantau pengisian nilai oleh guru')
@section('sidebar-nav') @include('admin.partials.sidebar') @endsection

@section('content')
@include('components.alerts')

@if(!$ta)
<div style="padding:1.5rem;background:#fee2e2;color:#dc2626;border-radius:10px;margin-bottom:1rem">
    Belum ada Tahun Ajaran aktif. Silakan aktifkan terlebih dahulu di menu Tahun Ajaran.
</div>
@endif

<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Daftar Kelas (Tahun Ajaran: {{ $ta ? $ta->nama : '-' }})</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Wali Kelas</th>
                    <th>Jumlah Siswa</th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas as $k)
                <tr>
                    <td>
                        <div style="font-weight:700;color:var(--accent)">Kelas {{ $k->nama_kelas }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted)">Tingkat {{ $k->tingkat }}</div>
                    </td>
                    <td>{{ $k->waliKelas ? $k->waliKelas->nama : '-' }}</td>
                    <td>{{ $k->siswa_count }} Siswa</td>
                    <td style="text-align:center">
                        <button onclick="alert('Fitur rincian nilai sedang dalam pengembangan.')" style="padding:0.4rem 0.8rem;background:var(--surface-2);border:1px solid var(--border);border-radius:6px;cursor:pointer;font-size:0.8rem;color:var(--primary);font-weight:600">
                            Lihat Nilai
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem">Tidak ada data kelas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
