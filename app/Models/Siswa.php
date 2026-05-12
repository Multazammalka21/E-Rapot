<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id', 'nisn', 'nis', 'nama_lengkap', 'jenis_kelamin',
        'tempat_lahir', 'tanggal_lahir', 'agama', 'alamat', 'no_hp', 'foto',
        'nama_ayah', 'pekerjaan_ayah', 'nama_ibu', 'pekerjaan_ibu',
        'nama_wali', 'no_hp_ortu', 'alamat_ortu',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /** Kelas aktif siswa saat ini */
    public function getKelasAktifAttribute(): ?Kelas
    {
        $ta = TahunAjaran::where('is_active', true)->first();
        if (!$ta) return null;

        return $this->kelas()->wherePivot('tahun_ajaran_id', $ta->id)->first();
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Semua kelas yang pernah diikuti */
    public function kelas(): BelongsToMany
    {
        return $this->belongsToMany(Kelas::class, 'siswa_kelas')
                    ->withPivot(['tahun_ajaran_id', 'nomor_urut'])
                    ->withTimestamps();
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

    public function ekstrakurikulerSiswa(): HasMany
    {
        return $this->hasMany(EkstrakurikulerSiswa::class);
    }

    public function catatanWaliKelas(): HasMany
    {
        return $this->hasMany(CatatanWaliKelas::class);
    }

    public function rapotLog(): HasMany
    {
        return $this->hasMany(RapotLog::class);
    }
}
