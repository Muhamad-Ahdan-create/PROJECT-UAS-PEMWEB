<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatCatatan extends Model
{
    protected $table = 'rat_catatan';

    protected $fillable = [
        'dibuat_oleh',
        'tahun_rat',
        'tanggal_rat',
        'tempat',
        'agenda',
        'notulensi',
        'hasil_keputusan',
        'dokumen_lampiran',
    ];

    protected $casts = [
        'tanggal_rat' => 'date',
    ];

    public function pembuat()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
