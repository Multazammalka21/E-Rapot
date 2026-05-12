@extends('layouts.dashboard')
@section('title', 'Import Nilai Excel')
@section('page-title', '📤 Import Nilai via Excel')
@section('page-subtitle', 'Upload file Excel untuk input nilai massal')

@section('sidebar-nav')
@include('guru.partials.sidebar')
@endsection

@section('content')
@include('components.alerts')

<div class="panel" style="margin-bottom:1rem">
    <div class="panel-header"><span class="panel-title">📋 Panduan Import</span></div>
    <div class="panel-body">
        <ol style="color:var(--text-soft);font-size:0.85rem;line-height:2;padding-left:1.25rem">
            <li>Pilih kelas dan mata pelajaran yang ingin diimport nilainya</li>
            <li>Klik <strong style="color:var(--primary-light)">📥 Download Template</strong> untuk mendapatkan file Excel berisi daftar siswa</li>
            <li>Isi kolom <strong>Nilai SH, STS, SAS</strong> dengan angka 0–100</li>
            <li>Upload kembali file yang sudah diisi</li>
            <li>Nilai akhir dan predikat akan dihitung otomatis berdasarkan bobot mapel</li>
        </ol>
        <div style="background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.2);border-radius:8px;padding:0.75rem 1rem;font-size:0.8rem;color:#fcd34d;margin-top:0.75rem">
            ⚠️ Import hanya tersedia untuk format <strong>.xlsx</strong>. Nilai yang sudah ada akan <strong>ditimpa</strong>.
        </div>
    </div>
</div>

{{-- Assignment list --}}
<div class="panel">
    <div class="panel-header"><span class="panel-title">📚 Daftar Kelas & Mapel</span></div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Bobot SH/STS/SAS</th>
                    <th style="text-align:center">Template</th>
                    <th style="text-align:center">Upload Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $gm)
                <tr>
                    <td style="font-weight:700;color:var(--text)">{{ $gm->kelas?->nama_kelas }}</td>
                    <td>
                        <code style="background:rgba(79,70,229,0.15);color:var(--primary-light);padding:2px 8px;border-radius:5px;font-size:0.75rem">{{ $gm->mataPelajaran?->kode_mapel }}</code>
                        {{ $gm->mataPelajaran?->nama_mapel }}
                    </td>
                    <td style="font-size:0.8rem;color:var(--text-muted)">
                        {{ $gm->mataPelajaran?->bobot_sumatif_harian }}% /
                        {{ $gm->mataPelajaran?->bobot_sumatif_tengah }}% /
                        {{ $gm->mataPelajaran?->bobot_sumatif_akhir }}%
                    </td>
                    <td style="text-align:center">
                        <a href="{{ route('guru.import.template', [$gm->kelas_id, $gm->mata_pelajaran_id]) }}"
                            style="color:#6ee7b7;text-decoration:none;font-size:0.8rem;font-weight:600">
                            📥 Template
                        </a>
                    </td>
                    <td style="text-align:center">
                        <button onclick="openModal({{ $gm->kelas_id }}, {{ $gm->mata_pelajaran_id }}, '{{ $gm->kelas?->nama_kelas }}', '{{ $gm->mataPelajaran?->nama_mapel }}')"
                            style="background:rgba(79,70,229,0.15);border:1px solid rgba(79,70,229,0.3);border-radius:6px;color:var(--primary-light);cursor:pointer;font-size:0.8rem;padding:0.3rem 0.75rem;font-family:'Inter',sans-serif">
                            📤 Upload
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem">Tidak ada assignment mapel.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal upload --}}
<div id="uploadModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:999;align-items:center;justify-content:center">
    <div style="background:#1a1a2e;border:1px solid var(--border);border-radius:16px;padding:2rem;width:100%;max-width:480px;margin:1rem">
        <h3 style="color:var(--text);margin-bottom:0.25rem;font-size:1rem">📤 Upload Nilai Excel</h3>
        <p id="modalSubtitle" style="color:var(--text-muted);font-size:0.8rem;margin-bottom:1.5rem"></p>

        <form id="uploadForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="margin-bottom:1.25rem">
                <label style="display:block;font-size:0.75rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:0.5rem">File Excel (.xlsx)</label>
                <input type="file" name="file_excel" accept=".xlsx,.xls" required
                    style="width:100%;padding:0.6rem;background:rgba(255,255,255,0.05);border:1px solid var(--border);border-radius:8px;color:var(--text);font-family:'Inter',sans-serif;font-size:0.85rem">
            </div>
            <div style="display:flex;gap:0.75rem;justify-content:flex-end">
                <button type="button" onclick="closeModal()"
                    style="padding:0.6rem 1.25rem;background:var(--surface);border:1px solid var(--border);border-radius:8px;color:var(--text-soft);cursor:pointer;font-family:'Inter',sans-serif">
                    Batal
                </button>
                <button type="submit"
                    style="padding:0.6rem 1.25rem;background:linear-gradient(135deg,var(--primary),var(--accent));border:none;border-radius:8px;color:white;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif">
                    📤 Upload & Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openModal(kelasId, mapelId, namaKelas, namaMapel) {
    document.getElementById('modalSubtitle').textContent = 'Kelas ' + namaKelas + ' · ' + namaMapel;
    document.getElementById('uploadForm').action = `/guru/import/${kelasId}/${mapelId}`;
    document.getElementById('uploadModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('uploadModal').style.display = 'none';
}
document.getElementById('uploadModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endpush
@endsection
