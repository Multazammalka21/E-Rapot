@php $isEdit = isset($tahun_ajaran); @endphp

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
    .btn-bar { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
    .btn-save { padding: 0.7rem 1.5rem; background: linear-gradient(135deg,var(--primary),var(--accent)); border: none; border-radius: 10px; color: white; font-weight: 600; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 0.9rem; }
    .btn-cancel { padding: 0.7rem 1.5rem; background: var(--surface); border: 1px solid var(--border); border-radius: 10px; color: var(--text-soft); text-decoration: none; font-size: 0.9rem; display:inline-flex; align-items:center; }
</style>

<div class="form-grid">
    <div class="fg">
        <label for="nama">Nama (contoh: 2025/2026) *</label>
        <input type="text" id="nama" name="nama"
            value="{{ old('nama', $isEdit ? $tahun_ajaran->nama : '') }}" required>
    </div>

    <div class="fg">
        <label for="semester">Semester *</label>
        <select id="semester" name="semester" required>
            <option value="ganjil" {{ old('semester', $isEdit ? $tahun_ajaran->semester : '') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
            <option value="genap" {{ old('semester', $isEdit ? $tahun_ajaran->semester : '') == 'genap' ? 'selected' : '' }}>Genap</option>
        </select>
    </div>

    <div class="fg">
        <label for="tanggal_mulai">Tanggal Mulai *</label>
        <input type="date" id="tanggal_mulai" name="tanggal_mulai"
            value="{{ old('tanggal_mulai', $isEdit ? $tahun_ajaran->tanggal_mulai->format('Y-m-d') : '') }}" required>
    </div>

    <div class="fg">
        <label for="tanggal_selesai">Tanggal Selesai *</label>
        <input type="date" id="tanggal_selesai" name="tanggal_selesai"
            value="{{ old('tanggal_selesai', $isEdit ? $tahun_ajaran->tanggal_selesai->format('Y-m-d') : '') }}" required>
    </div>

    <div class="fg form-full" style="display:flex;align-items:center;">
        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;margin:0">
            <input type="checkbox" name="is_active" value="1"
                {{ old('is_active', $isEdit ? $tahun_ajaran->is_active : false) ? 'checked' : '' }}
                style="width:18px;height:18px;accent-color:var(--primary)">
            <span style="font-size:0.85rem;color:var(--text);font-weight:600">Jadikan Tahun Ajaran Aktif (Menonaktifkan yang lain)</span>
        </label>
    </div>
</div>

<div class="btn-bar">
    <button type="submit" class="btn-save">💾 {{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
    <a href="{{ route('admin.tahun-ajaran.index') }}" class="btn-cancel">← Kembali</a>
</div>
