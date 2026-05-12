<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Urutan PENTING — ikuti dependensi Foreign Key.
     */
    public function run(): void
    {
        $this->call([
            // ── Fase 1: Master data (tanpa FK) ─────────────────────────────
            TahunAjaranSeeder::class,
            MataPelajaranSeeder::class,

            // ── Fase 2: Users & profil ──────────────────────────────────────
            AdminSeeder::class,      // 1 admin
            GuruSeeder::class,       // 25 guru + user accounts
            SiswaSeeder::class,      // 250 siswa + user accounts

            // ── Fase 3: Struktur kelas ─────────────────────────────────────
            KelasSeeder::class,      // 9 kelas (7A-9C)
            SiswaKelasSeeder::class, // distribusi siswa ke kelas
            GuruMapelSeeder::class,  // assignment guru mengajar mapel di kelas

            // ── Fase 4: Data akademik ──────────────────────────────────────
            NilaiSiswaSeeder::class,       // ~3.000 nilai (int + AES-256 encrypted)
            AbsensiSeeder::class,          // 250 rekap absensi
            EkstrakurikulerSeeder::class,  // 6 ekskul + partisipasi siswa
            CatatanWaliKelasSeeder::class, // 250 catatan wali kelas
        ]);

        $this->command->newLine();
        $this->command->info('🎉 Seluruh data dummy E-Rapot SMPN 1 Surabaya berhasil diisi!');
        $this->command->table(
            ['Tabel', 'Jumlah Data'],
            [
                ['tahun_ajaran',         '4 (aktif: 2025/2026 Genap)'],
                ['mata_pelajaran',       '12 (Kurikulum Merdeka Fase D)'],
                ['users',                '1 admin + 25 guru + 250 siswa = 276'],
                ['guru',                 '25'],
                ['siswa',                '250'],
                ['kelas',                '9 (7A-7C, 8A-8C, 9A-9C)'],
                ['siswa_kelas',          '250'],
                ['guru_mapel',           '108 (9 kelas × 12 mapel)'],
                ['nilai_siswa',          '~3.000 (int + AES-256 encrypted)'],
                ['absensi',              '250'],
                ['ekstrakurikuler',      '6'],
                ['ekstrakurikuler_siswa','~125-250'],
                ['catatan_wali_kelas',   '250'],
            ]
        );
    }
}
