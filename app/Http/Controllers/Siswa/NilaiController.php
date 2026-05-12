<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\NilaiSiswa;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswa;
        $ta    = TahunAjaran::where('is_active', true)->firstOrFail();

        $nilai = NilaiSiswa::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', $ta->id)
            ->get()
            ->sortBy('mataPelajaran.nama_mapel');

        $statsNilai = [
            'rata_rata'   => round($nilai->avg('nilai_akhir'), 1),
            'tertinggi'   => $nilai->max('nilai_akhir'),
            'terendah'    => $nilai->min('nilai_akhir'),
            'lulus'       => $nilai->where('is_lulus', true)->count(),
            'tidak_lulus' => $nilai->where('is_lulus', false)->count(),
        ];

        $distribusiPredikat = $nilai->groupBy('predikat')->map->count();

        return view('siswa.nilai', compact('siswa', 'ta', 'nilai', 'statsNilai', 'distribusiPredikat'));
    }
}
