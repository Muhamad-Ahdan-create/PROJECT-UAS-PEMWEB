<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSeragam;
use App\Services\SeragamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SeragamExport;
use Maatwebsite\Excel\Facades\Excel;

class SeragamAdminController extends Controller
{
    public function __construct(private SeragamService $service)
    {
    }

    public function index()
    {
        // 1. Ambil data pembayaran dengan urutan prioritas yang membutuhkan tindakan admin terlebih dahulu
        $pembayaran = PembayaranSeragam::with('siswaBaru')
            ->orderByRaw("FIELD(status, 'menunggu_validasi', 'partial', 'belum', 'lunas', 'tervalidasi')")
            ->latest()
            ->paginate(15);

        // 2. Optimasi: Ambil semua statistik status hanya dengan 1 query database
        $rawStats = PembayaranSeragam::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Menyusun stats dengan default nilai 0 jika status tersebut belum ada datanya di DB
        $stats = [
            'belum' => $rawStats['belum'] ?? 0,
            'menunggu_validasi' => $rawStats['menunggu_validasi'] ?? 0,
            'partial' => $rawStats['partial'] ?? 0,
            'lunas' => $rawStats['lunas'] ?? 0,
            'tervalidasi' => $rawStats['lunas'] ?? 0,
        ];

        return view('admin.seragam.index', compact('pembayaran', 'stats'));
    }

    // Menggunakan Route Model Binding agar konsisten
    public function show(PembayaranSeragam $seragam)
    {

        $seragam->load(['siswaBaru.user', 'details.item', 'validator']);
        return view('admin.seragam.show', compact('seragam'));
    }


    // Mengubah int $id menjadi Route Model Binding PembayaranSeragam $seragam
    public function validasi(Request $request, PembayaranSeragam $seragam)
    {
        $request->validate([
            'catatan' => 'nullable|string|max:500'
        ]);

        try {
            $this->service->validasiPembayaran($seragam, $request->catatan);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Pembayaran seragam berhasil divalidasi.');
    }
    public function cetakKwitansi(int $id)
    {
        $pembayaran = PembayaranSeragam::with([
            'siswaBaru',
            'details.item',
            'validator'
        ])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.kwitansi-seragam', compact('pembayaran'))->setPaper('a5', 'portrait');
        return $pdf->download("kwitansi-{$pembayaran->kode_tagihan}.pdf");
    }
    public function export()
    {
        return Excel::download(new SeragamExport, 'laporan-seragam-' . date('Ymd') . '.xlsx');
    }

}
