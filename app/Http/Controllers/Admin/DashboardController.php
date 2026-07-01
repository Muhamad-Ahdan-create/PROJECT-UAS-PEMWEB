<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\Pinjaman;
use App\Models\PengajuanPinjaman;
use App\Models\PembayaranSeragam;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_anggota'             => Anggota::count(),
            'total_pinjaman_aktif'      => Pinjaman::where('status', 'disetujui')->count(),
            'total_sisa_pinjaman'       => Pinjaman::where('status', 'disetujui')->sum('sisa_pinjaman'),
            'pengajuan_pending'         => PengajuanPinjaman::where('status', 'diajukan')->count(),
            'seragam_belum_lunas'       => PembayaranSeragam::whereIn('status', ['belum', 'partial'])->count(),
            'seragam_menunggu_validasi' => PembayaranSeragam::where('status', 'menunggu_validasi')->count(),
        ];

        $pinjamanTerbaru = Pinjaman::with('anggota')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        $pengajuanTerbaru = PengajuanPinjaman::with('anggota')
            ->where('status', 'diajukan')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        return view('admin.dashboard', compact('stats', 'pinjamanTerbaru', 'pengajuanTerbaru'));
    }
}