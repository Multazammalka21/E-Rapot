<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NilaiSiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $ta     = TahunAjaran::where('is_active', true)->first();
        $dataTa = TahunAjaran::orderBy('tanggal_mulai', 'desc')->paginate(10);

        // Hitung nilai draft per tahun ajaran untuk ditampilkan di tabel
        $draftPerTa = NilaiSiswa::where('status', 'draft')
            ->selectRaw('tahun_ajaran_id, COUNT(*) as total')
            ->groupBy('tahun_ajaran_id')
            ->pluck('total', 'tahun_ajaran_id');

        return view('admin.tahun_ajaran.index', compact('ta', 'dataTa', 'draftPerTa'));
    }

    public function create()
    {
        return view('admin.tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'            => 'required|string|max:50',
            'semester'        => 'required|in:ganjil,genap',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active'       => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', false);
        
        if ($data['is_active']) {
            // Nonaktifkan yang lain
            TahunAjaran::where('is_active', true)->update(['is_active' => false]);
        }

        TahunAjaran::create($data);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahun_ajaran)
    {
        // Hitung nilai draft untuk warning di UI
        $draftCount = $tahun_ajaran->is_active
            ? NilaiSiswa::where('tahun_ajaran_id', $tahun_ajaran->id)
                        ->where('status', 'draft')
                        ->count()
            : 0;

        return view('admin.tahun_ajaran.edit', compact('tahun_ajaran', 'draftCount'));
    }

    public function update(Request $request, TahunAjaran $tahun_ajaran)
    {
        $data = $request->validate([
            'nama'            => 'required|string|max:50',
            'semester'        => 'required|in:ganjil,genap',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active'       => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', false);

        // ─── Proteksi: Cegah nonaktifkan TA yang masih punya nilai draft ────
        if ($tahun_ajaran->is_active && !$data['is_active']) {
            $draftCount = NilaiSiswa::where('tahun_ajaran_id', $tahun_ajaran->id)
                ->where('status', 'draft')
                ->count();

            if ($draftCount > 0) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'is_active' => "Tidak dapat menonaktifkan tahun ajaran ini. "
                            . "Masih terdapat {$draftCount} nilai yang belum difinalisasi oleh guru. "
                            . "Harap finalisasi semua nilai terlebih dahulu.",
                    ]);
            }
        }
        // ────────────────────────────────────────────────────────────────────

        if ($data['is_active']) {
            // Nonaktifkan yang lain
            TahunAjaran::where('id', '!=', $tahun_ajaran->id)->update(['is_active' => false]);
        }

        $tahun_ajaran->update($data);

        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahun_ajaran)
    {
        // ─── Proteksi: Cegah hapus TA yang masih ada data nilai ─────────────
        $totalNilai = NilaiSiswa::where('tahun_ajaran_id', $tahun_ajaran->id)->count();
        if ($totalNilai > 0) {
            return redirect()->route('admin.tahun-ajaran.index')
                ->with('error', "Tidak dapat menghapus tahun ajaran '{$tahun_ajaran->nama}'. "
                    . "Masih terdapat {$totalNilai} data nilai siswa yang terikat.");
        }
        // ────────────────────────────────────────────────────────────────────

        $tahun_ajaran->delete();
        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}
