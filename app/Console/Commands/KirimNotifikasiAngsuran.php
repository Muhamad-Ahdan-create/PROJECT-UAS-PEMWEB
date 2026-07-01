<?php

namespace App\Console\Commands;

use App\Models\Angsuran;
use App\Notifications\JatuhTempoAngsuran;
use Illuminate\Console\Command;

class KirimNotifikasiAngsuran extends Command
{
    protected $signature = 'angsuran:notifikasi';
    protected $description = 'Kirim notifikasi jatuh tempo angsuran H-3';

    public function handle(): void
    {
        // Ambil angsuran yang jatuh tempo 3 hari lagi
        $angsuran = Angsuran::where('status', 'belum')
            ->whereDate('tanggal_bayar', now()->addDays(3)->toDateString())
            ->with('pinjaman.anggota.user')
            ->get();

        foreach ($angsuran as $a) {
            $user = $a->pinjaman->anggota->user;
            $user->notify(new JatuhTempoAngsuran($a));
            $this->line("Notifikasi dikirim ke: {$user->email}");
        }

        $this->info("Total: {$angsuran->count()} notifikasi dikirim.");
    }
}
