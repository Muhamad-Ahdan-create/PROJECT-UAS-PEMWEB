<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembayaranSeragam extends Model
{
    protected $table = 'pembayaran_seragam';

    protected $fillable = [
        'siswa_baru_id',
        'kode_tagihan',
        'total_tagihan',
        'jumlah_bayar',
        'sisa_tagihan',
        'metode_bayar',
        'bukti_bayar',
        'status',
        'divalidasi_oleh',
        'tanggal_validasi',
        'catatan',
    ];

    protected $casts = [
        'total_tagihan' => 'decimal:2',
        'jumlah_bayar' => 'decimal:2',
        'sisa_tagihan' => 'decimal:2',
        'tanggal_validasi' => 'datetime',
    ];

    public function siswaBaru()
    {
        // return $this->belongsTo(SiswaBaru::class);
        return $this->belongsTo(SiswaBaru::class, 'siswa_baru_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(DetailSeragam::class, 'pembayaran_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'divalidasi_oleh');
    }
}
