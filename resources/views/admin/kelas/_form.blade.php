@php $isEdit = isset($kelas); @endphp
<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
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
    .btn-cancel { padding: 0.7rem 1.5rem; background: var(--surface); border: 1px solid var(--border); border-radius: 10px; color: var(--text-soft); text-decoration: none; font-size: 0.9rem; }
</style>

<div class="form-grid">
    <div class="fg">
        <label for="nama_kelas">Nama Kelas *</label>
        <input type="text" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas', $isEdit ? $kelas->nama_kelas : '') }}" placeholder="7A, 8B, 9C" required>
    </div>
    <div class="fg">
        <label for="tingkat">Tingkat *</label>
        <select id="tingkat" name="tingkat" required>
            @foreach(['7','8','9'] as $t)
                <option value="{{ $t }}" {{ old('tingkat', $isEdit ? $kelas->tingkat : '') == $t ? 'selected' : '' }}>Kelas {{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div class="fg">
        <label for="tahun_ajaran_id">Tahun Ajaran *</label>
        <select id="tahun_ajaran_id" name="tahun_ajaran_id" required>
            <option value="">— Pilih —</option>
            @foreach($tahunAjaran as $ta)
                <option value="{{ $ta->id }}" {{ old('tahun_ajaran_id', $isEdit ? $kelas->tahun_ajaran_id : '') == $ta->id ? 'selected' : '' }}>{{ $ta->label }}</option>
            @endforeach
        </select>
    </div>
    <div class="fg">
        <label for="wali_kelas_id">Wali Kelas</label>
        <select id="wali_kelas_id" name="wali_kelas_id">
            <option value="">— Belum ditentukan —</option>
            @foreach($guru as $g)
                <option value="{{ $g->id }}" {{ old('wali_kelas_id', $isEdit ? $kelas->wali_kelas_id : '') == $g->id ? 'selected' : '' }}>{{ $g->nama_lengkap }}</option>
            @endforeach
        </select>
    </div>
    <div class="fg">
        <label for="kapasitas">Kapasitas</label>
        <input type="number" id="kapasitas" name="kapasitas" value="{{ old('kapasitas', $isEdit ? $kelas->kapasitas : 32) }}" min="1" max="50" required>
    </div>
</div>

<div class="btn-bar">
    <button type="submit" class="btn-save">💾 {{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
    <a href="{{ route('admin.kelas.index') }}" class="btn-cancel">← Kembali</a>
</div>
