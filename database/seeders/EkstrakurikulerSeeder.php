<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use App\Models\EkstrakurikulerSiswa;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EkstrakurikulerSeeder extends Seeder
{
    public function run(): void
    {
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();
        $guru  = Guru::pluck('id')->toArray();
        $now   = now();

        // ── Buat 6 ekstrakurikuler ────────────────────────────────────────────
        $ekskul = [
            ['nama' => 'Pramuka',       'deskripsi' => 'Kegiatan kepramukaan untuk pembentukan karakter.', 'pembina_id' => $guru[10] ?? $guru[0]],
            ['nama' => 'Basket',        'deskripsi' => 'Olahraga bola basket antar kelas.', 'pembina_id' => $guru[16] ?? $guru[0]],
            ['nama' => 'Futsal',        'deskripsi' => 'Olahraga futsal putra dan putri.', 'pembina_id' => $guru[17] ?? $guru[0]],
            ['nama' => 'PMR',           'deskripsi' => 'Palang Merah Remaja, kegiatan sosial kesehatan.', 'pembina_id' => $guru[14] ?? $guru[0]],
            ['nama' => 'Paduan Suara',  'deskripsi' => 'Paduan suara dan seni vokal.', 'pembina_id' => $guru[18] ?? $guru[0]],
            ['nama' => 'Robotika',      'deskripsi' => 'Desain dan pemrograman robot dasar.', 'pembina_id' => $guru[21] ?? $guru[0]],
        ];

        $ekskulIds = [];
        foreach ($ekskul as $e) {
            $record      = Ekstrakurikuler::updateOrCreate(['nama' => $e['nama']], array_merge($e, ['is_active' => true]));
            $ekskulIds[] = $record->id;
        }

        // ── Assign ~50% siswa ke 1-2 ekskul ──────────────────────────────────
        $siswaIds = Siswa::pluck('id')->toArray();
        $predikat = ['A', 'A', 'B', 'B', 'B', 'C', 'C', 'D'];
        $batch    = [];

        foreach ($siswaIds as $siswaId) {
            // Semua siswa diwajibkan ikut minimal 1 ekskul
            $jumlah = rand(1, 2);
            $picked = (array) array_rand(array_flip($ekskulIds), $jumlah);

            foreach ($picked as $ekskulId) {
                $batch[] = [
                    'siswa_id'           => $siswaId,
                    'ekstrakurikuler_id' => $ekskulId,
                    'tahun_ajaran_id'    => $ta->id,
                    'predikat'           => $predikat[array_rand($predikat)],
                    'keterangan'         => null,
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ];
            }
        }

        // Insert, abaikan duplikat (unique key)
        foreach (array_chunk($batch, 100) as $chunk) {
            DB::table('ekstrakurikuler_siswa')->insertOrIgnore($chunk);
        }

        $this->command->info('✅ EkstrakurikulerSeeder: 6 ekskul + ~' . count($batch) . ' partisipasi siswa berhasil dibuat.');
    }
}
