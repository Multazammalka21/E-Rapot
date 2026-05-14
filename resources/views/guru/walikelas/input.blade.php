@extends('layouts.dashboard')
@section('title', 'Input Wali Kelas')
@section('page-title', '📝 Input Absensi & Catatan')
@section('page-subtitle', 'Kelas ' . $kelas->nama_kelas . ' — ' . $ta->label)

@section('sidebar-nav')
    @include('guru.partials.sidebar')
@endsection

@section('content')
@include('components.alerts')

<div class="panel">
    <div class="panel-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem">
        <h2 class="panel-title">Data Siswa Kelas {{ $kelas->nama_kelas }}</h2>
        <a href="{{ route('guru.walikelas.index') }}" style="padding:0.65rem 1.25rem;background:var(--surface);border:1px solid var(--border);border-radius:10px;color:var(--text-soft);text-decoration:none;font-size:0.875rem">← Kembali</a>
    </div>
    
    <div class="panel-body">
        <form action="{{ route('guru.walikelas.store', $kelas->id) }}" method="POST">
            @csrf
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th style="width:200px">Siswa</th>
                            <th style="width:80px;text-align:center" title="Hadir">H</th>
                            <th style="width:80px;text-align:center" title="Sakit">S</th>
                            <th style="width:80px;text-align:center" title="Izin">I</th>
                            <th style="width:80px;text-align:center" title="Tanpa Keterangan (Alpha)">A</th>
                            <th>Catatan Wali Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($siswaKelas as $sk)
                        @php
                            $ab = $sk->siswa->absensi->first();
                            $cat = $sk->siswa->catatanWaliKelas->first();
                        @endphp
                        <tr>
                            <td>{{ $sk->nomor_urut }}</td>
                            <td>
                                <div style="font-weight:600">{{ $sk->siswa?->nama_lengkap }}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted)">NIS: {{ $sk->siswa?->nis }}</div>
                            </td>
                            <td>
                                <input type="number" name="data[{{ $sk->siswa_id }}][hadir]" class="form-control" value="{{ old('data.'.$sk->siswa_id.'.hadir', $ab->hadir ?? 0) }}" min="0" required style="padding:0.4rem;text-align:center">
                            </td>
                            <td>
                                <input type="number" name="data[{{ $sk->siswa_id }}][sakit]" class="form-control" value="{{ old('data.'.$sk->siswa_id.'.sakit', $ab->sakit ?? 0) }}" min="0" required style="padding:0.4rem;text-align:center">
                            </td>
                            <td>
                                <input type="number" name="data[{{ $sk->siswa_id }}][izin]" class="form-control" value="{{ old('data.'.$sk->siswa_id.'.izin', $ab->izin ?? 0) }}" min="0" required style="padding:0.4rem;text-align:center">
                            </td>
                            <td>
                                <input type="number" name="data[{{ $sk->siswa_id }}][alpha]" class="form-control" value="{{ old('data.'.$sk->siswa_id.'.alpha', $ab->alpha ?? 0) }}" min="0" required style="padding:0.4rem;text-align:center">
                            </td>
                            <td>
                                <textarea name="data[{{ $sk->siswa_id }}][catatan]" class="form-control" rows="2" placeholder="Catatan untuk siswa..." style="padding:0.5rem;font-size:0.85rem">{{ old('data.'.$sk->siswa_id.'.catatan', $cat->catatan ?? '') }}</textarea>
                            </td>
                        </tr>
                        @endforeach
                        @if($siswaKelas->isEmpty())
                        <tr>
                            <td colspan="7" style="text-align:center;padding:2rem;color:var(--text-muted)">Belum ada siswa di kelas ini.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            @if($siswaKelas->isNotEmpty())
            <div style="margin-top:2rem;display:flex;justify-content:flex-end">
                <button type="submit" style="padding:0.75rem 2rem;background:var(--primary);border:none;border-radius:8px;color:white;font-weight:600;cursor:pointer;font-size:0.9rem">
                    💾 Simpan Semua Data
                </button>
            </div>
            @endif
        </form>
    </div>
</div>
@endsection
