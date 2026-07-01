<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\Anggota;
use App\Services\PinjamanService;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    public function __construct(private PinjamanService $service)
    {

    }

    public function index()
    {
        $pinjaman = Pinjaman::with('anggota')
            ->latest()
            ->paginate(15);

        $totalSisa = Pinjaman::where('status', 'disetujui')->sum('sisa_pinjaman');

        return view('admin.pinjaman.index', compact('pinjaman', 'totalSisa'));
    }

    public function show(Pinjaman $pinjaman)
    {
        $pinjaman->load(['anggota.user', 'angsuran']);
        return view('admin.pinjaman.show', compact('pinjaman'));
    }

    /**
     * Admin input pembayaran angsuran secara manual
     */
    public function bayarAngsuran(Request $request, Angsuran $angsuran)
    {
        if ($angsuran->status === 'lunas') {
            return back()->with('error', 'Angsuran ini sudah lunas.');
        }

        $this->service->bayarAngsuran($angsuran);

        return back()->with('success', "Angsuran bulan ke-{$angsuran->ke_bulan} berhasil dicatat sebagai lunas.");
    }

    public function destroy(Pinjaman $pinjaman)
    {
        if ($pinjaman->status === 'disetujui') {
            return back()->with('error', 'Pinjaman aktif tidak dapat dihapus.');
        }
        $pinjaman->delete();
        return redirect()->route('admin.pinjaman.index')->with('success', 'Pinjaman dihapus.');
    }
}
