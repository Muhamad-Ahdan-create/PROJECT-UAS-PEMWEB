<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPinjaman extends Model
{
    protected $table = 'pengajuan_pinjaman';

    protected $fillable = [
        'anggota_id',
        'jumlah_diajukan',
        'tujuan_pinjaman',
        'tenor_diajukan',
        'jaminan',
        'dokumen_path',
        'status',
        'catatan_admin',
        'tanggal_pengajuan',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'datetime',
        'jumlah_diajukan' => 'decimal:2',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
