<?php

namespace App\Notifications;

use App\Models\Angsuran;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class JatuhTempoAngsuran extends Notification
{
    public function __construct(public Angsuran $angsuran)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $pinjaman = $this->angsuran->pinjaman;
        return (new MailMessage)
            ->subject('Pengingat Angsuran Pinjaman - Koperasi Sekolah')
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Angsuran pinjaman Anda (Kode: {$pinjaman->kode_pinjaman}) bulan ke-{$this->angsuran->ke_bulan} akan jatuh tempo.")
            ->line("Jumlah: Rp " . number_format($this->angsuran->jumlah_bayar, 0, ',', '.'))
            ->line("Tanggal Jatuh Tempo: " . $this->angsuran->tanggal_bayar->format('d F Y'))
            ->action('Lihat Detail Pinjaman', url('/anggota/sisa-pinjaman'))
            ->line('Harap segera lakukan pembayaran. Terima kasih.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'angsuran_id' => $this->angsuran->id,
            'pinjaman_id' => $this->angsuran->pinjaman_id,
            'ke_bulan' => $this->angsuran->ke_bulan,
            'jumlah' => $this->angsuran->jumlah_bayar,
            'jatuh_tempo' => $this->angsuran->tanggal_bayar,
        ];
    }
}
