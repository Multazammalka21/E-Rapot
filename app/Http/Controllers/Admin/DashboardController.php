<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\NilaiSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $ta = TahunAjaran::where('is_active', true)->first();

        $stats = [
            'total_siswa'   => Siswa::count(),
            'total_guru'    => Guru::count(),
            'total_kelas'   => $ta ? Kelas::where('tahun_ajaran_id', $ta->id)->count() : 0,
            'total_nilai'   => NilaiSiswa::count(),
            'nilai_final'   => NilaiSiswa::where('status', 'final')->count(),
            'nilai_draft'   => NilaiSiswa::where('status', 'draft')->count(),
            'siswa_lulus'   => NilaiSiswa::where('is_lulus', true)->distinct('siswa_id')->count('siswa_id'),
        ];

        // Distribusi predikat untuk chart
        $distribusiPredikat = NilaiSiswa::selectRaw('predikat, count(*) as total')
            ->groupBy('predikat')
            ->orderBy('predikat')
            ->pluck('total', 'predikat');

        // Top 5 siswa berdasarkan rata-rata nilai akhir
        $topSiswa = NilaiSiswa::with('siswa')
            ->where('tahun_ajaran_id', $ta?->id)
            ->selectRaw('siswa_id, AVG(nilai_akhir) as rata_rata')
            ->groupBy('siswa_id')
            ->orderByDesc('rata_rata')
            ->limit(5)
            ->get();

        // Kelas dengan rata-rata tertinggi
        $topKelas = NilaiSiswa::with('kelas')
            ->where('tahun_ajaran_id', $ta?->id)
            ->selectRaw('kelas_id, AVG(nilai_akhir) as rata_rata')
            ->groupBy('kelas_id')
            ->orderByDesc('rata_rata')
            ->limit(5)
            ->get();

        // Aktivitas terbaru (user terbaru)
        $recentUsers = User::latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'ta', 'stats', 'distribusiPredikat', 'topSiswa', 'topKelas', 'recentUsers'
        ));
    }
}
