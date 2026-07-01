<?php

namespace App\Services;

use App\Models\SiswaBaru;
use App\Models\PembayaranSeragam;
use App\Models\ItemSeragam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SeragamService
{
    /**
     * Generate tagihan seragam untuk siswa baru
     */
    public function buatTagihan(SiswaBaru $siswa, array $items): PembayaranSeragam
    {
        if ($siswa->pembayaran()->exists()) {
            throw new \Exception('Siswa ini sudah memiliki tagihan seragam.');
        }

        return DB::transaction(function () use ($siswa, $items) {
            $total = 0;
            $detailData = [];

            foreach ($items as $item) {
                // Mencegah Race Condition dengan lockForUpdate()
                $seragam = ItemSeragam::lockForUpdate()->findOrFail($item['item_seragam_id']);

                if ($seragam->stok < $item['jumlah']) {
                    throw new \Exception("Stok {$seragam->nama_item} tidak mencukupi.");
                }

                $subtotal = $seragam->harga * $item['jumlah'];
                $total += $subtotal;

                $detailData[] = [
                    'item_seragam_id' => $seragam->id,
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $seragam->harga,
                    'subtotal' => $subtotal,
                ];

                // Kurangi stok
                $seragam->decrement('stok', $item['jumlah']);
            }

            // Menggunakan kombinasi waktu & string acak agar tidak duplikat
            $kode = 'SGM-' . date('Ymd') . '-' . strtoupper(Str::random(4));

            $pembayaran = PembayaranSeragam::create([
                'siswa_baru_id' => $siswa->id,
                'kode_tagihan' => $kode,
                'total_tagihan' => $total,
                'jumlah_bayar' => 0,
                'sisa_tagihan' => $total,
                'status' => 'belum', // Belum bayar
            ]);

            // Simpan detail (Menggunakan createMany lebih efisien)
            $pembayaran->details()->createMany($detailData);

            return $pembayaran;
        });
    }

    /**
     * Proses upload bukti bayar dari siswa
     */
    public function uploadBuktiBayar(PembayaranSeragam $pembayaran, float $jumlahBayar, string $metodeBayar, string $buktiBayarPath): void
    {
        // Status diubah ke menunggu_validasi, bukan langsung lunas
        $pembayaran->update([
            'jumlah_bayar' => $jumlahBayar,
            'sisa_tagihan' => max($pembayaran->total_tagihan - $jumlahBayar, 0),
            'metode_bayar' => $metodeBayar,
            'bukti_bayar' => $buktiBayarPath,
            'status' => 'menunggu_validasi', 
        ]);
    }

    /**
     * Admin memvalidasi pembayaran
     */
    public function validasiPembayaran(PembayaranSeragam $pembayaran, ?string $catatan = null): void
    {
        if ($pembayaran->status !== 'menunggu_validasi') {
            throw new \Exception('Tidak ada pembayaran baru yang perlu divalidasi.');
        }

        // Tentukan status akhir berdasarkan uang yang masuk
        $statusAkhir = $pembayaran->jumlah_bayar >= $pembayaran->total_tagihan ? 'lunas' : 'partial';

        $pembayaran->update([
            'status' => $statusAkhir,
            'divalidasi_oleh' => Auth::id(),
            'tanggal_validasi' => now(),
            'catatan' => $catatan,
        ]);
    }

    /**
     * Batalkan tagihan & kembalikan stok
     */
    public function batalTagihan(PembayaranSeragam $pembayaran): void
    {
        // Tagihan lunas/tervalidasi tidak boleh asal hapus
        if (in_array($pembayaran->status, ['lunas', 'partial', 'tervalidasi'])) {
            throw new \Exception('Tagihan yang sudah dibayar atau tervalidasi tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($pembayaran) {
            // Eager load relasi item untuk menghindari query N+1
            $pembayaran->load('details.item');

            foreach ($pembayaran->details as $detail) {
                $detail->item()->lockForUpdate()->increment('stok', $detail->jumlah);
            }

            $pembayaran->details()->delete();
            $pembayaran->delete();
        });
    }
}