<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $siswa   = Auth::user()->siswa;
        $ta      = TahunAjaran::where('is_active', true)->firstOrFail();
        $absensi = Absensi::where('siswa_id', $siswa->id)->where('tahun_ajaran_id', $ta->id)->first();
        return view('siswa.absensi', compact('siswa', 'ta', 'absensi'));
    }
}
