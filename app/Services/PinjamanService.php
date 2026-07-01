<?php

namespace App\Services;

use App\Models\Pinjaman;
use App\Models\Angsuran;
use App\Models\PengajuanPinjaman;

class PinjamanService
{
    /**
     * Hitung angsuran per bulan (metode flat rate)
     * Formula: (Pokok + Total Bunga) / Tenor
     */
    public function hitungAngsuran(float $jumlah, float $bungaPersen, int $tenor): array
    {
        $totalBunga = $jumlah * ($bungaPersen / 100) * $tenor;
        $totalKewajiban = $jumlah + $totalBunga;
        $angsuranPerBulan = $totalKewajiban / $tenor;
        $pokokPerBulan = $jumlah / $tenor;
        $bungaPerBulan = $totalBunga / $tenor;

        return [
            'angsuran_per_bulan' => round($angsuranPerBulan, 2),
            'pokok_per_bulan' => round($pokokPerBulan, 2),
            'bunga_per_bulan' => round($bungaPerBulan, 2),
            'total_kewajiban' => round($totalKewajiban, 2),
            'total_bunga' => round($totalBunga, 2),
        ];
    }

    /**
     * Buat pinjaman baru dari pengajuan yang disetujui
     * Sekaligus generate jadwal angsuran
     */
    public function buatPinjaman(PengajuanPinjaman $pengajuan): Pinjaman
    {
        $bungaPersen = 1.5; // bisa diambil dari config
        $kalkulasi = $this->hitungAngsuran(
            (float) $pengajuan->jumlah_diajukan,
            $bungaPersen,
            (int) $pengajuan->tenor_diajukan
        );

        // Buat kode pinjaman unik
        $kode = 'PJM-' . date('Ymd') . '-' . str_pad(Pinjaman::count() + 1, 4, '0', STR_PAD_LEFT);

        $pinjaman = Pinjaman::create([
            'anggota_id' => $pengajuan->anggota_id,
            'kode_pinjaman' => $kode,
            'jumlah_pinjaman' => $pengajuan->jumlah_diajukan,
            'bunga_persen' => $bungaPersen,
            'tenor_bulan' => $pengajuan->tenor_diajukan,
            'angsuran_per_bulan' => $kalkulasi['angsuran_per_bulan'],
            'sisa_pinjaman' => $kalkulasi['total_kewajiban'],
            'tanggal_pinjaman' => now(),
            'status' => 'disetujui',
        ]);

        // Generate jadwal angsuran
        $this->generateJadwalAngsuran($pinjaman, $kalkulasi);

        return $pinjaman;
    }

    /**
     * Generate semua baris angsuran (belum bayar)
     */
    public function generateJadwalAngsuran(Pinjaman $pinjaman, array $kalkulasi): void
    {
        for ($i = 1; $i <= $pinjaman->tenor_bulan; $i++) {
            Angsuran::create([
                'pinjaman_id' => $pinjaman->id,
                'ke_bulan' => $i,
                'jumlah_bayar' => $kalkulasi['angsuran_per_bulan'],
                'pokok' => $kalkulasi['pokok_per_bulan'],
                'bunga' => $kalkulasi['bunga_per_bulan'],
                'tanggal_bayar' => now()->addMonths($i)->startOfMonth(),
                'status' => 'belum',
            ]);
        }
    }

    /**
     * Bayar satu angsuran & update sisa pinjaman
     */
    public function bayarAngsuran(Angsuran $angsuran): void
    {
        $angsuran->update([
            'status' => 'lunas',
            'tanggal_bayar' => now(),
        ]);

        $pinjaman = $angsuran->pinjaman;

        // Kurangi sisa pinjaman
        $sisaBaru = (float) $pinjaman-> sisa_pinjaman - (float) $angsuran->jumlah_bayar;
        $pinjaman->sisa_pinjaman = max ($sisaBaru, 0);

        // Cek apakah semua angsuran sudah lunas
        $masihAda = $pinjaman->angsuran()->where('status', 'belum')->count();
        if ($masihAda === 0) {
            $pinjaman->status = 'lunas';
            $pinjaman->sisa_pinjaman = 0;
        }

        $pinjaman->save();
    }

    /**
     * Hitung sisa pinjaman aktif seorang anggota
     */
    public function sisaPinjamanAnggota(int $anggotaId): array
    {
        $pinjaman = Pinjaman::where('anggota_id', $anggotaId)
            ->where('status', 'disetujui')
            ->with('angsuran')
            ->get();

        $total = $pinjaman->sum('sisa_pinjaman');

        return [
            'pinjaman' => $pinjaman,
            'total_sisa' => $total,
        ];
    }

    /**
     * Tandai angsuran yang terlambat (untuk di-cron)
     */
    public function tandaiTerlambat(): int
    {
        return Angsuran::where('status', 'belum')
            ->where('tanggal_bayar', '<', now())
            ->update(['status' => 'terlambat']);
    }
}
