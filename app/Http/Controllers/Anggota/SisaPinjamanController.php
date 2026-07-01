<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Services\PinjamanService;
use Illuminate\Support\Facades\Auth;

class SisaPinjamanController extends Controller
{
    public function __construct(private PinjamanService $service)
    {
    
    }

    public function index()
    {
        $anggota = Auth::user()->anggota;
        $data = $this->service->sisaPinjamanAnggota($anggota->id);

        return view('anggota.sisa-pinjaman', [
            'pinjaman' => $data['pinjaman'],
            'totalSisa' => $data['total_sisa'],
            'anggota' => $anggota,
        ]);
    }
}
