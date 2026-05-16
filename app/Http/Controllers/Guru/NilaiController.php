<?php

namespace App\Http\Controllers\Guru;

use App\Casts\EncryptedNilai;
use App\Http\Controllers\Controller;
use App\Models\GuruMapel;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\NilaiSiswa;
use App\Models\SiswaKelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    /** Daftar kelas & mapel yang bisa diinput guru ini */
    public function index()
    {
        $guru = Auth::user()->guru;
        $ta   = TahunAjaran::where('is_active', true)->firstOrFail();

        // Semua assignment guru ini di semester aktif
        $assignments = GuruMapel::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->get();

        // Hitung progress input per assignment
        $progress = [];
        foreach ($assignments as $gm) {
            $totalSiswa  = SiswaKelas::where('kelas_id', $gm->kelas_id)
                ->where('tahun_ajaran_id', $ta->id)->count();

            $sudahInput  = NilaiSiswa::where('guru_id', $guru->id)
                ->where('kelas_id', $gm->kelas_id)
                ->where('mata_pelajaran_id', $gm->mata_pelajaran_id)
                ->where('tahun_ajaran_id', $ta->id)
                ->whereNotNull('nilai_akhir')
                ->count();

            $isFinal = NilaiSiswa::where('kelas_id', $gm->kelas_id)
                ->where('mata_pelajaran_id', $gm->mata_pelajaran_id)
                ->where('tahun_ajaran_id', $ta->id)
                ->where('status', 'final')
                ->exists();

            $progress[$gm->id] = [
                'total'      => $totalSiswa,
                'sudah'      => $sudahInput,
                'pct'        => $totalSiswa ? round($sudahInput / $totalSiswa * 100) : 0,
                'is_final'   => $isFinal,
            ];
        }

        // Kelas wali (untuk finalisasi)
        $kelasWali = Kelas::with(['siswa', 'tahunAjaran'])
            ->where('wali_kelas_id', $guru->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->first();

        $isWaliKelasFinal = false;
        $waliAssignments = collect();
        $waliProgress = [];

        if ($kelasWali) {
            $isWaliKelasFinal = NilaiSiswa::where('kelas_id', $kelasWali->id)
                ->where('tahun_ajaran_id', $ta->id)
                ->where('status', 'final')
                ->exists();

            $waliAssignments = GuruMapel::with(['kelas', 'mataPelajaran', 'guru'])
                ->where('kelas_id', $kelasWali->id)
                ->where('tahun_ajaran_id', $ta->id)
                ->get();
                
            foreach ($waliAssignments as $gm) {
                $totalSiswa  = SiswaKelas::where('kelas_id', $gm->kelas_id)
                    ->where('tahun_ajaran_id', $ta->id)->count();

                $sudahInput  = NilaiSiswa::where('kelas_id', $gm->kelas_id)
                    ->where('mata_pelajaran_id', $gm->mata_pelajaran_id)
                    ->where('tahun_ajaran_id', $ta->id)
                    ->whereNotNull('nilai_akhir')
                    ->count();

                $isFinal = NilaiSiswa::where('kelas_id', $gm->kelas_id)
                    ->where('mata_pelajaran_id', $gm->mata_pelajaran_id)
                    ->where('tahun_ajaran_id', $ta->id)
                    ->where('status', 'final')
                    ->exists();

                $waliProgress[$gm->id] = [
                    'total'      => $totalSiswa,
                    'sudah'      => $sudahInput,
                    'pct'        => $totalSiswa ? round($sudahInput / $totalSiswa * 100) : 0,
                    'is_final'   => $isFinal,
                ];
            }
        }

        return view('guru.nilai.index', compact('assignments', 'progress', 'ta', 'kelasWali', 'guru', 'isWaliKelasFinal', 'waliAssignments', 'waliProgress'));
    }

    /** Form input nilai semua siswa di kelas tertentu untuk satu mapel */
    public function input(int $kelasId, int $mapelId)
    {
        $guru  = Auth::user()->guru;
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();
        $kelas = Kelas::findOrFail($kelasId);
        $mapel = MataPelajaran::findOrFail($mapelId);

        // Pastikan guru berhak mengajar mapel ini di kelas ini
        $isAuthorized = GuruMapel::where('guru_id', $guru->id)
            ->where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mapelId)
            ->where('tahun_ajaran_id', $ta->id)
            ->exists();

        if (!$isAuthorized) {
            abort(403, 'Anda tidak berwenang mengisi nilai mapel ini.');
        }

        // Ambil semua siswa di kelas ini beserta nilai yang sudah ada
        $siswaList = SiswaKelas::with(['siswa'])
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $ta->id)
            ->orderBy('nomor_urut')
            ->get();

        // Nilai yang sudah diinput (indexed by siswa_id)
        $nilaiExisting = NilaiSiswa::where('kelas_id', $kelasId)
            ->where('mata_pelajaran_id', $mapelId)
            ->where('tahun_ajaran_id', $ta->id)
            ->get()
            ->keyBy('siswa_id');

        $isFinal = $nilaiExisting->where('status', 'final')->isNotEmpty();

        return view('guru.nilai.input', compact(
            'kelas', 'mapel', 'ta', 'siswaList', 'nilaiExisting', 'isFinal', 'guru'
        ));
    }

    /** Simpan nilai (plaintext + encrypted) untuk semua siswa */
    public function store(Request $request, int $kelasId, int $mapelId)
    {
        $guru  = Auth::user()->guru;
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();
        $mapel = MataPelajaran::findOrFail($mapelId);

        $request->validate([
            'nilai'                => 'required|array',
            'nilai.*.siswa_id'     => 'required|exists:siswa,id',
            'nilai.*.nilai_sh'     => 'required|integer|min:0|max:100',
            'nilai.*.nilai_sts'    => 'required|integer|min:0|max:100',
            'nilai.*.nilai_sas'    => 'required|integer|min:0|max:100',
            'nilai.*.catatan_guru' => 'nullable|string|max:500',
        ]);

        $now   = now();
        $saved = 0;

        DB::transaction(function () use ($request, $mapel, $kelasId, $mapelId, $ta, $guru, $now, &$saved) {
            foreach ($request->input('nilai') as $row) {
                $sh  = (int) $row['nilai_sh'];
                $sts = (int) $row['nilai_sts'];
                $sas = (int) $row['nilai_sas'];

                // Hitung nilai akhir berdasarkan bobot mapel
                $nilaiAkhir = round(
                    ($sh * $mapel->bobot_sumatif_harian +
                     $sts * $mapel->bobot_sumatif_tengah +
                     $sas * $mapel->bobot_sumatif_akhir) / 100, 2
                );

                $predikat = match(true) {
                    $nilaiAkhir >= 90 => 'A',
                    $nilaiAkhir >= 80 => 'B',
                    $nilaiAkhir >= 70 => 'C',
                    default           => 'D',
                };

                NilaiSiswa::updateOrCreate(
                    [
                        'siswa_id'          => $row['siswa_id'],
                        'mata_pelajaran_id' => $mapelId,
                        'kelas_id'          => $kelasId,
                        'tahun_ajaran_id'   => $ta->id,
                    ],
                    [
                        'guru_id'         => $guru->id,
                        // Plaintext
                        'nilai_sh'        => $sh,
                        'nilai_sts'       => $sts,
                        'nilai_sas'       => $sas,
                        'nilai_akhir'     => $nilaiAkhir,
                        // Encrypted
                        'nilai_sh_enc'    => Crypt::encryptString((string)$sh),
                        'nilai_sts_enc'   => Crypt::encryptString((string)$sts),
                        'nilai_sas_enc'   => Crypt::encryptString((string)$sas),
                        'nilai_akhir_enc' => Crypt::encryptString((string)$nilaiAkhir),
                        // Metadata
                        'predikat'        => $predikat,
                        'is_lulus'        => $nilaiAkhir >= $mapel->kktp,
                        'catatan_guru'    => $row['catatan_guru'] ?? null,
                        'status'          => 'draft',
                        'updated_at'      => $now,
                    ]
                );
                $saved++;
            }
        });

        return redirect()
            ->route('guru.nilai.input', [$kelasId, $mapelId])
            ->with('success', "✅ {$saved} nilai berhasil disimpan. Status: Draft (belum difinalisasi).");
    }

    /** Finalisasi semua nilai di satu kelas oleh wali kelas */
    public function finalize(Request $request, int $kelasId)
    {
        $guru  = Auth::user()->guru;
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();
        $kelas = Kelas::findOrFail($kelasId);

        // Pastikan yang finalisasi adalah wali kelas
        if ($kelas->wali_kelas_id !== $guru->id) {
            abort(403, 'Hanya wali kelas yang dapat memfinalisasi nilai.');
        }

        $updated = NilaiSiswa::where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $ta->id)
            ->where('status', 'draft')
            ->update([
                'status'       => 'final',
                'finalized_by' => Auth::id(),
                'finalized_at' => now(),
            ]);

        return redirect()
            ->route('guru.nilai.index')
            ->with('success', "🔒 {$updated} nilai Kelas {$kelas->nama_kelas} berhasil difinalisasi!");
    }

    /** Batal Finalisasi semua nilai di satu kelas oleh wali kelas */
    public function unfinalize(Request $request, int $kelasId)
    {
        $guru  = Auth::user()->guru;
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();
        $kelas = Kelas::findOrFail($kelasId);

        // Pastikan yang batal finalisasi adalah wali kelas
        if ($kelas->wali_kelas_id !== $guru->id) {
            abort(403, 'Hanya wali kelas yang dapat membatalkan finalisasi nilai.');
        }

        $updated = NilaiSiswa::where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $ta->id)
            ->where('status', 'final')
            ->update([
                'status'       => 'draft',
                'finalized_by' => null,
                'finalized_at' => null,
            ]);

        return redirect()
            ->route('guru.nilai.index')
            ->with('success', "🔓 {$updated} nilai Kelas {$kelas->nama_kelas} berhasil dibatalkan finalisasinya (Draft).");
    }
}
