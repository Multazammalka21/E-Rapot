<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $ta = TahunAjaran::where('is_active', true)->first();
        $dataTa = TahunAjaran::orderBy('tanggal_mulai', 'desc')->paginate(10);
        return view('admin.tahun_ajaran.index', compact('ta', 'dataTa'));
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
        return view('admin.tahun_ajaran.edit', compact('tahun_ajaran'));
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
        $tahun_ajaran->delete();
        return redirect()->route('admin.tahun-ajaran.index')
            ->with('success', 'Tahun Ajaran berhasil dihapus.');
    }
}
