@php $isEdit = isset($siswa); @endphp

<style>
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-full { grid-column: 1 / -1; }
    .fg { margin-bottom: 0; }
    .fg label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.35rem; }
    .fg input, .fg select, .fg textarea {
        width: 100%; padding: 0.6rem 0.85rem; background: rgba(255,255,255,0.05);
        border: 1px solid var(--border); border-radius: 8px; color: var(--text);
        font-family: 'Inter', sans-serif; font-size: 0.85rem; outline: none;
    }
    .fg input:focus, .fg select:focus { border-color: var(--primary-light); }
    .fg select { appearance: auto; }
    .fg textarea { min-height: 70px; resize: vertical; }
    .section-title { font-size: 0.8rem; font-weight: 700; color: var(--primary-light); margin: 1.5rem 0 0.75rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); grid-column: 1 / -1; }
    .btn-bar { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
    .btn-save { padding: 0.7rem 1.5rem; background: linear-gradient(135deg,var(--primary),var(--accent)); border: none; border-radius: 10px; color: white; font-weight: 600; cursor: pointer; font-family: 'Inter', sans-serif; font-size: 0.9rem; }
    .btn-cancel { padding: 0.7rem 1.5rem; background: var(--surface); border: 1px solid var(--border); border-radius: 10px; color: var(--text-soft); text-decoration: none; font-size: 0.9rem; display: inline-flex; align-items: center; }
</style>

<div class="form-grid">
    <div class="section-title">📋 Identitas Siswa</div>
    <div class="fg">
        <label for="nama_lengkap">Nama Lengkap *</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $isEdit ? $siswa->nama_lengkap : '') }}" required>
    </div>
    <div class="fg">
        <label for="jenis_kelamin">Jenis Kelamin *</label>
        <select id="jenis_kelamin" name="jenis_kelamin" required>
            <option value="L" {{ old('jenis_kelamin', $isEdit ? $siswa->jenis_kelamin : '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
            <option value="P" {{ old('jenis_kelamin', $isEdit ? $siswa->jenis_kelamin : '') == 'P' ? 'selected' : '' }}>Perempuan</option>
        </select>
    </div>
    @if($isEdit)
    <div class="fg">
        <label for="nis">NIS (Sekolah) *</label>
        <input type="text" id="nis" name="nis" value="{{ old('nis', $siswa->nis) }}" maxlength="8" readonly style="background: rgba(255,255,255,0.02); opacity: 0.7; cursor: not-allowed;">
    </div>
    @else
    <div class="fg">
        <label>NIS (Sekolah)</label>
        <input type="text" value="Dibuat Otomatis" readonly style="background: rgba(255,255,255,0.02); opacity: 0.7; cursor: not-allowed;">
    </div>
    @endif
    <div class="fg">
        <label for="nisn">NISN (10 digit) *</label>
        <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $isEdit ? $siswa->nisn : '') }}" maxlength="10" required>
    </div>
    <div class="fg">
        <label for="tempat_lahir">Tempat Lahir</label>
        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $isEdit ? $siswa->tempat_lahir : '') }}">
    </div>
    <div class="fg">
        <label for="tanggal_lahir">Tanggal Lahir</label>
        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $isEdit && $siswa->tanggal_lahir ? $siswa->tanggal_lahir->format('Y-m-d') : '') }}">
    </div>
    <div class="fg">
        <label for="agama">Agama</label>
        <select id="agama" name="agama">
            @foreach(['Islam','Protestan','Katolik','Hindu','Buddha','Konghucu'] as $a)
                <option value="{{ $a }}" {{ old('agama', $isEdit ? $siswa->agama : 'Islam') == $a ? 'selected' : '' }}>{{ $a }}</option>
            @endforeach
        </select>
    </div>
    <div class="fg">
        <label for="no_hp">No HP</label>
        <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $isEdit ? $siswa->no_hp : '') }}">
    </div>
    <div class="fg form-full">
        <label for="alamat">Alamat Siswa</label>
        <textarea id="alamat" name="alamat">{{ old('alamat', $isEdit ? $siswa->alamat : '') }}</textarea>
    </div>

    <div class="section-title">👨‍👩‍👧 Data Orang Tua / Wali</div>
    <div class="fg"><label for="nama_ayah">Nama Ayah</label><input type="text" id="nama_ayah" name="nama_ayah" value="{{ old('nama_ayah', $isEdit ? $siswa->nama_ayah : '') }}"></div>
    <div class="fg"><label for="pekerjaan_ayah">Pekerjaan Ayah</label><input type="text" id="pekerjaan_ayah" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $isEdit ? $siswa->pekerjaan_ayah : '') }}"></div>
    <div class="fg"><label for="nama_ibu">Nama Ibu</label><input type="text" id="nama_ibu" name="nama_ibu" value="{{ old('nama_ibu', $isEdit ? $siswa->nama_ibu : '') }}"></div>
    <div class="fg"><label for="pekerjaan_ibu">Pekerjaan Ibu</label><input type="text" id="pekerjaan_ibu" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $isEdit ? $siswa->pekerjaan_ibu : '') }}"></div>
    <div class="fg"><label for="no_hp_ortu">No HP Orang Tua</label><input type="text" id="no_hp_ortu" name="no_hp_ortu" value="{{ old('no_hp_ortu', $isEdit ? $siswa->no_hp_ortu : '') }}"></div>
    <div class="fg form-full"><label for="alamat_ortu">Alamat Orang Tua</label><textarea id="alamat_ortu" name="alamat_ortu">{{ old('alamat_ortu', $isEdit ? $siswa->alamat_ortu : '') }}</textarea></div>

    @if(!$isEdit)
    <div class="section-title">🔑 Akun Login (Opsional)</div>
    <div class="fg">
        <label><input type="checkbox" name="buat_akun" value="1" {{ old('buat_akun') ? 'checked' : '' }} onchange="document.getElementById('akun-fields').style.display=this.checked?'grid':'none'" style="width:auto;margin-right:0.5rem">Buat akun login untuk siswa</label>
    </div>
    <div id="akun-fields" class="form-grid form-full" style="display:{{ old('buat_akun') ? 'grid' : 'none' }}">
        <div class="fg"><label for="email">Email</label><input type="email" id="email" name="email" value="{{ old('email') }}"></div>
        <div class="fg"><label for="password">Password</label><input type="password" id="password" name="password"></div>
    </div>
    @endif
</div>

<div class="btn-bar">
    <button type="submit" class="btn-save">💾 {{ $isEdit ? 'Perbarui' : 'Simpan' }}</button>
    <a href="{{ route('admin.siswa.index') }}" class="btn-cancel">← Kembali</a>
</div>
