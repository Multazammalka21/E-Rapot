<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\CatatanWaliKelas;
use App\Models\EkstrakurikulerSiswa;
use App\Models\Kelas;
use App\Models\NilaiSiswa;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\TahunAjaran;
use App\Models\RapotLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RapotController extends Controller
{
    /** Halaman pilih kelas & siswa untuk cetak rapot (Semua Kelas) */
    public function index(Request $request)
    {
        $ta = TahunAjaran::where('is_active', true)->firstOrFail();

        // Admin bisa melihat semua kelas
        $kelasList = Kelas::with(['siswa', 'tahunAjaran'])
            ->where('tahun_ajaran_id', $ta->id)
            ->orderBy('nama_kelas')
            ->get();

        $selectedKelas = $request->kelas_id
            ? Kelas::with(['siswa' => fn($q) => $q->orderBy('nama_lengkap')])->find($request->kelas_id)
            : $kelasList->first();

        return view('admin.rapot.index', compact('kelasList', 'selectedKelas', 'ta'));
    }

    /** Generate PDF rapot satu siswa */
    public function cetak(Siswa $siswa, Request $request)
    {
        $ta = TahunAjaran::where('is_active', true)->firstOrFail();

        $siswaKelas = SiswaKelas::with(['kelas.waliKelas', 'kelas.tahunAjaran'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->firstOrFail();

        $nilai = NilaiSiswa::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->get()
            ->sortBy('mataPelajaran.nama_mapel');

        $absensi  = Absensi::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();
        $ekskul   = EkstrakurikulerSiswa::with('ekstrakurikuler')->where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->get();
        $catatan  = CatatanWaliKelas::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();

        $rataRata = $nilai->isNotEmpty() ? round($nilai->avg('nilai_akhir'), 2) : 0;
        $ranking  = $this->hitungRanking($siswa->id, $siswaKelas->kelas_id, $ta->id);

        $data = compact('siswa', 'siswaKelas', 'ta', 'nilai', 'absensi', 'ekskul', 'catatan', 'rataRata', 'ranking');

        // Logging Action
        RapotLog::catat($siswa->id, $ta->id, $siswaKelas->kelas_id, 'Cetak PDF (Admin)', Auth::user());

        $pdf = Pdf::loadView('rapot.template', $data)
            ->setPaper('A4', 'portrait')
            ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $safeTaName = str_replace(['/', '\\'], '-', $ta->nama);
        $safeSiswaName = str_replace(['/', '\\', ' '], '_', $siswa->nama_lengkap);
        $filename = 'Rapot_' . $safeSiswaName . '_' . $safeTaName . '.pdf';
        return $pdf->download($filename);
    }

    /** Halaman preview rapot (HTML, tanpa PDF) */
    public function preview(Siswa $siswa)
    {
        $ta = TahunAjaran::where('is_active', true)->firstOrFail();

        $siswaKelas = SiswaKelas::with(['kelas.waliKelas'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->firstOrFail();

        $nilai    = NilaiSiswa::with('mataPelajaran')->where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->get()->sortBy('mataPelajaran.nama_mapel');
        $absensi  = Absensi::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();
        $ekskul   = EkstrakurikulerSiswa::with('ekstrakurikuler')->where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->get();
        $catatan  = CatatanWaliKelas::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();
        $rataRata = $nilai->isNotEmpty() ? round($nilai->avg('nilai_akhir'), 2) : 0;
        $ranking  = $this->hitungRanking($siswa->id, $siswaKelas->kelas_id, $ta->id);

        // Logging Action
        RapotLog::catat($siswa->id, $ta->id, $siswaKelas->kelas_id, 'Preview (Admin)', Auth::user());

        return view('rapot.template', compact('siswa', 'siswaKelas', 'ta', 'nilai', 'absensi', 'ekskul', 'catatan', 'rataRata', 'ranking'));
    }

    private function hitungRanking(int $siswaId, int $kelasId, int $taId): array
    {
        $rankings = NilaiSiswa::where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $taId)
            ->selectRaw('siswa_id, AVG(nilai_akhir) as rata_rata')
            ->groupBy('siswa_id')
            ->orderByDesc('rata_rata')
            ->pluck('rata_rata', 'siswa_id');

        $urutan   = array_search($siswaId, array_keys($rankings->toArray())) + 1;
        return ['urutan' => $urutan, 'dari' => $rankings->count()];
    }
}
