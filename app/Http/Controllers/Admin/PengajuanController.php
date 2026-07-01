<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Services\PinjamanService;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanController extends Controller
{
    public function __construct(private PinjamanService $service)
    {

    }

    public function index()
    {
        $pengajuan = PengajuanPinjaman::with('anggota')
            ->orderByRaw("FIELD(status, 'diajukan', 'diproses', 'disetujui', 'ditolak', 'draft')")
            ->paginate(15);

        return view('admin.pengajuan.index', compact('pengajuan'));
    }

    public function setujui(int $id)
    {
        $pengajuan = PengajuanPinjaman::findOrFail($id);

        if ($pengajuan->status !== 'diajukan') {
            return back()->with('error', 'Pengajuan tidak dalam status yang bisa disetujui.');
        }

        // Update status pengajuan
        $pengajuan->update(['status' => 'disetujui']);

        // Buat pinjaman + jadwal angsuran otomatis
        $this->service->buatPinjaman($pengajuan);

        return back()->with('success', 'Pengajuan disetujui. Pinjaman dan jadwal angsuran telah dibuat.');
    }

    public function tolak(\Illuminate\Http\Request $request, int $id)
    {
        $request->validate(['catatan_admin' => 'required|string']);

        $pengajuan = PengajuanPinjaman::findOrFail($id);
        $pengajuan->update([
            'status' => 'ditolak',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return back()->with('success', 'Pengajuan ditolak.');
    }
    public function cetakFormPengajuan(int $id)
    {
        $pengajuan = PengajuanPinjaman::with('anggota.user')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.form-pengajuan', compact('pengajuan'))
            ->setPaper('a4', 'portrait');
        return $pdf->download("pengajuan-{$id}.pdf");
    }

    public function show(int $id)
    {
        $pengajuan = PengajuanPinjaman::with('anggota')->findOrFail($id);
        return view('admin.pengajuan.show', compact('pengajuan'));
    }
}
