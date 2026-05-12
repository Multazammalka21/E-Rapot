<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kelompok',
        'kktp',
        'bobot_sumatif_harian',
        'bobot_sumatif_tengah',
        'bobot_sumatif_akhir',
        'is_active',
    ];

    protected $casts = [
        'kktp'                  => 'integer',
        'bobot_sumatif_harian'  => 'integer',
        'bobot_sumatif_tengah'  => 'integer',
        'bobot_sumatif_akhir'   => 'integer',
        'is_active'             => 'boolean',
    ];

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Hitung nilai akhir berdasarkan bobot yang dikonfigurasi per mapel.
     * Formula: (sh × bobot_sh + sts × bobot_sts + sas × bobot_sas) / 100
     */
    public function hitungNilaiAkhir(float $sh, float $sts, float $sas): float
    {
        return round(
            ($sh * $this->bobot_sumatif_harian +
             $sts * $this->bobot_sumatif_tengah +
             $sas * $this->bobot_sumatif_akhir) / 100,
            2
        );
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function guru(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel')
                    ->withPivot(['kelas_id', 'tahun_ajaran_id'])
                    ->withTimestamps();
    }

    public function guruMapel(): HasMany
    {
        return $this->hasMany(GuruMapel::class);
    }

    public function nilaiSiswa(): HasMany
    {
        return $this->hasMany(NilaiSiswa::class);
    }
}
