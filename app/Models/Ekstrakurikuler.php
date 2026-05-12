<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ekstrakurikuler extends Model
{
    protected $table = 'ekstrakurikuler';

    protected $fillable = [
        'nama',
        'deskripsi',
        'pembina_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function pembina(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'pembina_id');
    }

    public function siswa(): HasMany
    {
        return $this->hasMany(EkstrakurikulerSiswa::class);
    }
}
