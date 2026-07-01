<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exports\AnggotaExport;
use Maatwebsite\Excel\Facades\Excel;

class Anggota extends Model
{
    protected $table = 'anggota';

    protected $fillable = [
        'user_id', 'nomor_anggota', 'nama_lengkap',
        'alamat', 'no_telp', 'tanggal_bergabung',
        'simpanan_pokok', 'simpanan_wajib',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pinjaman()
    {
        return $this->hasMany(Pinjaman::class);
    }

    public function pengajuan()
    {
        return $this->hasMany(PengajuanPinjaman::class);
    }

    public function pinjamanAktif()
    {
        return $this->hasMany(Pinjaman::class)->where('status', 'disetujui');
    }

    public function totalSisaPinjaman()
    {
        return $this->pinjamanAktif()->sum('sisa_pinjaman');
    }

    public function export()
{
    return Excel::download(new AnggotaExport, 'data-anggota-' . date('Ymd') . '.xlsx');
}

}
