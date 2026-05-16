<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\CatatanWaliKelas;
use App\Models\EkstrakurikulerSiswa;
use App\Models\NilaiSiswa;
use App\Models\RapotLog;
use App\Models\SiswaKelas;
use App\Models\TahunAjaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class RapotController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $siswa = $user->siswa;
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();

        $siswaKelas = SiswaKelas::with(['kelas.waliKelas'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->first();

        $nilai = NilaiSiswa::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->get()
            ->sortBy('mataPelajaran.nama_mapel');

        $absensi = Absensi::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();
        $ekskul  = EkstrakurikulerSiswa::with('ekstrakurikuler')->where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->get();
        $catatan = CatatanWaliKelas::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();

        $rataRata = $nilai->isNotEmpty() ? round($nilai->avg('nilai_akhir'), 2) : 0;
        $ranking  = $this->hitungRanking($siswa->id, $siswaKelas?->kelas_id, $ta->id);
        $isReady  = $nilai->where('status', 'final')->count() > 0;

        // Log akses
        if ($siswaKelas) {
            RapotLog::catat($siswa->id, $ta->id, $siswaKelas->kelas_id, 'view', $user);
        }

        return view('siswa.rapot', compact(
            'siswa', 'siswaKelas', 'ta', 'nilai', 'absensi',
            'ekskul', 'catatan', 'rataRata', 'ranking', 'isReady'
        ));
    }

    public function download()
    {
        $user  = Auth::user();
        $siswa = $user->siswa;
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();

        $siswaKelas = SiswaKelas::with(['kelas.waliKelas'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->firstOrFail();

        $nilai   = NilaiSiswa::with('mataPelajaran')->where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->get()->sortBy('mataPelajaran.nama_mapel');
        $absensi = Absensi::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();
        $ekskul  = EkstrakurikulerSiswa::with('ekstrakurikuler')->where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->get();
        $catatan = CatatanWaliKelas::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();
        $rataRata = $nilai->isNotEmpty() ? round($nilai->avg('nilai_akhir'), 2) : 0;
        $ranking  = $this->hitungRanking($siswa->id, $siswaKelas->kelas_id, $ta->id);

        // Log cetak
        RapotLog::catat($siswa->id, $ta->id, $siswaKelas->kelas_id, 'print', $user);

        $pdf = Pdf::loadView('rapot.template', compact('siswa', 'siswaKelas', 'ta', 'nilai', 'absensi', 'ekskul', 'catatan', 'rataRata', 'ranking'))
            ->setPaper('A4', 'portrait')
            ->setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);

        return $pdf->download('Rapot_' . str_replace(' ', '_', $siswa->nama_lengkap) . '.pdf');
    }

    public function preview()
    {
        $user  = Auth::user();
        $siswa = $user->siswa;
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();

        $siswaKelas = SiswaKelas::with(['kelas.waliKelas'])
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->firstOrFail();

        $nilai   = NilaiSiswa::with('mataPelajaran')->where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->get()->sortBy('mataPelajaran.nama_mapel');
        $absensi = Absensi::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();
        $ekskul  = EkstrakurikulerSiswa::with('ekstrakurikuler')->where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->get();
        $catatan = CatatanWaliKelas::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();
        $rataRata = $nilai->isNotEmpty() ? round($nilai->avg('nilai_akhir'), 2) : 0;
        $ranking  = $this->hitungRanking($siswa->id, $siswaKelas->kelas_id, $ta->id);

        RapotLog::catat($siswa->id, $ta->id, $siswaKelas->kelas_id, 'view', $user);

        return view('rapot.template', compact('siswa', 'siswaKelas', 'ta', 'nilai', 'absensi', 'ekskul', 'catatan', 'rataRata', 'ranking'));
    }

    private function hitungRanking(int $siswaId, ?int $kelasId, int $taId): array
    {
        if (!$kelasId) return ['urutan' => '-', 'dari' => 0];

        $rankings = NilaiSiswa::where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $taId)
            ->selectRaw('siswa_id, AVG(nilai_akhir) as rata_rata')
            ->groupBy('siswa_id')
            ->orderByDesc('rata_rata')
            ->pluck('rata_rata', 'siswa_id');

        $urutan = array_search($siswaId, array_keys($rankings->toArray())) + 1;
        return ['urutan' => $urutan, 'dari' => $rankings->count()];
    }
}
