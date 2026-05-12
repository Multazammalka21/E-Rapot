<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Guru extends Model
{
    use SoftDeletes;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'foto',
        'gelar_depan',
        'gelar_belakang',
        'bidang_studi',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Nama lengkap dengan gelar: "Dr. Budi Santoso, S.Pd., M.Pd."
     */
    public function getNamaGelarAttribute(): string
    {
        $depan = $this->gelar_depan ? $this->gelar_depan . ' ' : '';
        $belakang = $this->gelar_belakang ? ', ' . $this->gelar_belakang : '';
        return "{$depan}{$this->nama_lengkap}{$belakang}";
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Kelas yang diwali-kelasi */
    public function kelasWali(): HasMany
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    /** Mapel yang diajar (via pivot guru_mapel) */
    public function mataPelajaran(): BelongsToMany
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mapel')
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

    public function ekstrakurikuler(): HasMany
    {
        return $this->hasMany(Ekstrakurikuler::class, 'pembina_id');
    }
}
