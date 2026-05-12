{{-- Shared form fields for Guru create/edit --}}
@php $isEdit = isset($guru); @endphp

<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-full { grid-column: 1 / -1; }
    .fg { margin-bottom: 0; }
    .fg label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem; }
    .fg input, .fg select, .fg textarea {
        width: 100%; padding: 0.6rem 0.85rem; background: rgba(255,255,255,0.05);
        border: 1px solid var(--border); border-radius: 8px; color: var(--text);
        font-family: 'Inter', sans-serif; font-size: 0.85rem; outline: none; transition: border-color 0.2s;
    }
    .fg input:focus, .fg select:focus, .fg textarea:focus { border-color: var(--primary-light); }
    .fg select { appearance: auto; }
    .fg textarea { min-height: 80px; resize: vertical; }
    .fg .hint { font-size: 0.7rem; color: var(--text-muted); margin-top: 0.2rem; }
    .btn-bar { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
    .btn-save { padding: 0.7rem 1.5rem; background: linear-gradient(135deg,var(--primary),var(--accent)); border: none; border-radius: 10px; color: white; font-weight: 600; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 0.9rem; }
    .btn-save:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(79,70,229,0.3); }
    .btn-cancel { padding: 0.7rem 1.5rem; background: var(--surface); border: 1px solid var(--border); border-radius: 10px; color: var(--text-soft); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; }
</style>

<div class="form-grid">
    <div class="fg">
        <label for="nama_lengkap">Nama Lengkap *</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $isEdit ? $guru->nama_lengkap : '') }}" required>
    </div>
    <div class="fg">
        <label for="nip">NIP</label>
        <input type="text" id="nip" name="nip" value="{{ old('nip', $isEdit ? $guru->nip : '') }}" maxlength="20">
    </div>
    <div class="fg">
        <label for="email">Email Akun *</label>
        <input type="email" id="email" name="email" value="{{ old('email', $isEdit ? $guru->user?->email : '') }}" required>
    </div>
    <div class="fg">
        <label for="password">Password {{ $isEdit ? '(kosongkan jika tidak diubah)' : '*' }}</label>
        <input type="password" id="password" name="password" {{ $isEdit ? '' : 'required' }} minlength="6">
    </div>
    <div class="fg">
        <label for="jenis_kelamin">Jenis Kelamin *</label>
        <select id="jenis_kelamin" name="jenis_kelamin" required>
            <option value="L" {{ old('jenis_kelamin', $isEdit ? $guru->jenis_kelamin : '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin', $isEdit ? $guru->jenis_kelamin : '') == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>
    <div class="fg">
        <label for="bidang_studi">Bidang Studi</label>
        <input type="text" id="bidang_studi" name="bidang_studi" value="{{ old('bidang_studi', $isEdit ? $guru->bidang_studi : '') }}">
    </div>
    <div class="fg">
        <label for="gelar_depan">Gelar Depan</label>
        <input type="text" id="gelar_depan" name="gelar_depan" value="{{ old('gelar_depan', $isEdit ? $guru->gelar_depan : '') }}" placeholder="Dr., Prof.">
    </div>
    <div class="fg">
        <label for="gelar_belakang">Gelar Belakang</label>
        <input type="text" id="gelar_belakang" name="gelar_belakang" value="{{ old('gelar_belakang', $isEdit ? $guru->gelar_belakang : '') }}" placeholder="S.Pd., M.Pd.">
    </div>
    <div class="fg">
        <label for="tempat_lahir">Tempat Lahir</label>
        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $isEdit ? $guru->tempat_lahir : '') }}">
    </div>
    <div class="fg">
        <label for="tanggal_lahir">Tanggal Lahir</label>
        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $isEdit && $guru->tanggal_lahir ? $guru->tanggal_lahir->format('Y-m-d') : '') }}">
    </div>
    <div class="fg">
        <label for="no_hp">No HP</label>
        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $isEdit ? $guru->no_hp : '') }}" maxlength="15">
    </div>
    <div class="fg form-full">
        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat">{{ old('alamat', $isEdit ? $guru->alamat : '') }}</textarea>
    </div>
</div>

<div class="btn-bar">
    <button type="submit" class="btn-save">💾 {{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
    <a href="{{ route('admin.guru.index') }}" class="btn-cancel">← Kembali</a>
</div>
