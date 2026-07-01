<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pinjaman extends Model
{
    protected $table = 'pinjaman';
    protected $fillable = [
        'anggota_id',
        'kode_pinjaman',
        'jumlah_pinjaman',
        'bunga_persen',
        'tenor_bulan',
        'angsuran_per_bulan',
        'sisa_pinjaman',
        'tanggal_pinjaman',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pinjaman' => 'date',
        'jumlah_pinjaman' => 'decimal:2',
        'sisa_pinjaman' => 'decimal:2',
        'angsuran_per_bulan' => 'decimal:2',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function angsuran()
    {
        return $this->hasMany(Angsuran::class);
    }

    public function angsuranBelumBayar()
    {
        return $this->hasMany(Angsuran::class)->where('status', 'belum');
    }
}
