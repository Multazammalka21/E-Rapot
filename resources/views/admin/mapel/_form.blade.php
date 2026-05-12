@php $isEdit = isset($mapel); @endphp

<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-full { grid-column: 1 / -1; }
    .fg label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem; }
    .fg input, .fg select {
        width: 100%; padding: 0.6rem 0.85rem; background: rgba(255,255,255,0.05);
        border: 1px solid var(--border); border-radius: 8px; color: var(--text);
        font-family: 'Inter', sans-serif; font-size: 0.85rem; outline: none;
    }
    .fg input:focus, .fg select:focus { border-color: var(--primary-light); }
    .fg select { appearance: auto; }
    .fg small { font-size: 0.72rem; color: var(--text-muted); display: block; margin-top: 0.25rem; }
    .bobot-sum {
        background: rgba(79,70,229,0.1); border: 1px solid rgba(79,70,229,0.2);
        border-radius: 8px; padding: 0.6rem 1rem; font-size: 0.82rem;
        color: var(--primary-light); grid-column: 1 / -1;
    }
    .btn-bar { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
    .btn-save { padding: 0.7rem 1.5rem; background: linear-gradient(135deg,var(--primary),var(--accent)); border: none; border-radius: 10px; color: white; font-weight: 600; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 0.9rem; }
    .btn-cancel { padding: 0.7rem 1.5rem; background: var(--surface); border: 1px solid var(--border); border-radius: 10px; color: var(--text-soft); text-decoration: none; font-size: 0.9rem; display:inline-flex; align-items:center; }
</style>

<div class="form-grid">
    {{-- Identitas --}}
    <div class="fg">
        <label for="kode_mapel">Kode Mapel *</label>
        <input type="text" id="kode_mapel" name="kode_mapel"
            value="{{ old('kode_mapel', $isEdit ? $mapel->kode_mapel : '') }}"
            placeholder="MTK, BIN, IPA..." maxlength="10" style="text-transform:uppercase" required>
    </div>
    <div class="fg">
        <label for="kelompok">Kelompok *</label>
        <select id="kelompok" name="kelompok" required>
            @foreach(['Umum','Pilihan','Muatan Lokal'] as $k)
                <option value="{{ $k }}" {{ old('kelompok', $isEdit ? $mapel->kelompok : 'Umum') == $k ? 'selected' : '' }}>{{ $k }}</option>
            @endforeach
        </select>
    </div>
    <div class="fg form-full">
        <label for="nama_mapel">Nama Mata Pelajaran *</label>
        <input type="text" id="nama_mapel" name="nama_mapel"
            value="{{ old('nama_mapel', $isEdit ? $mapel->nama_mapel : '') }}" maxlength="80" required>
    </div>

    {{-- KKTP --}}
    <div class="fg">
        <label for="kktp">KKTP (Kriteria Ketercapaian) *</label>
        <input type="number" id="kktp" name="kktp"
            value="{{ old('kktp', $isEdit ? $mapel->kktp : 70) }}"
            min="0" max="100" required>
        <small>Kurikulum Merdeka menggunakan KKTP sebagai pengganti KKM</small>
    </div>

    {{-- Status --}}
    <div class="fg" style="display:flex;flex-direction:column;justify-content:flex-end">
        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
            <input type="checkbox" name="is_active" value="1"
                {{ old('is_active', $isEdit ? $mapel->is_active : true) ? 'checked' : '' }}
                style="width:18px;height:18px;accent-color:var(--primary)">
            <span style="font-size:0.85rem;color:var(--text)">Mapel aktif (tampil di rapot)</span>
        </label>
    </div>

    {{-- Bobot Penilaian --}}
    <div style="grid-column:1/-1;font-size:0.78rem;font-weight:700;color:var(--primary-light);padding-top:0.5rem;border-top:1px solid var(--border)">
        ⚖️ Bobot Penilaian Kurikulum Merdeka (Total harus = 100%)
    </div>

    <div class="fg">
        <label for="bobot_sumatif_harian">Sumatif Harian (SH) %</label>
        <input type="number" id="bobot_sh" name="bobot_sumatif_harian"
            value="{{ old('bobot_sumatif_harian', $isEdit ? $mapel->bobot_sumatif_harian : 60) }}"
            min="0" max="100" oninput="updateTotal()" required>
        <small>Rata-rata ulangan harian / penugasan</small>
    </div>
    <div class="fg">
        <label for="bobot_sumatif_tengah">Sumatif Tengah Semester (STS) %</label>
        <input type="number" id="bobot_sts" name="bobot_sumatif_tengah"
            value="{{ old('bobot_sumatif_tengah', $isEdit ? $mapel->bobot_sumatif_tengah : 20) }}"
            min="0" max="100" oninput="updateTotal()" required>
        <small>Penilaian Tengah Semester (PTS)</small>
    </div>
    <div class="fg">
        <label for="bobot_sumatif_akhir">Sumatif Akhir Semester (SAS) %</label>
        <input type="number" id="bobot_sas" name="bobot_sumatif_akhir"
            value="{{ old('bobot_sumatif_akhir', $isEdit ? $mapel->bobot_sumatif_akhir : 20) }}"
            min="0" max="100" oninput="updateTotal()" required>
        <small>Penilaian Akhir Semester (PAS)</small>
    </div>

    {{-- Total bobot indicator --}}
    <div class="bobot-sum" id="bobotInfo">
        ✅ Total bobot: <strong id="totalBobot">100</strong>% — Valid
    </div>
</div>

<div class="btn-bar">
    <button type="submit" class="btn-save">💾 {{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
    <a href="{{ route('admin.mapel.index') }}" class="btn-cancel">← Kembali</a>
</div>

<script>
function updateTotal() {
    const sh  = parseInt(document.getElementById('bobot_sh').value)  || 0;
    const sts = parseInt(document.getElementById('bobot_sts').value) || 0;
    const sas = parseInt(document.getElementById('bobot_sas').value) || 0;
    const total = sh + sts + sas;
    const el = document.getElementById('totalBobot');
    const box = document.getElementById('bobotInfo');
    el.textContent = total;
    if (total === 100) {
        box.style.background = 'rgba(16,185,129,0.1)';
        box.style.borderColor = 'rgba(16,185,129,0.3)';
        box.style.color = '#6ee7b7';
        box.innerHTML = '✅ Total bobot: <strong>' + total + '</strong>% — Valid';
    } else {
        box.style.background = 'rgba(239,68,68,0.1)';
        box.style.borderColor = 'rgba(239,68,68,0.3)';
        box.style.color = '#fca5a5';
        box.innerHTML = '⚠️ Total bobot: <strong>' + total + '</strong>% — Harus tepat 100%';
    }
}
updateTotal();
</script>
