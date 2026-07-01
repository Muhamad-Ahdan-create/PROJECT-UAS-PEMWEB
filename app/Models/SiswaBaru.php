<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiswaBaru extends Model
{
    protected $table = 'siswa_baru';

    protected $fillable = [
        'user_id',
        'nisn',
        'nama_lengkap',
        'kelas',
        'jurusan',
        'tahun_masuk',
        'nama_orang_tua',
        'no_telp_ortu',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(PembayaranSeragam::class);
    }
}
