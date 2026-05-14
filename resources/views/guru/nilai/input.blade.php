@extends('layouts.dashboard')
@section('title', 'Input Nilai — ' . $mapel->kode_mapel . ' ' . $kelas->nama_kelas)
@section('page-title', "📝 Input Nilai: {$mapel->nama_mapel}")
@section('page-subtitle', "Kelas {$kelas->nama_kelas} · {$ta->label} · KKTP: {$mapel->kktp}")

@section('sidebar-nav')
<div class="nav-section-title">Utama</div>
<a href="{{ route('guru.dashboard') }}" class="nav-item"><span class="nav-icon">📊</span> Dashboard</a>
<div class="nav-section-title">Nilai</div>
<a href="{{ route('guru.nilai.index') }}" class="nav-item"><span class="nav-icon">📝</span> Daftar Mapel</a>
<div class="nav-section-title">Rapot</div>
<a href="#" class="nav-item"><span class="nav-icon">📄</span> Cetak Rapot PDF</a>
@endsection

@push('styles')
<style>
    .nilai-table { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .nilai-table th { padding: 0.6rem 0.75rem; text-align: center; font-size: 0.7rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.06em; border-bottom: 2px solid var(--border); background: rgba(79,70,229,0.05); }
    .nilai-table th.left { text-align: left; }
    .nilai-table td { padding: 0.5rem 0.6rem; border-bottom: 1px solid rgba(255,255,255,0.04); vertical-align: middle; }
    .nilai-table tr:hover td { background: rgba(255,255,255,0.02); }
    .nilai-input {
        width: 64px; padding: 0.45rem 0.5rem; text-align: center;
        background: rgba(255,255,255,0.06); border: 1px solid var(--border);
        border-radius: 7px; color: var(--text); font-family: 'Inter', sans-serif;
        font-size: 0.85rem; outline: none; transition: all 0.15s;
    }
    .nilai-input:focus { border-color: var(--primary-light); background: rgba(79,70,229,0.1); }
    .nilai-input:disabled { opacity: 0.5; cursor: not-allowed; }
    .nilai-akhir-cell { font-weight: 800; font-size: 1rem; text-align: center; }
    .catatan-input {
        width: 100%; padding: 0.4rem 0.6rem; background: rgba(255,255,255,0.04);
        border: 1px solid var(--border); border-radius: 7px; color: var(--text);
        font-family: 'Inter', sans-serif; font-size: 0.78rem; resize: none; outline: none;
    }
    .catatan-input:focus { border-color: var(--primary-light); }
    .bobot-info {
        display: inline-flex; gap: 1rem; background: rgba(79,70,229,0.1);
        border: 1px solid rgba(79,70,229,0.2); border-radius: 8px;
        padding: 0.5rem 1rem; font-size: 0.78rem; color: var(--primary-light);
    }
</style>
@endpush

@section('content')
@include('components.alerts')

{{-- Info bobot + status --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;flex-wrap:wrap;gap:0.75rem">
    <div class="bobot-info">
        <span>⚖️ SH: <strong>{{ $mapel->bobot_sumatif_harian }}%</strong></span>
        <span>STS: <strong>{{ $mapel->bobot_sumatif_tengah }}%</strong></span>
        <span>SAS: <strong>{{ $mapel->bobot_sumatif_akhir }}%</strong></span>
        <span>KKTP: <strong>{{ $mapel->kktp }}</strong></span>
    </div>
    @if($isFinal)
    <span style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);color:#6ee7b7;padding:0.4rem 1rem;border-radius:20px;font-size:0.8rem;font-weight:700">
        🔒 Nilai sudah difinalisasi — hanya lihat
    </span>
    @endif
</div>

<form method="POST" action="{{ route('guru.nilai.store', [$kelas->id, $mapel->id]) }}" id="formNilai">
    @csrf

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">
                Nilai {{ $mapel->nama_mapel }} — Kelas {{ $kelas->nama_kelas }}
                <span style="color:var(--text-muted);font-weight:400">({{ $siswaList->count() }} siswa)</span>
            </span>
            @if(!$isFinal)
            <div style="display:flex;gap:0.5rem">
                <button type="button" onclick="isiRandom()" style="padding:0.4rem 0.85rem;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);border-radius:7px;color:#fcd34d;cursor:pointer;font-size:0.78rem;font-family:'Inter',sans-serif">🎲 Isi Demo</button>
                <button type="submit" style="padding:0.4rem 0.85rem;background:linear-gradient(135deg,var(--primary),var(--accent));border:none;border-radius:7px;color:white;cursor:pointer;font-size:0.78rem;font-weight:600;font-family:'Inter',sans-serif">💾 Simpan Semua</button>
            </div>
            @endif
        </div>

        <div class="table-wrap">
            <table class="nilai-table">
                <thead>
                    <tr>
                        <th class="left" style="width:30px">#</th>
                        <th class="left" style="width:200px">Nama Siswa</th>
                        <th style="width:60px">NIS</th>
                        <th style="width:70px">SH<br><small style="font-weight:400;font-size:0.65rem">0–100</small></th>
                        <th style="width:70px">STS<br><small style="font-weight:400;font-size:0.65rem">0–100</small></th>
                        <th style="width:70px">SAS<br><small style="font-weight:400;font-size:0.65rem">0–100</small></th>
                        <th style="width:80px">Nilai Akhir</th>
                        <th style="width:40px">P</th>
                        <th>Catatan Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaList as $i => $sk)
                    @php
                        $existing = $nilaiExisting[$sk->siswa_id] ?? null;
                        $sh  = $existing?->nilai_sh ?? '';
                        $sts = $existing?->nilai_sts ?? '';
                        $sas = $existing?->nilai_sas ?? '';
                        $na  = $existing?->nilai_akhir ?? '';
                        $pr  = $existing?->predikat ?? '';
                    @endphp
                    <tr data-bobot-sh="{{ $mapel->bobot_sumatif_harian }}"
                        data-bobot-sts="{{ $mapel->bobot_sumatif_tengah }}"
                        data-bobot-sas="{{ $mapel->bobot_sumatif_akhir }}"
                        data-kktp="{{ $mapel->kktp }}">
                        <td style="color:var(--text-muted)">{{ $sk->nomor_urut }}</td>
                        <td style="color:var(--text);font-weight:600">
                            {{ $sk->siswa?->nama_lengkap }}
                            <input type="hidden" name="nilai[{{ $i }}][siswa_id]" value="{{ $sk->siswa_id }}">
                        </td>
                        <td style="text-align:center;font-size:0.75rem;color:var(--accent)">{{ $sk->siswa?->nis }}</td>
                        <td style="text-align:center">
                            <input type="number" name="nilai[{{ $i }}][nilai_sh]" class="nilai-input sh-input"
                                value="{{ old("nilai.$i.nilai_sh", $sh) }}"
                                min="0" max="100" {{ $isFinal ? 'disabled' : 'required' }}
                                oninput="hitungAkhir(this.closest('tr'))">
                        </td>
                        <td style="text-align:center">
                            <input type="number" name="nilai[{{ $i }}][nilai_sts]" class="nilai-input sts-input"
                                value="{{ old("nilai.$i.nilai_sts", $sts) }}"
                                min="0" max="100" {{ $isFinal ? 'disabled' : 'required' }}
                                oninput="hitungAkhir(this.closest('tr'))">
                        </td>
                        <td style="text-align:center">
                            <input type="number" name="nilai[{{ $i }}][nilai_sas]" class="nilai-input sas-input"
                                value="{{ old("nilai.$i.nilai_sas", $sas) }}"
                                min="0" max="100" {{ $isFinal ? 'disabled' : 'required' }}
                                oninput="hitungAkhir(this.closest('tr'))">
                        </td>
                        <td class="nilai-akhir-cell">
                            <span class="na-display" style="color:{{ $na !== '' ? ($na >= $mapel->kktp ? '#6ee7b7' : '#fca5a5') : 'var(--text-muted)' }}">
                                {{ $na !== '' ? number_format($na, 1) : '—' }}
                            </span>
                        </td>
                        <td style="text-align:center">
                            <span class="pr-display badge {{ $pr ? 'badge-'.$pr : '' }}">{{ $pr ?: '—' }}</span>
                        </td>
                        <td>
                            <textarea name="nilai[{{ $i }}][catatan_guru]" class="catatan-input" rows="1"
                                placeholder="Komentar opsional..." {{ $isFinal ? 'disabled' : '' }}>{{ old("nilai.$i.catatan_guru", $existing?->catatan_guru) }}</textarea>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(!$isFinal)
        <div style="padding:1rem 1.25rem;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:0.75rem">
            <a href="{{ route('guru.nilai.index') }}" style="padding:0.65rem 1.25rem;background:var(--surface);border:1px solid var(--border);border-radius:10px;color:var(--text-soft);text-decoration:none;font-size:0.875rem">← Kembali</a>
            <button type="submit" style="padding:0.65rem 1.5rem;background:linear-gradient(135deg,var(--primary),var(--accent));border:none;border-radius:10px;color:white;font-weight:700;cursor:pointer;font-size:0.875rem;font-family:'Inter',sans-serif">
                💾 Simpan Semua Nilai
            </button>
        </div>
        @endif
    </div>
</form>
@endsection

@push('scripts')
<script>
const bshArr  = {{ $mapel->bobot_sumatif_harian }};
const bstsArr = {{ $mapel->bobot_sumatif_tengah }};
const bsasArr = {{ $mapel->bobot_sumatif_akhir }};
const kktp    = {{ $mapel->kktp }};

function hitungAkhir(row) {
    const sh  = parseFloat(row.querySelector('.sh-input').value)  || 0;
    const sts = parseFloat(row.querySelector('.sts-input').value) || 0;
    const sas = parseFloat(row.querySelector('.sas-input').value) || 0;

    const na = ((sh * bshArr + sts * bstsArr + sas * bsasArr) / 100).toFixed(1);
    const pr = na >= 90 ? 'A' : na >= 80 ? 'B' : na >= 70 ? 'C' : 'D';

    const naEl = row.querySelector('.na-display');
    naEl.textContent = na;
    naEl.style.color = na >= kktp ? '#6ee7b7' : '#fca5a5';

    const prEl = row.querySelector('.pr-display');
    prEl.textContent = pr;
    prEl.className = 'pr-display badge badge-' + pr;
}

// Inisialisasi semua baris yang sudah ada nilainya
document.querySelectorAll('tr[data-kktp]').forEach(row => {
    if (row.querySelector('.sh-input')?.value) hitungAkhir(row);
});

// Isi demo random untuk testing
function isiRandom() {
    if (!confirm('Isi semua nilai dengan data random? (untuk testing)')) return;
    document.querySelectorAll('tr[data-kktp]').forEach(row => {
        const base = Math.floor(Math.random() * 35) + 60; // 60–95
        row.querySelector('.sh-input').value  = Math.min(100, base + Math.floor(Math.random()*10-5));
        row.querySelector('.sts-input').value = Math.min(100, base + Math.floor(Math.random()*10-5));
        row.querySelector('.sas-input').value = Math.min(100, base + Math.floor(Math.random()*10-5));
        hitungAkhir(row);
    });
}
</script>
@endpush
