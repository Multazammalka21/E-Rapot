<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'nama',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
    ];

    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_selesai'  => 'date',
        'is_active'        => 'boolean',
    ];

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Label lengkap: "2025/2026 - Ganjil"
     */
    public function getLabelAttribute(): string
    {
        return "{$this->nama} - " . ucfirst($this->semester);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class);
    }

    public function siswaKelas(): HasMany
    {
        return $this->hasMany(SiswaKelas::class);
    }

    public function nilaiSiswa(): HasMany
    {
        return $this->hasMany(NilaiSiswa::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }
}
