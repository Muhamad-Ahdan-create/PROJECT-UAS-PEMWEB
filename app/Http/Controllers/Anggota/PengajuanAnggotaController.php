<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanAnggotaController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        $anggota = Auth::user()->anggota;
        $pengajuan = $anggota->pengajuan()->latest()->paginate(10);
        return view('anggota.pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        return view('anggota.pengajuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'jumlah_diajukan' => 'required|numeric|min:100000|max:50000000',
            'tujuan_pinjaman' => 'required|string|max:500',
            'tenor_diajukan' => 'required|integer|min:1|max:36',
            'jaminan' => 'nullable|string|max:200',
            'dokumen_path' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $anggota = Auth::user()->anggota;

        // Cek apakah masih ada pinjaman aktif
        $pinjamanAktif = $anggota->pinjamanAktif()->count();
        if ($pinjamanAktif > 0) {
            return back()->with('error', 'Anda masih memiliki pinjaman aktif. Selesaikan dulu sebelum mengajukan baru.');
        }

        $dokumenPath = null;
        if ($request->hasFile('dokumen_path')) {
            $dokumenPath = $request->file('dokumen_path')
                ->store('dokumen-pengajuan', 'public');
        }

        PengajuanPinjaman::create([
            'anggota_id' => $anggota->id,
            'jumlah_diajukan' => $request->jumlah_diajukan,
            'tujuan_pinjaman' => $request->tujuan_pinjaman,
            'tenor_diajukan' => $request->tenor_diajukan,
            'jaminan' => $request->jaminan,
            'dokumen_path' => $dokumenPath,
            'status' => 'diajukan',
            'tanggal_pengajuan' => now(),
        ]);

        return redirect()->route('anggota.pengajuan.index')
            ->with('success', 'Pengajuan pinjaman berhasil dikirim. Tunggu konfirmasi admin.');
    }

    public function show(PengajuanPinjaman $pengajuan)
    {
        // Pastikan hanya anggota ybs yang bisa lihat
        $anggota = Auth::user()->anggota;
        abort_if($pengajuan->anggota_id !== $anggota->id, 403);

        return view('anggota.pengajuan.show', compact('pengajuan'));
    }
}
