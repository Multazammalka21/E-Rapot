<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $mapel = MataPelajaran::when($request->search, fn($q,$s) =>
                $q->where('nama_mapel','like',"%$s%")->orWhere('kode_mapel','like',"%$s%")
            )
            ->when($request->kelompok, fn($q,$k) => $q->where('kelompok',$k))
            ->orderBy('kelompok')->orderBy('nama_mapel')
            ->paginate(15)->withQueryString();

        return view('admin.mapel.index', compact('mapel'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_mapel'           => 'required|string|max:10|unique:mata_pelajaran,kode_mapel',
            'nama_mapel'           => 'required|string|max:80',
            'kelompok'             => 'required|in:Umum,Pilihan,Muatan Lokal',
            'kktp'                 => 'required|integer|min:0|max:100',
            'bobot_sumatif_harian' => 'required|integer|min:0|max:100',
            'bobot_sumatif_tengah' => 'required|integer|min:0|max:100',
            'bobot_sumatif_akhir'  => 'required|integer|min:0|max:100',
            'is_active'            => 'nullable|boolean',
        ]);

        // Validasi total bobot = 100
        $totalBobot = $data['bobot_sumatif_harian'] + $data['bobot_sumatif_tengah'] + $data['bobot_sumatif_akhir'];
        if ($totalBobot !== 100) {
            return back()->withInput()->withErrors(['bobot_sumatif_harian' => "Total bobot harus 100 (saat ini: {$totalBobot})."]);
        }

        try {
            $data['is_active'] = $request->boolean('is_active', true);
            MataPelajaran::create($data);

            return redirect()->route('admin.mapel.index')
                ->with('success', "Mata pelajaran {$data['nama_mapel']} berhasil ditambahkan.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', "Gagal menambahkan mata pelajaran: " . $e->getMessage());
        }
    }

    public function edit(MataPelajaran $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, MataPelajaran $mapel)
    {
        $data = $request->validate([
            'kode_mapel'           => ['required','string','max:10', Rule::unique('mata_pelajaran','kode_mapel')->ignore($mapel->id)],
            'nama_mapel'           => 'required|string|max:80',
            'kelompok'             => 'required|in:Umum,Pilihan,Muatan Lokal',
            'kktp'                 => 'required|integer|min:0|max:100',
            'bobot_sumatif_harian' => 'required|integer|min:0|max:100',
            'bobot_sumatif_tengah' => 'required|integer|min:0|max:100',
            'bobot_sumatif_akhir'  => 'required|integer|min:0|max:100',
            'is_active'            => 'nullable|boolean',
        ]);

        $totalBobot = $data['bobot_sumatif_harian'] + $data['bobot_sumatif_tengah'] + $data['bobot_sumatif_akhir'];
        if ($totalBobot !== 100) {
            return back()->withInput()->withErrors(['bobot_sumatif_harian' => "Total bobot harus 100 (saat ini: {$totalBobot})."]);
        }

        try {
            $data['is_active'] = $request->boolean('is_active');
            $mapel->update($data);

            return redirect()->route('admin.mapel.index')
                ->with('success', "Mata pelajaran {$data['nama_mapel']} berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', "Gagal memperbarui mata pelajaran: " . $e->getMessage());
        }
    }

    public function destroy(MataPelajaran $mapel)
    {
        try {
            $nama = $mapel->nama_mapel;
            $mapel->delete();
            return redirect()->route('admin.mapel.index')
                ->with('success', "Mata pelajaran {$nama} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('admin.mapel.index')
                ->with('error', "Gagal menghapus mata pelajaran: " . $e->getMessage());
        }
    }
}
