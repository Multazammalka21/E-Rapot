<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'tahun_ajaran_id',
        'wali_kelas_id',
        'nama_kelas',
        'tingkat',
        'kapasitas',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /** Label lengkap: "Kelas 7A - 2025/2026 Ganjil" */
    public function getLabelLengkapAttribute(): string
    {
        return "Kelas {$this->nama_kelas} - {$this->tahunAjaran->label}";
    }

    /** Jumlah siswa terdaftar */
    public function getJumlahSiswaAttribute(): int
    {
        return $this->siswa()->count();
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'siswa_kelas')
                    ->withPivot(['tahun_ajaran_id', 'nomor_urut'])
                    ->withTimestamps();
    }

    public function siswaKelas(): HasMany
    {
        return $this->hasMany(SiswaKelas::class);
    }

    public function guruMapel(): HasMany
    {
        return $this->hasMany(GuruMapel::class);
    }

    public function nilaiSiswa(): HasMany
    {
        return $this->hasMany(NilaiSiswa::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    public function catatanWaliKelas(): HasMany
    {
        return $this->hasMany(CatatanWaliKelas::class);
    }
}
