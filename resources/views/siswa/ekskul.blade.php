@extends('layouts.dashboard')
@section('title', 'Ekstrakurikuler') @section('page-title', '🏅 Ekstrakurikuler')
@section('page-subtitle', '{{ $ta->label }}')
@section('sidebar-nav') @include('siswa.partials.sidebar') @endsection

@section('content')
<div class="panel">
    <div class="panel-header"><span class="panel-title">Kegiatan Ekstrakurikuler yang Diikuti</span></div>
    @if($ekskul->isNotEmpty())
    <div class="table-wrap">
        <table>
            <thead><tr><th>#</th><th>Nama Kegiatan</th><th>Predikat</th><th>Keterangan</th></tr></thead>
            <tbody>
                @foreach($ekskul as $i => $e)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td style="color:var(--text);font-weight:600">{{ $e->ekstrakurikuler?->nama }}</td>
                    <td><span class="badge badge-{{ $e->predikat ?? 'C' }}">{{ $e->predikat ?? '—' }}</span></td>
                    <td style="font-size:0.8rem;color:var(--text-muted)">{{ $e->keterangan ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="panel-body" style="text-align:center;padding:3rem;color:var(--text-muted)">
        <div style="font-size:3rem;margin-bottom:1rem">🏅</div>
        <div>Anda belum terdaftar dalam kegiatan ekstrakurikuler semester ini.</div>
    </div>
    @endif
</div>
@endsection
