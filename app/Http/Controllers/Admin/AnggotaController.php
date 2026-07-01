<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index()
    {
        $anggota = Anggota::with('user')->latest()->paginate(15);
        return view('admin.anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'      => 'required|string|max:100',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:6',
            'alamat'            => 'nullable|string',
            'no_telp'           => 'nullable|string|max:20',
            'tanggal_bergabung' => 'required|date',
            'simpanan_pokok'    => 'required|numeric|min:0',
            'simpanan_wajib'    => 'required|numeric|min:0',
        ]);

        $user = User::create([
            'name'      => $request->nama_lengkap,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);
        $user->assignRole('anggota');

        $nomorUrut    = Anggota::count() + 1;
        $nomorAnggota = 'KOP-' . str_pad($nomorUrut, 3, '0', STR_PAD_LEFT);

        Anggota::create([
            'user_id'           => $user->id,
            'nomor_anggota'     => $nomorAnggota,
            'nama_lengkap'      => $request->nama_lengkap,
            'alamat'            => $request->alamat,
            'no_telp'           => $request->no_telp,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'simpanan_pokok'    => $request->simpanan_pokok,
            'simpanan_wajib'    => $request->simpanan_wajib,
        ]);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function show($id)
    {
        $anggota = Anggota::with(['pinjaman.angsuran', 'pengajuan'])->findOrFail($id);
        return view('admin.anggota.show', compact('anggota'));
    }

    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('admin.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        $request->validate([
            'nama_lengkap'   => 'required|string|max:100',
            'alamat'         => 'nullable|string',
            'no_telp'        => 'nullable|string|max:20',
            'simpanan_wajib' => 'required|numeric|min:0',
        ]);

        $anggota->update($request->only([
            'nama_lengkap', 'alamat', 'no_telp', 'simpanan_wajib',
        ]));

        $anggota->user->update(['name' => $request->nama_lengkap]);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Data anggota diperbarui.');
    }

    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->user->delete();
        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota dihapus.');
    }

    public function export()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AnggotaExport,
            'data-anggota-' . date('Ymd') . '.xlsx'
        );
    }
}