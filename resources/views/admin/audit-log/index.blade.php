@extends('layouts.dashboard')
@section('title', 'Audit Log')
@section('page-title', '📋 Audit Log')
@section('page-subtitle', 'Log aktivitas cetak rapot dan aksi lainnya')

@section('sidebar-nav')
@include('admin.partials.sidebar')
@endsection

@section('content')
@include('components.alerts')

<div class="panel">
    <div class="panel-header" style="display:flex;justify-content:space-between;align-items:center">
        <span class="panel-title">Riwayat Aktivitas</span>
        <form action="{{ route('admin.audit-log.index') }}" method="GET" style="display:flex;gap:0.5rem">
            <input type="text" name="search" class="form-input" placeholder="Cari nama atau aksi..." value="{{ request('search') }}" style="max-width:200px">
            <button type="submit" class="btn btn-primary" style="padding:0.5rem">🔍</button>
        </form>
    </div>
    
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Aktor</th>
                    <th>Role</th>
                    <th>Aksi</th>
                    <th>Siswa Terkait</th>
                    <th>Kelas</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="white-space:nowrap;font-size:0.85rem">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    <td style="font-weight:600">{{ $log->actor?->name ?? 'Sistem' }}</td>
                    <td>
                        <span class="badge" style="background:var(--surface);color:var(--text-muted)">
                            {{ strtoupper($log->actor_role ?? '-') }}
                        </span>
                    </td>
                    <td>
                        <span class="badge" style="background:rgba(79,70,229,0.15);color:var(--primary-light)">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td>{{ $log->siswa?->nama_lengkap ?? '-' }}</td>
                    <td>{{ $log->kelas?->nama_kelas ?? '-' }}</td>
                    <td style="font-size:0.8rem;color:var(--text-muted)">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted)">Belum ada log aktivitas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($logs->hasPages())
    <div class="panel-body" style="border-top:1px solid var(--border)">
        {{ $logs->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>
@endsection
