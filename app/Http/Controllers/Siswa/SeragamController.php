<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Services\SeragamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Tambahkan facade Storage

class SeragamController extends Controller
{
    public function __construct(private SeragamService $service)
    {
        // $this->middleware(['auth', 'role:siswa_baru']);
    }

    public function tagihan()
    {
        $siswa = Auth::user()->siswaBaru;

        // Benarkan urutan: validasi objek siswa terlebih dahulu
        if (!$siswa) {
            abort(403, 'Data profil siswa Anda tidak ditemukan. Hubungi admin.');
        }

        // Baru load data pembayaran beserta detail itemnya
        $pembayaran = $siswa->pembayaran?->load('details.item');

        return view('siswa.seragam.tagihan', compact('siswa', 'pembayaran'));
    }

    public function uploadBukti(Request $request)
    {
        $siswa = Auth::user()->siswaBaru;
        
        if (!$siswa) {
            return back()->with('error', 'Profil siswa tidak ditemukan.');
        }

        $pembayaran = $siswa->pembayaran;

        if (!$pembayaran) {
            return back()->with('error', 'Tagihan tidak ditemukan.');
        }

        // Validasi status: Cegah upload jika sudah tervalidasi atau sedang ditinjau admin
        if (in_array($pembayaran->status, ['lunas', 'tervalidasi'])) {
            return back()->with('error', 'Pembayaran Anda sudah tervalidasi atau sudah lunas.');
        }

        if ($pembayaran->status === 'menunggu_validasi') {
            return back()->with('error', 'Bukti sebelumnya sedang diperiksa oleh admin. Mohon tunggu.');
        }

        // Validasi request secara ketat (Maksimal input sesuai sisa tagihan)
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1|max:' . $pembayaran->sisa_tagihan,
            'metode_bayar' => 'required|string|in:Transfer Bank,QRIS,Tunai',
            'bukti_bayar'  => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'jumlah_bayar.max' => 'Jumlah bayar tidak boleh melebihi sisa tagihan Anda (Rp ' . number_format($pembayaran->sisa_tagihan, 0, ',', '.') . ').'
        ]);

        // Optimasi Storage: Hapus file bukti bayar lama jika siswa melakukan upload ulang
        if ($pembayaran->bukti_bayar && Storage::disk('public')->exists($pembayaran->bukti_bayar)) {
            Storage::disk('public')->delete($pembayaran->bukti_bayar);
        }

        // Simpan file bukti baru
        $path = $request->file('bukti_bayar')->store('bukti-seragam', 'public');

        try {
            $this->service->uploadBuktiBayar(
                $pembayaran,
                (float) $request->jumlah_bayar,
                $request->metode_bayar,
                $path
            );
        } catch (\Exception $e) {
            // Jika service gagal, hapus kembali file yang baru saja diupload agar tidak mengotori storage
            Storage::disk('public')->delete($path);
            
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu validasi admin.');
    }
}