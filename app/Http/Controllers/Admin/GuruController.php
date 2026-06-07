<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with('user')
            ->when($request->search, fn($q, $s) =>
                $q->where('nama_lengkap', 'like', "%$s%")
                  ->orWhere('nip', 'like', "%$s%")
                  ->orWhere('bidang_studi', 'like', "%$s%")
            )
            ->latest();

        $guru = $query->paginate(15)->withQueryString();
        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nip'            => 'nullable|string|max:20|unique:guru,nip',
            'nama_lengkap'   => 'required|string|max:100',
            'email'          => 'nullable|email|unique:users,email',
            'password'       => 'nullable|min:6',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'nullable|string|max:60',
            'tanggal_lahir'  => 'nullable|date',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:15',
            'gelar_depan'    => 'nullable|string|max:20',
            'gelar_belakang' => 'nullable|string|max:30',
            'bidang_studi'   => 'nullable|string|max:80',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, &$data) {
                // Auto Generate Email
                if (empty($data['email'])) {
                    $emailName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $data['nama_lengkap']));
                    $email = $emailName . '@smpn1sby.sch.id';
                    $counter = 1;
                    while (User::withTrashed()->where('email', $email)->exists()) {
                        $email = $emailName . $counter . '@smpn1sby.sch.id';
                        $counter++;
                    }
                    $data['email'] = $email;
                }

                // Auto Generate Password
                if (empty($data['password'])) {
                    $data['password'] = 'Guru@1234';
                }

                $user = User::create([
                    'name'      => $data['nama_lengkap'],
                    'email'     => $data['email'],
                    'password'  => Hash::make($data['password']),
                    'role'      => 'guru',
                    'is_active' => true,
                ]);

                Guru::create(array_merge(
                    collect($data)->except(['email','password'])->toArray(),
                    ['user_id' => $user->id]
                ));
            });

            return redirect()->route('admin.guru.index')
                ->with('success', "Guru {$data['nama_lengkap']} berhasil ditambahkan.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', "Gagal menambahkan data guru: " . $e->getMessage());
        }
    }

    public function edit(Guru $guru)
    {
        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $data = $request->validate([
            'nip'            => ['nullable','string','max:20', Rule::unique('guru','nip')->ignore($guru->id)],
            'nama_lengkap'   => 'required|string|max:100',
            'email'          => ['required','email', Rule::unique('users','email')->ignore($guru->user_id)],
            'password'       => 'nullable|min:6',
            'jenis_kelamin'  => 'required|in:L,P',
            'tempat_lahir'   => 'nullable|string|max:60',
            'tanggal_lahir'  => 'nullable|date',
            'alamat'         => 'nullable|string',
            'no_hp'          => 'nullable|string|max:15',
            'gelar_depan'    => 'nullable|string|max:20',
            'gelar_belakang' => 'nullable|string|max:30',
            'bidang_studi'   => 'nullable|string|max:80',
        ]);

        // Update user
        $userUpdate = ['name' => $data['nama_lengkap'], 'email' => $data['email']];
        if (!empty($data['password'])) {
            $userUpdate['password'] = Hash::make($data['password']);
        }
        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($guru, $data, $userUpdate) {
                $guru->user?->update($userUpdate);
                $guru->update(collect($data)->except(['email','password'])->toArray());
            });

            return redirect()->route('admin.guru.index')
                ->with('success', "Data guru {$data['nama_lengkap']} berhasil diperbarui.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', "Gagal memperbarui data guru: " . $e->getMessage());
        }
    }

    public function destroy(Guru $guru)
    {
        try {
            $nama = $guru->nama_lengkap;
            \Illuminate\Support\Facades\DB::transaction(function () use ($guru) {
                $guru->user?->delete(); // soft delete user
                $guru->delete();        // soft delete guru
            });

            return redirect()->route('admin.guru.index')
                ->with('success', "Guru {$nama} berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->route('admin.guru.index')
                ->with('error', "Gagal menghapus data guru: " . $e->getMessage());
        }
    }
}
