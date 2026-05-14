@php $isEdit = isset($ekstrakurikuler); @endphp

<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-full { grid-column: 1 / -1; }
    .fg label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem; }
    .fg input, .fg select, .fg textarea {
        width: 100%; padding: 0.6rem 0.85rem; background: rgba(255,255,255,0.05);
        border: 1px solid var(--border); border-radius: 8px; color: var(--text);
        font-family: 'Inter', sans-serif; font-size: 0.85rem; outline: none;
    }
    .fg input:focus, .fg select:focus, .fg textarea:focus { border-color: var(--primary-light); }
    .fg select { appearance: auto; }
    .fg small { font-size: 0.72rem; color: var(--text-muted); display: block; margin-top: 0.25rem; }
    .btn-bar { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
    .btn-save { padding: 0.7rem 1.5rem; background: linear-gradient(135deg,var(--primary),var(--accent)); border: none; border-radius: 10px; color: white; font-weight: 600; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 0.9rem; }
    .btn-cancel { padding: 0.7rem 1.5rem; background: var(--surface); border: 1px solid var(--border); border-radius: 10px; color: var(--text-soft); text-decoration: none; font-size: 0.9rem; display:inline-flex; align-items:center; }
</style>

<div class="form-grid">
    <div class="fg form-full">
        <label for="nama">Nama Ekstrakurikuler *</label>
        <input type="text" id="nama" name="nama"
            value="{{ old('nama', $isEdit ? $ekstrakurikuler->nama : '') }}" maxlength="80" required>
    </div>

    <div class="fg form-full">
        <label for="deskripsi">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $isEdit ? $ekstrakurikuler->deskripsi : '') }}</textarea>
    </div>

    <div class="fg">
        <label for="pembina_id">Pembina (Guru)</label>
        <select id="pembina_id" name="pembina_id">
            <option value="">-- Pilih Pembina --</option>
            @foreach($gurus as $guru)
                <option value="{{ $guru->id }}" {{ old('pembina_id', $isEdit ? $ekstrakurikuler->pembina_id : '') == $guru->id ? 'selected' : '' }}>
                    {{ $guru->nama }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="fg" style="display:flex;flex-direction:column;justify-content:flex-end">
        <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer">
            <input type="checkbox" name="is_active" value="1"
                {{ old('is_active', $isEdit ? $ekstrakurikuler->is_active : true) ? 'checked' : '' }}
                style="width:18px;height:18px;accent-color:var(--primary)">
            <span style="font-size:0.85rem;color:var(--text)">Ekstrakurikuler Aktif</span>
        </label>
    </div>
</div>

<div class="btn-bar">
    <button type="submit" class="btn-save">💾 {{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
    <a href="{{ route('admin.ekstrakurikuler.index') }}" class="btn-cancel">← Kembali</a>
</div>
