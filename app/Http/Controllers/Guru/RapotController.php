<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\CatatanWaliKelas;
use App\Models\EkstrakurikulerSiswa;
use App\Models\Kelas;
use App\Models\NilaiSiswa;
use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RapotController extends Controller
{
    /** Halaman pilih kelas & siswa untuk cetak rapot */
    public function index(Request $request)
    {
        $guru = Auth::user()->guru;
        $ta   = TahunAjaran::where('is_active', true)->firstOrFail();

                // Kelas yang diajar atau diwali-kelasi guru ini
        $kelasIds = $guru->guruMapel->pluck('kelas_id')
            ->merge($guru->kelasWali->pluck('id'))
            ->unique();

        $kelasList = Kelas::with(['siswa', 'tahunAjaran'])
            ->whereIn('id', $kelasIds)
            ->where('tahun_ajaran_id', $ta->id)
            ->orderBy('nama_kelas')
            ->get();

        $selectedKelas = $request->kelas_id
            ? Kelas::with(['siswa' => fn($q) => $q->orderBy('nama_lengkap')])->find($request->kelas_id)
            : $kelasList->first();

        return view('guru.rapot.index', compact('kelasList', 'selectedKelas', 'ta', 'guru'));
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

        $rataRata = round($nilai->avg('nilai_akhir'), 2);
        $ranking  = $this->hitungRanking($siswa->id, $siswaKelas->kelas_id, $ta->id);

        $data = compact('siswa', 'siswaKelas', 'ta', 'nilai', 'absensi', 'ekskul', 'catatan', 'rataRata', 'ranking');

        $pdf = Pdf::loadView('rapot.template', $data)
            ->setPaper('A4', 'portrait')
            ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'Rapot_' . str_replace(' ', '_', $siswa->nama_lengkap) . '_' . $ta->nama . '.pdf';
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
        $rataRata = round($nilai->avg('nilai_akhir'), 2);
        $ranking  = $this->hitungRanking($siswa->id, $siswaKelas->kelas_id, $ta->id);

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
