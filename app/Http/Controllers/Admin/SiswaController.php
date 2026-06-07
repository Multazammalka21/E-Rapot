<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\User;
use App\Models\TahunAjaran;
use App\Models\Ekstrakurikuler;
use App\Models\EkstrakurikulerSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('siswaKelas.kelas')
            ->when($request->search, fn($q, $s) =>
                $q->where('nama_lengkap', 'like', "%$s%")
                  ->orWhere('nis', 'like', "%$s%")
                  ->orWhere('nisn', 'like', "%$s%")
            )
            ->latest();

        $siswa = $query->paginate(20)->withQueryString();
        return view('admin.siswa.index', compact('siswa'));
    }

    public function create()
    {
        return view('admin.siswa.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nisn'           => 'required|string|size:10|unique:siswa,nisn',
            'nama_lengkap'   => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'nullable|string|max:60',
            'tanggal_lahir'  => 'nullable|date',
            'agama'          => 'nullable|string',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:15',
            'nama_ayah'      => 'nullable|string|max:80',
            'pekerjaan_ayah' => 'nullable|string|max:60',
            'nama_ibu'       => 'nullable|string|max:80',
            'pekerjaan_ibu'  => 'nullable|string|max:60',
            'no_hp_ortu'     => 'nullable|string|max:15',
            'alamat_ortu'    => 'nullable|string',
            // Akun opsional
            'buat_akun'      => 'nullable|boolean',
            'email'          => 'nullable|email|unique:users,email',
            'password'       => 'nullable|min:6',
        ]);

        $userId = null;
        $siswa = null;

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, $data, &$siswa, &$userId) {
                if ($request->boolean('buat_akun')) {
                    // Auto Generate Email
                    if (empty($data['email'])) {
                        $email = $data['nisn'] . '@smpn1sby.sch.id';
                        $counter = 1;
                        $baseEmail = $data['nisn'];
                        while (User::withTrashed()->where('email', $email)->exists()) {
                            $email = $baseEmail . '_' . $counter . '@smpn1sby.sch.id';
                            $counter++;
                        }
                        $data['email'] = $email;
                    }

                    // Auto Generate Password
                    if (empty($data['password'])) {
                        $data['password'] = 'Siswa@1234';
                    }

                    $user = User::create([
                        'name'      => $data['nama_lengkap'],
                        'email'     => $data['email'],
                        'password'  => Hash::make($data['password']),
                        'role'      => 'siswa',
                        'is_active' => true,
                    ]);
                    $userId = $user->id;
                }

                // Auto Generate NIS
                $lastSiswa = Siswa::withTrashed()->orderByRaw('CAST(nis AS UNSIGNED) DESC')->first();
                $nextNis = $lastSiswa && is_numeric($lastSiswa->nis) ? ((int)$lastSiswa->nis + 1) : 10001;
                $data['nis'] = (string) $nextNis;

                $siswa = Siswa::create(array_merge(
                    collect($data)->except(['buat_akun','email','password'])->toArray(),
                    ['user_id' => $userId, 'nis' => $data['nis']]
                ));

                // Otomatis daftar ke Pramuka (atau ekskul pertama) agar setiap siswa punya minimal 1 ekskul
                $ta = TahunAjaran::where('is_active', true)->first();
                $ekskulDefault = Ekstrakurikuler::where('nama', 'like', '%Pramuka%')->first() ?? Ekstrakurikuler::first();
                if ($ta && $ekskulDefault) {
                    EkstrakurikulerSiswa::create([
                        'siswa_id'           => $siswa->id,
                        'ekstrakurikuler_id' => $ekskulDefault->id,
                        'tahun_ajaran_id'    => $ta->id,
                        'predikat'           => null,
                        'keterangan'         => 'Otomatis terdaftar',
                    ]);
                }
            });

            return redirect()->route('admin.siswa.index')
                ->with('success', "Siswa {$data['nama_lengkap']} berhasil ditambahkan.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', "Gagal menambahkan data siswa: " . $e->getMessage());
        }
    }

    public function edit(Siswa $siswa)
    {
        return view('admin.siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'nisn'           => ['required','string','size:10', Rule::unique('siswa','nisn')->ignore($siswa->id)],
            'nis'            => ['required','string','max:8', Rule::unique('siswa','nis')->ignore($siswa->id)],
            'nama_lengkap'   => 'required|string|max:100',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'nullable|string|max:60',
            'tanggal_lahir'  => 'nullable|date',
            'agama'          => 'nullable|string',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:15',
            'nama_ayah'      => 'nullable|string|max:80',
            'pekerjaan_ayah' => 'nullable|string|max:60',
            'nama_ibu'       => 'nullable|string|max:80',
            'pekerjaan_ibu'  => 'nullable|string|max:60',
            'no_hp_ortu'     => 'nullable|string|max:15',
            'alamat_ortu'    => 'nullable|string',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($siswa, $data) {
                $siswa->update($data);
                $siswa->user?->update(['name' => $data['nama_lengkap']]);
            });

            return redirect()->route('admin.siswa.index')
                ->with('success', "Data siswa {$data['nama_lengkap']} berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', "Gagal memperbarui data siswa: " . $e->getMessage());
        }
    }

    public function destroy(Siswa $siswa)
    {
        try {
            $nama = $siswa->nama_lengkap;
            \Illuminate\Support\Facades\DB::transaction(function () use ($siswa) {
                $siswa->user?->delete();
                $siswa->delete();
            });

            return redirect()->route('admin.siswa.index')
                ->with('success', "Siswa {$nama} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa.index')
                ->with('error', "Gagal menghapus data siswa: " . $e->getMessage());
        }
    }
}
