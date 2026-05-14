<?php

namespace App\Http\Controllers\Guru;

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
use OpenSpout\Reader\XLSX\Reader;
use OpenSpout\Writer\XLSX\Writer;
use OpenSpout\Common\Entity\Row;

class ImportNilaiController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;
        $ta   = TahunAjaran::where('is_active', true)->firstOrFail();

        $assignments = GuruMapel::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->get();

        return view('guru.import.index', compact('assignments', 'ta'));
    }

    /** Download template Excel kosong */
    public function template(int $kelasId, int $mapelId)
    {
        $ta     = TahunAjaran::where('is_active', true)->firstOrFail();
        $kelas  = Kelas::findOrFail($kelasId);
        $mapel  = MataPelajaran::findOrFail($mapelId);

        $siswaList = SiswaKelas::with('siswa')
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $ta->id)
            ->orderBy('nomor_urut')
            ->get();

        $tmpFile = tempnam(sys_get_temp_dir(), 'rapot_') . '.xlsx';
        $writer  = new Writer();
        $writer->openToFile($tmpFile);

        // Header row
        $writer->addRow(Row::fromValues([
            'No', 'NIS', 'Nama Lengkap',
            'Nilai SH (0-100)', 'Nilai STS (0-100)', 'Nilai SAS (0-100)',
            'Catatan Guru (Opsional)',
        ]));

        // Data rows (siswa)
        foreach ($siswaList as $sk) {
            $writer->addRow(Row::fromValues([
                $sk->nomor_urut,
                $sk->siswa?->nis,
                $sk->siswa?->nama_lengkap,
                '',  // SH
                '',  // STS
                '',  // SAS
                '',  // Catatan
            ]));
        }
        $writer->close();

        $safeTaName = str_replace(['/', '\\'], '-', $ta->nama);
        $filename = "Template_Nilai_{$kelas->nama_kelas}_{$mapel->kode_mapel}_{$safeTaName}.xlsx";
        return response()->download($tmpFile, $filename)->deleteFileAfterSend(true);
    }

    /** Proses upload Excel */
    public function store(Request $request, int $kelasId, int $mapelId)
    {
        $request->validate([
            'file_excel' => 'required|file|mimes:xlsx,xls|max:5120',
        ]);

        $guru  = Auth::user()->guru;
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();
        $mapel = MataPelajaran::findOrFail($mapelId);

        // Verifikasi authorization
        abort_unless(
            GuruMapel::where('guru_id', $guru->id)->where('kelas_id', $kelasId)->where('mata_pelajaran_id', $mapelId)->where('tahun_ajaran_id', $ta->id)->exists(),
            403, 'Anda tidak berwenang mengisi nilai mapel ini.'
        );

        // Ambil siswa (NIS → siswa_id map)
        $siswaMap = SiswaKelas::with('siswa')
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $ta->id)
            ->get()
            ->keyBy(fn($sk) => $sk->siswa?->nis);

        $file   = $request->file('file_excel');
        $path   = $file->storeAs('imports', 'import_' . time() . '.xlsx', 'local');
        $reader = new Reader();
        $reader->open(storage_path('app/private/' . $path));

        $saved  = 0;
        $errors = [];
        $now    = now();

        DB::transaction(function () use ($reader, $siswaMap, $mapel, $kelasId, $mapelId, $ta, $guru, $now, &$saved, &$errors) {
            foreach ($reader->getSheetIterator() as $sheet) {
                $rowIndex = 0;
                foreach ($sheet->getRowIterator() as $row) {
                    $rowIndex++;
                    if ($rowIndex === 1) continue; // skip header

                    $cells = $row->getCells();
                    $nis   = trim((string)($cells[1]?->getValue() ?? ''));
                    $sh    = (int)($cells[3]?->getValue() ?? 0);
                    $sts   = (int)($cells[4]?->getValue() ?? 0);
                    $sas   = (int)($cells[5]?->getValue() ?? 0);
                    $catatan = trim((string)($cells[6]?->getValue() ?? ''));

                    if (empty($nis)) continue;

                    $sk = $siswaMap[$nis] ?? null;
                    if (!$sk) {
                        $errors[] = "Baris {$rowIndex}: NIS '{$nis}' tidak ditemukan di kelas ini.";
                        continue;
                    }

                    if ($sh < 0 || $sh > 100 || $sts < 0 || $sts > 100 || $sas < 0 || $sas > 100) {
                        $errors[] = "Baris {$rowIndex}: Nilai harus antara 0-100.";
                        continue;
                    }

                    $nilaiAkhir = round(($sh * $mapel->bobot_sumatif_harian + $sts * $mapel->bobot_sumatif_tengah + $sas * $mapel->bobot_sumatif_akhir) / 100, 2);
                    $predikat   = $nilaiAkhir >= 90 ? 'A' : ($nilaiAkhir >= 80 ? 'B' : ($nilaiAkhir >= 70 ? 'C' : 'D'));

                    NilaiSiswa::updateOrCreate(
                        ['siswa_id' => $sk->siswa_id, 'mata_pelajaran_id' => $mapelId, 'kelas_id' => $kelasId, 'tahun_ajaran_id' => $ta->id],
                        [
                            'guru_id'         => $guru->id,
                            'nilai_sh'        => $sh,
                            'nilai_sts'       => $sts,
                            'nilai_sas'       => $sas,
                            'nilai_akhir'     => $nilaiAkhir,
                            'nilai_sh_enc'    => Crypt::encryptString((string)$sh),
                            'nilai_sts_enc'   => Crypt::encryptString((string)$sts),
                            'nilai_sas_enc'   => Crypt::encryptString((string)$sas),
                            'nilai_akhir_enc' => Crypt::encryptString((string)$nilaiAkhir),
                            'predikat'        => $predikat,
                            'is_lulus'        => $nilaiAkhir >= $mapel->kktp,
                            'catatan_guru'    => $catatan ?: null,
                            'status'          => 'draft',
                            'updated_at'      => $now,
                        ]
                    );
                    $saved++;
                }
                break; // Hanya proses sheet pertama
            }
        });

        $reader->close();

        $msg = "✅ {$saved} nilai berhasil diimport.";
        if (!empty($errors)) {
            $msg .= ' ⚠️ ' . count($errors) . ' baris dilewati: ' . implode('; ', array_slice($errors, 0, 3));
        }

        return back()->with('success', $msg);
    }
}
