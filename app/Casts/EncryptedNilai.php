<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

/**
 * EncryptedNilai Cast
 *
 * Mengenkripsi nilai (integer 0-100) menggunakan Laravel Crypt (AES-256-CBC).
 * Digunakan pada kolom *_enc di tabel nilai_siswa untuk keamanan data rapot.
 *
 * Cara kerja:
 *   - SET  : integer → encrypt → simpan sebagai TEXT di DB
 *   - GET  : TEXT ciphertext dari DB → decrypt → kembalikan sebagai float|null
 *
 * Contoh penggunaan di Model:
 *   protected $casts = [
 *       'nilai_sh_enc' => EncryptedNilai::class,
 *   ];
 */
class EncryptedNilai implements CastsAttributes
{
    /**
     * Decrypt nilai dari database untuk ditampilkan di aplikasi.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): float|null
    {
        if (is_null($value) || $value === '') {
            return null;
        }

        try {
            $decrypted = Crypt::decryptString($value);
            return is_numeric($decrypted) ? (float) $decrypted : null;
        } catch (DecryptException) {
            // Jika gagal decrypt (mis: data lama sebelum enkripsi), kembalikan null
            return null;
        }
    }

    /**
     * Encrypt nilai sebelum disimpan ke database.
     *
     * @param  array<string, mixed>  $attributes
     * @return array<string, mixed>
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): array
    {
        if (is_null($value)) {
            return [$key => null];
        }

        // Validasi range 0-100
        $nilai = (float) $value;
        if ($nilai < 0 || $nilai > 100) {
            throw new \InvalidArgumentException("Nilai harus berada dalam rentang 0-100, diberikan: {$nilai}");
        }

        return [$key => Crypt::encryptString((string) $nilai)];
    }
}
