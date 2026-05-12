<?php

namespace Database\Seeders;

use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;

/**
 * Mata Pelajaran Kurikulum Merdeka Fase D — SMP Kelas 7-9
 * Referensi: Permendikbudristek No. 12 Tahun 2024
 */
class MataPelajaranSeeder extends Seeder
{
    public function run(): void
    {
        $mataPelajaran = [
            // ── Kelompok Umum ──────────────────────────────────────────────────
            [
                'kode_mapel'            => 'PAI',
                'nama_mapel'            => 'Pendidikan Agama Islam dan Budi Pekerti',
                'kelompok'              => 'Umum',
                'kktp'                  => 75,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            [
                'kode_mapel'            => 'PPKn',
                'nama_mapel'            => 'Pendidikan Pancasila',
                'kelompok'              => 'Umum',
                'kktp'                  => 75,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            [
                'kode_mapel'            => 'BIN',
                'nama_mapel'            => 'Bahasa Indonesia',
                'kelompok'              => 'Umum',
                'kktp'                  => 75,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            [
                'kode_mapel'            => 'MTK',
                'nama_mapel'            => 'Matematika',
                'kelompok'              => 'Umum',
                'kktp'                  => 70,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            [
                'kode_mapel'            => 'IPA',
                'nama_mapel'            => 'Ilmu Pengetahuan Alam',
                'kelompok'              => 'Umum',
                'kktp'                  => 70,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            [
                'kode_mapel'            => 'IPS',
                'nama_mapel'            => 'Ilmu Pengetahuan Sosial',
                'kelompok'              => 'Umum',
                'kktp'                  => 70,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            [
                'kode_mapel'            => 'BING',
                'nama_mapel'            => 'Bahasa Inggris',
                'kelompok'              => 'Umum',
                'kktp'                  => 70,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            [
                'kode_mapel'            => 'INFO',
                'nama_mapel'            => 'Informatika',
                'kelompok'              => 'Umum',
                'kktp'                  => 70,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            [
                'kode_mapel'            => 'PJOK',
                'nama_mapel'            => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                'kelompok'              => 'Umum',
                'kktp'                  => 75,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            // ── Kelompok Pilihan (Seni & Prakarya) ───────────────────────────
            [
                'kode_mapel'            => 'SENI',
                'nama_mapel'            => 'Seni Budaya',
                'kelompok'              => 'Pilihan',
                'kktp'                  => 75,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            [
                'kode_mapel'            => 'PKR',
                'nama_mapel'            => 'Prakarya',
                'kelompok'              => 'Pilihan',
                'kktp'                  => 75,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
            // ── Muatan Lokal (Jawa Timur) ─────────────────────────────────────
            [
                'kode_mapel'            => 'BJAWA',
                'nama_mapel'            => 'Bahasa Jawa',
                'kelompok'              => 'Muatan Lokal',
                'kktp'                  => 70,
                'bobot_sumatif_harian'  => 60,
                'bobot_sumatif_tengah'  => 20,
                'bobot_sumatif_akhir'   => 20,
            ],
        ];

        foreach ($mataPelajaran as $item) {
            MataPelajaran::updateOrCreate(
                ['kode_mapel' => $item['kode_mapel']],
                $item
            );
        }

        $this->command->info('✅ MataPelajaran seeder selesai (12 mapel Kurikulum Merdeka Fase D)');
    }
}
