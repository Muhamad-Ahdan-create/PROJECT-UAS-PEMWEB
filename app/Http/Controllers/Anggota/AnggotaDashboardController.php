<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Services\PinjamanService;
use Illuminate\Support\Facades\Auth;

class AnggotaDashboardController extends Controller
{
    public function __construct(private PinjamanService $service)
    {
    
    }

    public function index()
    {
        $anggota = Auth::user()->anggota;
        $pinjamanData = $this->service->sisaPinjamanAnggota($anggota->id);

        $angsuranMendatang = collect();
        foreach ($pinjamanData['pinjaman'] as $p) {
            $next = $p->angsuran->where('status', 'belum')->sortBy('ke_bulan')->first();
            if ($next)
                $angsuranMendatang->push($next);
        }

        return view('anggota.dashboard', [
            'anggota' => $anggota,
            'totalSisaPinjaman' => $pinjamanData['total_sisa'],
            'jumlahPinjaman' => $pinjamanData['pinjaman']->count(),
            'angsuranMendatang' => $angsuranMendatang,
        ]);
    }
}
